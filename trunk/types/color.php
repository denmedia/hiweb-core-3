<?php

	namespace {


		if( !function_exists( 'add_field_color' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\color\field
			 */
			function add_field_color( $id ){
				$new_field = new hiweb\fields\types\color\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\color {


		class field extends \hiweb\fields\field{

			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\js( HIWEB_DIR_JS . '/field-color.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/tinyColorPicker/jqColorPicker.min.js' );
				///
				$this->attributes['type'] = 'text';
				$this->attributes['data-type-color'] = ' ';
				$this->attributes['value'] = $this->VALUE()->get_sanitized();
				///
				ob_start();
				?>
				<div class="ui field">
					<input <?= $this->sanitize_attributes() ?>>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}