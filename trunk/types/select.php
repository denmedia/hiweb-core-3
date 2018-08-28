<?php

	namespace {


		if( !function_exists( 'add_field_select' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\select\field
			 */
			function add_field_select( $id ){
				$new_field = new hiweb\fields\types\select\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\select {


		use function hiweb\js;


		class field extends \hiweb\fields\field{


			public function options( $set = null ){
				$set_sanitize = [];
				if( is_array( $set ) ) foreach( $set as $key => $val ){
					$set_sanitize[ is_int( $key ) ? $val : $key ] = $val;
				}
				return $this->set_input_property( __FUNCTION__, $set_sanitize );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public $options = [];


			public function html(){
				\hiweb\js( HIWEB_DIR_JS . '/field-select.min.js' );
				\hiweb\css( HIWEB_DIR_CSS . '/field-select.css' );
				$options = [];
				if( is_array( $this->options ) ) $options = $this->options;
				$R = '';
				foreach( $options as $key => $val ){
					$selected = '';
					if( !is_null( $this->VALUE()->get() ) && $key == $this->VALUE()->get() ){
						$selected = 'selected';
					}
					$R .= '<option ' . $selected . ' value="' . htmlentities( $key, ENT_QUOTES, 'UTF-8' ) . '">' . $val . '</option>';
				}
				return '<div class="hiweb-field-select"><select class="ui fluid search selection dropdown" ' . $this->sanitize_attributes() . '>' . $R . '</select></div>';
			}


		}
	}