<?php

	namespace {


		if( !function_exists( 'add_field_file' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\file\field
			 */
			function add_field_file( $id ){
				$new_field = new hiweb\fields\types\file\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\file {


		use hiweb\path;


		class field extends \hiweb\fields\field{


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{


			private $has_file = [];


			/**
			 * Возвращает TRUE, если файл существует
			 * @return bool
			 */
			public function have_file(){
				$attachment_url = wp_get_attachment_url( $this->VALUE()->get() );
				$this->has_file[ $attachment_url ] = path::is_readable( $attachment_url );
				return $this->has_file[ $attachment_url ];
			}


			public function html(){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-file.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-file.css' );
				///
				ob_start();
				?>
				<div class="hiweb-field-file" id="<?= $this->global_id() ?>" data-has-file="<?= $this->have_file() ? '1' : '0' ?>">
					<input type="hidden" <?= $this->sanitize_attributes() ?> value="<?= ( $this->has_file ? $this->VALUE()->get() : '' ) ?>"/>
					<a href="#" class="file-select" title="<?= __( 'Select/Deselect file...' ) ?>" data-click="<?= ( $this->have_file() ? 'deselect' : 'select' ) ?>" style="width: 80px; height: 80px;">
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