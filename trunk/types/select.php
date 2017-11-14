<?php

	namespace {


		if( !function_exists( 'add_field_select' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\select
			 */
			function add_field_select( $id ){
				$new_field = new hiweb\fields\types\select( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class select extends field{


			protected $options = [];


			public function options( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_DIR_CSS . '/field-select.css' );
				$options = [];
				if( is_array( $this->options ) ) $options = $this->options;
				$R = '';
				foreach( $options as $key => $val ){
					$selected = '';
					if( !is_null( $value ) && $key == $value ){
						$selected = 'selected';
					}
					$R .= '<option ' . $selected . ' value="' . htmlentities( $key, ENT_QUOTES, 'UTF-8' ) . '">' . $val . '</option>';
				}
				return '<select class="hiweb-field-select" ' . $this->admin_get_input_attributes_html($attributes) . '>' . $R . '</select>';
			}


		}
	}