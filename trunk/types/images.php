<?php

	namespace {


		if( !function_exists( 'add_field_images' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\images\field
			 */
			function add_field_images( $id ){
				$new_field = new hiweb\fields\types\images\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\images {


		use hiweb\arrays;
		use hiweb\images;


		class field extends \hiweb\fields\field{


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function preview_width( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function preview_height( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function preview_cols( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public $preview_width = 150;
			public $preview_height = 80;
			public $preview_cols = 10;


			/**
			 * @return string
			 */
			private function get_col_width( $koof = 1 ){
				$cols = intval( $this->preview_cols );
				$cols = $cols < 1 ? 10 : $cols;
				return ceil( 100 / $cols / $koof ) . '%';
			}


			private function html_row( $IMAGE = null ){
				$is_source = false;
				if( !( $IMAGE instanceof images\image ) ){
					$is_source = true;
					$IMAGE = images::get( 0 );
				}
				/** @var images\image $IMAGE */
				if( $is_source || $IMAGE->is_attachment_exists() ){
					?>
					<li tabindex="0" <?= $is_source ? 'data-source' : 'data-image-id="' . $IMAGE->get_attachment_id() . '"' ?> data-file-name data-tooltip="<?= $is_source ? '' : $IMAGE->get_size_original()->filename() ?>" data-variation="mini">
						<div class="inner">
							<input type="hidden" value="<?= $IMAGE->get_attachment_id() ?>" <?= $is_source ? 'data-' : '' ?>name="<?= $this->name() ?>[]"/>
							<div class="overlay">
								<div class="background"></div>
								<div data-ctrl>
									<div class="ui buttons">
										<div class="ui icon primary button" data-click-edit>
											<i class="edit outline icon"></i>
										</div>
										<div class="ui icon button" data-click-remove>
											<i class="window close outline icon"></i>
										</div>
									</div>
								</div>
							</div>
							<img src="<?= $IMAGE->get_src([92,92], 0) ?>" alt="">
						</div>
					</li>
					<?php
				}
			}


			public function html(){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-images.min.js', [ 'jquery-ui-sortable' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-images.min.css' );
				////
				$IMAGES = [];
				foreach( $this->VALUE()->get_sanitized() as $image_id ){
					$IMAGE = images::get( $image_id );
					if( $IMAGE->is_attachment_exists() ){
						$IMAGES[] = $IMAGE;
					}
				}
				ob_start();
				?>
				<div class="hiweb-field-images" data-images-count="<?= count( $IMAGES ) ?>">
					<div class="ui segments">
						<div class="ui mini top attached menu">
							<div class="ui item">
								Изображений: <b data-images-count class="text"><?= count( $IMAGES ) ?></b>
							</div>
							<div class="ui right dropdown icon item">
								<i class="ellipsis vertical icon"></i>
								<div class="menu">
									<div class="item">
										<i class="plus square icon"></i>
										<span class="text" data-ctrl-sub="right">Добавить файл(ы) в конце</span>
									</div>
									<div class="item">
										<i class="plus square outline icon"></i>
										<span class="text" data-ctrl-sub="left">Добавить файл(ы) в начале</span>
									</div>
									<div class="divider"></div>
									<div class="item" data-click-reverse>
										<i class="retweet icon"></i>
										<span class="text">Обратить порядок</span>
									</div>
									<div class="item" data-click-random>
										<i class="random icon"></i>
										<span class="text">Смешать порядок</span>
									</div>
									<div class="divider"></div>
									<div class="item" data-click-clear>
										<i class="trash alternate icon"></i>
										<span class="text">Убрать все изображения</span>
									</div>
								</div>
							</div>
						</div>
						<div class="ui bottom attached secondary segment">
							<div data-message-empty>
								Не выбрано ни одного изображения. Для добавления одного или более, кликните по кнопке сверху справа
								<button class="ui tiny labeled primary icon button" data-click-add>
									<i class="plus icon"></i>
									Добавить изображение
								</button>
							</div>
							<ul data-images-wrap>
								<?php
									$this->html_row();
									foreach( $IMAGES as $IMAGE ){
										$this->html_row( $IMAGE );
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				<?php
				////
				return ob_get_clean();
			}


		}


		class value extends \hiweb\fields\value{

			/**
			 * @param $value
			 * @return mixed
			 */
			public function sanitize( $value ){
				return is_array( $value ) ? $value : [];
			}


			/**
			 * @return array
			 */
			public function value_sanitize(){
				return is_array( $this->data ) ? $this->data : [];
			}


			/**
			 * @return bool
			 */
			public function have_images(){
				return !arrays::is_empty( $this->get_sanitized() );
			}
		}
	}