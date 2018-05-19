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


			public function html() {
				wp_enqueue_media();
				css( HIWEB_URL_CSS . '/field-image.css' );
				js( HIWEB_URL_JS . '/field-image.js' );
				///
				/** @var field $parent_field */
				$parent_field = $this->get_parent_field();
				$attr_width   = $parent_field->preview_width();
				$attr_height  = $parent_field->preview_height();
				$preview      = false;
				$image_small  = true;
				///
				if ( $this->have_image() ) {
					$image        = images::get( $this->VALUE()->get() );
					$preview      = $image->get_src( [ $attr_width, $attr_height ], true );
					$preview_size = $image->desire_to_size( $attr_width, $attr_height, - 1 );
					$image_small  = ! ( $attr_width <= $preview_size[0] && $attr_height <= $preview_size[1] );
				}
				///
				ob_start();
				?>
				<div class="hiweb-field-image" id="<?= $this->global_id() ?>" data-has-image="<?= $this->have_image() ? '1' : '0' ?>" data-image-small="<?= $image_small ? '1' : '0' ?>">
					<input type="hidden" <?= $this->sanitize_attributes() ?> value="<?= ( $this->has_image ? $this->VALUE()->get_sanitized() : '' ) ?>"/>
					<a href="#" class="image-select" title="<?= __( 'Select/Deselect image...' ) ?>" data-click="<?= ( $this->have_image() ? 'deselect' : 'select' ) ?>" style="width: <?= $attr_width ?>px; height: <?= $attr_height ?>px;">
						<div class="thumbnail" style="<?= $this->have_image() ? 'background-image:url(' . $preview . ')' : '' ?>"></div>
						<div class="overlay"></div>
						<i class="dashicons dashicons-format-image" data-icon="select"></i>
						<i class="dashicons dashicons-dismiss" data-icon="deselect"></i>
					</a>
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