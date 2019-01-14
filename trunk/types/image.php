<?php

	namespace {


		if( !function_exists( 'add_field_image' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\image\field
			 */
			function add_field_image( $id ){
				$new_field = new hiweb\fields\types\image\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\image {


		use hiweb\files;
		use hiweb\images;
		use hiweb\paths;


		class field extends \hiweb\fields\field{

			protected $preview_width = 250;
			protected $preview_height = 120;


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function preview_width( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function preview_height( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			//			private $has_image = [];
			//
			//
			//			private $has_file = [];
			//
			//
			//			/**
			//			 * Возвращает URL до изображения
			//			 * @version 2.0
			//			 * @param        $value - attachment id
			//			 * @param string $size
			//			 * @return bool|string
			//			 */
			//			private function get_src( $value, $size = 'thumbnail' ){
			//				//				$img = false;
			//				//				if( is_numeric( $value ) ){
			//				//					$thumb = wp_get_attachment_image_src( $value, $size );
			//				//					if( is_array( $thumb ) ){
			//				//						$img = $thumb[0];
			//				//					}
			//				//				}
			//				//
			//				//				return strpos( $img, 'http' ) === 0 ? $img : false;
			//				$image = \hiweb\images::get( $value );
			//				if( !$image->is_attachment_exists() ) return false;
			//				return $image->get_src( $size );
			//			}
			//
			//
			//			/**
			//			 * Возвращает TRUE, если файл существует
			//			 * @param string $size
			//			 * @return bool
			//			 */
			//			public function have_image( $size = 'thumbnail' ){
			//				$key = json_encode( $size );
			//				if( array_key_exists( $key, $this->has_image ) ){
			//					return $this->has_image[ $key ];
			//				}
			//				$img_url = $this->get_src( $this->VALUE()->get_sanitized(), $size );
			//				if( $img_url === false ){
			//					return false;
			//				}
			//				$img_path = paths::get( $img_url )->get_path();
			//				$this->has_image[ $key ] = file_exists( $img_path );
			//
			//				return $this->has_image[ $key ];
			//			}
			//
			//
			//			/**
			//			 * Возвращает TRUE, если файл существует
			//			 * @return bool
			//			 */
			//			public function have_file(){
			//				$attachment_url = wp_get_attachment_url( $this->VALUE()->get() );
			//				$this->has_file[ $attachment_url ] = paths::get( $attachment_url )->is_readable();
			//				return $this->has_file[ $attachment_url ];
			//			}

			public function html(){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-file.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-file.css' );
				///
				$attachment_id = intval( $this->VALUE()->get() );
				$FILE = false;
				$data_file_mime = '';
				$data_file_image = '';
				$data_image_bg = '';
				$file_base_name = '';
				if( $attachment_id > 0 ){
					$FILE = paths::get( get_attached_file($attachment_id) );
					$IMAGE = images::get( $attachment_id );
					$data_file_mime = $FILE->mime();
					$data_file_image = $FILE->is_image() ? 'image' : 'file';
					$data_image_bg = $FILE->is_image() ? 'style="background-image: url(' . $IMAGE->get_src( 'thumbnail' ) . ')"' : '';
					$file_base_name = $FILE->basename();
				}
				ob_start();
				?>
				<div class="ui move up reveal hiweb-field-image" id="<?= $this->global_id() ?>" data-has-file="<?= ( $FILE instanceof paths\path && $FILE->is_readable() ) ? '1' : '0' ?>" data-file-mime="<?= $data_file_mime ?>" data-file-image="<?= $data_file_image ?>">
					<div class="visible content">
						<div class="ui center aligned raised segment" data-segment="1">
							<i class="question circle outline icon" data-icon="select"></i>
							<i class="file alternate outline icon" data-icon="deselect"></i>
							<div class="thumbnail" <?= $data_image_bg ?>></div>
						</div>
						<div class="ui bottom attached label" data-file-nonselect-text>Файл не выбран</div>
						<div class="ui bottom attached mini blue label" data-file-name><?= $file_base_name ?></div>
					</div>
					<div class="hidden content">
						<div class="ui segment center aligned" data-segment="2">

							<div class="big ui primary icon button" data-click="select" data-tooltip="Выбрать файл..." data-inverted="">
								<i class="folder open outline icon"></i>
							</div>

							<div class="ui buttons">
								<div class="ui icon primary button" data-click="edit" data-tooltip="Редактировать..." data-inverted="">
									<i class="edit outline icon"></i>
								</div>
								<div class="ui icon button" data-click="deselect" data-tooltip="Отменить выбор" data-inverted="">
									<i class="window close outline icon"></i>
								</div>
							</div>

						</div>
					</div>
					<input type="hidden" <?= $this->sanitize_attributes() ?> value="<?= ( $FILE instanceof paths\path && $FILE->is_readable() ? $attachment_id : '' ) ?>"/>
				</div>
				<?php
				return ob_get_clean();
			}

		}


		class value extends \hiweb\fields\value{


			/**
			 * @param string $size
			 * @param bool   $return_image_html
			 * @param null   $null
			 * @return bool|mixed|string
			 */
			public function get_content( $size = 'thumbnail', $return_image_html = false, $null = null ){
				if( !is_numeric( $this->data ) ){
					return false;
				}
				if( $return_image_html ){
					return wp_get_attachment_image( $this->data, $size );
				}
				$R = wp_get_attachment_image_src( $this->data, $size );
				if( !is_array( $R ) || !array_key_exists( 0, $R ) ){
					return false;
				}

				return $R[0];
			}


		}
	}