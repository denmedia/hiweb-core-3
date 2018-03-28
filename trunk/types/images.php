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
		use hiweb\path;


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


			private function html_row( $img_id = null ){
				$img_src = false;
				$img_path = '';
				if( !is_null( $img_id ) ){
					$img_url = wp_get_attachment_image_src( $img_id, [
						$this->preview_width,
						$this->preview_height
					] );
					if( $img_url !== false ){
						$img_path = path::url_to_path( $img_url[0] );
					}
					if( trim( $img_path ) != '' && file_exists( $img_path ) && is_file( $img_path ) && is_readable( $img_path ) ){
						$img_src = $img_url[0];
					}
				}
				if( is_null( $img_id ) || ( is_string( $img_src ) && trim( $img_src ) != '' ) ){
					?>
					<li tabindex="0" <?= is_null( $img_id ) ? 'data-source' : 'data-image-id="' . $img_id . '"' ?> class="attachment" style="width: <?= $this->get_col_width() ?>;">
						<input type="hidden" value="<?= $img_id ?>" <?= is_null( $img_id ) ? 'data-' : '' ?>name="<?= $this->name() ?>[]"/>
						<div class="attachment-preview type-image subtype-png landscape">
							<div class="thumbnail">
								<div data-click-remove=""><i class="dashicons dashicons-dismiss"></i></div>
								<div class="centered">
									<img src="<?= $img_src ?>" alt="">
								</div>
							</div>
						</div>
					</li>
					<?php
				}
			}


			public function html(){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-images.js', [ 'jquery-ui-sortable' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-images.css' );
				////
				ob_start();
				?>
				<div class="postbox hiweb-field-images" <?= $this->sanitize_attributes() ?>>

					<div data-ctrl>
						<button><i class="dashicons dashicons-update" data-click-reverse></i></button>
						<button><i class="dashicons dashicons-marker" data-click-clear></i></button>
						<button><i class="dashicons dashicons-plus-alt" data-click-add></i></button>
					</div>

					<ul tabindex="-1" class="attachments">
						<li class="attachment" data-ctrl-sub="left" style="width: <?= $this->get_col_width() ?>;">
							<div class="attachment-preview">
								<div class="thumbnail">
									<i class="dashicons dashicons-plus-alt"></i>
								</div>
							</div>
						</li>
						<?php $this->html_row() ?>
						<div class="items">
							<?php
								if( $this->VALUE()->have_images() ){
									foreach( $this->VALUE()->get_sanitized() as $item ){
										$this->html_row( $item );
									}
								}
							?>
						</div>
						<li class="attachment" data-ctrl-sub="right" style="width: <?= $this->get_col_width() ?>;">
							<div class="attachment-preview">
								<div class="thumbnail">
									<i class="dashicons dashicons-plus-alt"></i>
								</div>
							</div>
						</li>
					</ul>
					<div class="clear"></div>

				</div>
				<?php
				////
				return ob_get_clean();
			}


		}


		class value extends \hiweb\fields\value{


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