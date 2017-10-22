<?php

	namespace {


		if( !function_exists( 'add_field_file' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\file
			 */
			function add_field_file( $id ){
				$new_field = new hiweb\fields\types\file( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;
		use hiweb\path;


		class file extends field{

			private $has_file = [];


			/**
			 * Возвращает TRUE, если файл существует
			 * @param $value - attachment_id
			 * @return bool
			 */
			public function have_file( $value ){
				$attachment_url = wp_get_attachment_url( $value );
				$this->has_file[ $attachment_url ] = path::is_readable( $attachment_url );
				return $this->has_file[ $attachment_url ];
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-file.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-file.css' );
				///
				ob_start();
				?>
				<div class="hiweb-field-file" id="<?= $this->id() ?>" data-has-file="<?= $this->have_file( $value ) ? '1' : '0' ?>">
					<input type="hidden" <?= $this->admin_get_input_attributes_html( $attributes ) ?> value="<?= ( $this->has_file ? $value : '' ) ?>"/>
					<a href="#" class="file-select" title="<?= __( 'Select/Deselect file...' ) ?>" data-click="<?= ( $this->have_file( $value ) ? 'deselect' : 'select' ) ?>" style="width: 80px; height: 80px;">
						<div class="overlay"></div>
						<i class="dashicons dashicons-paperclip" data-icon="select"></i>
						<i class="dashicons dashicons-format-aside" data-icon="deselect"></i>
					</a>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}