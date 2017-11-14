<?php

	namespace {


		if( !function_exists( 'add_field_color' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\color
			 */
			function add_field_color( $id ){
				$new_field = new hiweb\fields\types\color( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class color extends field{

			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\js( HIWEB_DIR_JS . '/field-color.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/tinyColorPicker/jqColorPicker.min.js' );
				///
				$this->admin_input_set_attributes( 'type', 'text' );
				$this->admin_input_set_attributes( 'data-type-color', ' ' );
				$this->admin_input_set_attributes( 'value', $value );
				///
				ob_start();
				?>
				<input <?= $this->admin_get_input_attributes_html($attributes) ?>>
				<?php
				return ob_get_clean();
			}

		}
	}