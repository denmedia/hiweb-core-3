<?php

	namespace {


		if( !function_exists( 'add_field_image' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\image
			 */
			function add_field_image( $id ){
				$new_field = new hiweb\fields\types\image( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;
		use hiweb\path;


		class image extends field{

			private $preview_width = 250;
			private $preview_height = 120;
			private $has_image = [];


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


			/**
			 * Возвращает URL до изображения
			 * @param $value - attachment id
			 * @param string $size
			 * @return bool|string
			 */
			private function get_src( $value, $size = 'thumbnail' ){
				$img = false;
				if( is_numeric( $value ) ){
					$thumb = wp_get_attachment_image_src( $value, $size );
					if( is_array( $thumb ) ){
						$img = $thumb[0];
					}
				}
				return strpos( $img, 'http' ) === 0 ? $img : false;
			}


			/**
			 * Возвращает TRUE, если файл существует
			 * @param $value
			 * @param string $size
			 * @return bool
			 */
			public function have_image( $value, $size = 'thumbnail' ){
				$key = json_encode( $size );
				if( array_key_exists( $key, $this->has_image ) ){
					return $this->has_image[ $key ];
				}
				$img_url = $this->get_src( $value, $size );
				if( $img_url === false ) return false;
				$img_path = path::url_to_path( $img_url );
				$this->has_image[ $key ] = file_exists( $img_path );
				return $this->has_image[ $key ];
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-image.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-image.css' );
				///
				$attr_width = $this->preview_width;
				$attr_height = $this->preview_height;
				$preview = false;
				$image_small = true;
				///
				if( $this->have_image( $value ) ){
					$preview = wp_get_attachment_image_src( $value, [ $attr_width, $attr_height ] );
					$image_small = !( $attr_width <= $preview[1] || $attr_height <= $preview[2] );
				}
				///
				ob_start();
				?>
				<div class="hiweb-field-image" id="<?= $this->id() ?>" data-has-image="<?= $this->have_image( $value ) ? '1' : '0' ?>" data-image-small="<?= $image_small ? '1' : '0' ?>">
					<input type="hidden" <?= $this->admin_get_input_attributes_html( $attributes ) ?> value="<?= ( $this->has_image ? $value : '' ) ?>"/>
					<a href="#" class="image-select" title="<?= __( 'Select/Deselect image...' ) ?>" data-click="<?= ( $this->have_image( $value ) ? 'deselect' : 'select' ) ?>" style="width: <?= $attr_width ?>px; height: <?= $attr_height ?>px;">
						<div class="thumbnail" style="<?= $this->have_image( $value ) ? 'background-image:url(' . $preview[0] . ')' : '' ?>"></div>
						<div class="overlay"></div>
						<i class="dashicons dashicons-format-image" data-icon="select"></i>
						<i class="dashicons dashicons-dismiss" data-icon="deselect"></i>
					</a>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}