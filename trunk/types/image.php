<?php

	namespace {


		if ( ! function_exists( 'add_field_image' ) ) {
			/**
			 * @param $id
			 *
			 * @return \hiweb\fields\types\image\field
			 */
			function add_field_image( $id ) {
				$new_field = new hiweb\fields\types\image\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\image {


		use function hiweb\css;
		use hiweb\files;
		use hiweb\images;
		use function hiweb\js;
		use hiweb\path;


		class field extends \hiweb\fields\field{

			protected $preview_width = 250;
			protected $preview_height = 120;


			/**
			 * @param null $set
			 *
			 * @return $this|null
			 */
			public function preview_width( $set = null ) {
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 *
			 * @return $this|null
			 */
			public function preview_height( $set = null ) {
				return $this->set_property( __FUNCTION__, $set );
			}


			protected function get_input_class() {
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class() {
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			private $has_image = [];


			private $has_file = [];


			/**
			 * Возвращает URL до изображения
			 *
			 * @param $value - attachment id
			 * @param string $size
			 *
			 * @return bool|string
			 */
			private function get_src( $value, $size = 'thumbnail' ) {
				$img = false;
				if ( is_numeric( $value ) ) {
					$thumb = wp_get_attachment_image_src( $value, $size );
					if ( is_array( $thumb ) ) {
						$img = $thumb[0];
					}
				}

				return strpos( $img, 'http' ) === 0 ? $img : false;
			}


			/**
			 * Возвращает TRUE, если файл существует
			 *
			 * @param string $size
			 *
			 * @return bool
			 */
			public function have_image( $size = 'thumbnail' ) {
				$key = json_encode( $size );
				if ( array_key_exists( $key, $this->has_image ) ) {
					return $this->has_image[ $key ];
				}
				$img_url = $this->get_src( $this->VALUE()->get_sanitized(), $size );
				if ( $img_url === false ) {
					return false;
				}
				$img_path                = path::url_to_path( $img_url );
				$this->has_image[ $key ] = file_exists( $img_path );

				return $this->has_image[ $key ];
			}

			/**
			 * Возвращает TRUE, если файл существует
			 * @return bool
			 */
			public function have_file(){
				$attachment_url = wp_get_attachment_url( $this->VALUE()->get() );
				$this->has_file[ $attachment_url ] = path::is_readable( $attachment_url );
				return $this->has_file[ $attachment_url ];
			}


			public function html() {
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-file.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-file.css' );
				///
				$file = false;
				$attachment_id = $this->VALUE()->get();
				if( $this->have_file() ){
					$file = files::get( wp_get_attachment_url( $attachment_id ) );
				}
				ob_start();
				?>
				<div class="ui move up reveal hiweb-field-image" id="<?= $this->global_id() ?>" data-has-file="<?= $this->have_file() ? '1' : '0' ?>" data-file-mime="<?= $file instanceof files\file ? $file->mime : '' ?>" data-file-image="<?= $file instanceof files\file ? ( $file->is_image() ? 'image' : 'file' ) : '' ?>">
					<div class="visible content">
						<div class="ui center aligned raised segment" data-segment="1">
							<i class="question circle outline icon" data-icon="select"></i>
							<i class="file alternate outline icon" data-icon="deselect"></i>
							<div class="thumbnail" <?= ( $file instanceof files\file ? $file->is_image() : false ) ? 'style="background-image: url(' . get_image( $attachment_id )->get_src() . ')"' : '' ?>>

							</div>
						</div>
						<div class="ui bottom attached label" data-file-nonselect-text>Файл не выбран</div>
						<div class="ui bottom attached blue label" data-file-name><?= $file instanceof files\file ? $file->basename : '' ?></div>
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
					<input type="hidden" <?= $this->sanitize_attributes() ?> value="<?= ( $this->has_file ? $this->VALUE()->get() : '' ) ?>"/>
				</div>
				<?php
				return ob_get_clean();
			}

		}


		class value extends \hiweb\fields\value{


			/**
			 * @param string $size
			 * @param bool $return_image_html
			 * @param null $null
			 *
			 * @return bool|mixed|string
			 */
			public function get_content( $size = 'thumbnail', $return_image_html = false, $null = null ) {
				if ( ! is_numeric( $this->data ) ) {
					return false;
				}
				if ( $return_image_html ) {
					return wp_get_attachment_image( $this->data, $size );
				}
				$R = wp_get_attachment_image_src( $this->data, $size );
				if ( ! is_array( $R ) || ! array_key_exists( 0, $R ) ) {
					return false;
				}

				return $R[0];
			}


		}
	}