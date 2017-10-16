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

			/**
			 * @param string $test
			 * @return string
			 */
			public function extend_function($test = 'My TEST'){
				return 'TEST';
			}

		}
	}