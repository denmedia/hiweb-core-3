<?php

	namespace {


		if( !function_exists( 'add_field_repeat' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\repeat
			 */
			function add_field_repeat( $id ){
				$new_field = new hiweb\fields\types\repeat( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class repeat extends field{

			public $cols = [];


			public function add_col( $colId, $type = 'text' ){
				$this->cols[ $colId ] = new field( $this->id() . '-col-' . $colId );
			}

		}
	}