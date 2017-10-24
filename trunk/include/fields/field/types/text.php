<?php

	namespace {


		if( !function_exists( 'add_field_text' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\text
			 */
			function add_field_text( $id ){
				$new_field = new hiweb\fields\types\text( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class text extends field{

			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_URL_ASSETS . '/css/field-text.css' );
				return parent::admin_get_input( $value, $attributes );
			}

		}
	}