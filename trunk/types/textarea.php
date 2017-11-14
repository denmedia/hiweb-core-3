<?php

	namespace {


		if( !function_exists( 'add_field_textarea' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\textarea
			 */
			function add_field_textarea( $id ){
				$new_field = new hiweb\fields\types\textarea( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class textarea extends field{

			/**
			 * @param $value
			 * @param null $arg_1
			 * @param null $arg_2
			 * @param null $arg_3
			 * @return string
			 */
			public function get_value_content( $value, $arg_1 = null, $arg_2 = null, $arg_3 = null ){
				return nl2br( $value );
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_URL_CSS . '/field-textarea.css' );
				return '<textarea class="hiweb-field-textarea" ' . $this->admin_get_input_attributes_html( $attributes ) . '>' . $value . '</textarea>';
			}

		}
	}