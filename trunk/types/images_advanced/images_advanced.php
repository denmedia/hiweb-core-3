<?php

	namespace {


		use hiweb\fields;
		use hiweb\path;


		if( !function_exists( 'add_field_images_advanced' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\images_advanced
			 */
			function add_field_images_advanced( $id ){
				$new_field = new hiweb\fields\types\images_advanced( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\arrays;
		use hiweb\fields\field;
		use hiweb\string;


		class images_advanced extends field{

			private $preview_width = 250;
			private $preview_height = 250;
			private $preview_cols = 5;

			private $cols = [];


			/**
			 * @param field $field
			 * @return col
			 */
			public function add_col_field( field $field ){
				$this->cols[ $field->id() ] = new col( $this, $field );
				return $this->cols[ $field->id() ];
			}


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
			 * @param null $set
			 * @return $this|null
			 */
			public function preview_cols( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @return array
			 */
			public function value_sanitize( $value ){
				return is_array( $value ) ? $value : [];
			}


			/**
			 * Get percent width in row
			 * @param int $items_in_row
			 * @return string
			 */
			private function get_col_width( $items_in_row = 1 ){
				$cols = intval( $this->preview_cols );
				$cols = $cols < 1 ? 10 : $cols;
				return ceil( 100 / $cols / $items_in_row ) . '%';
			}


			/**
			 * @param $value
			 * @return bool
			 */
			private function have_images( $value ){
				return !arrays::is_empty( $value );
			}


			/**
			 *
			 */
			protected function html_advanced_form(){
				include __DIR__ . '/view/advanced_form.php';
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				wp_enqueue_media();
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
				$js = include_js( HIWEB_URL_VENDORS . '/customGalleryEdit.js' );
				include_js( __DIR__ . '/assets/field-images-advanced.js', [ 'jquery-ui-sortable', $js ] );
				include_css( __DIR__ . '/assets/field-images-advanced.css' );
				////
				ob_start();
				include __DIR__ . '/view/main.php';
				////
				return ob_get_clean();
			}

		}
	}