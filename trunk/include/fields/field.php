<?php

	namespace {


		if( !function_exists( 'add_field' ) ){
			/**
			 * @param $fieldId
			 * @return \hiweb\fields\field
			 */
			function add_field( $fieldId ){
				$field = new hiweb\fields\field( $fieldId );
				hiweb\fields::register_field( $field );
				return $field;
			}
		}
	}

	namespace hiweb\fields {


		use hiweb\fields\field\admin;
		use hiweb\fields\field\properties;
		use hiweb\fields\field\value;
		use hiweb\fields\locations\location;
		use hiweb\fields\locations\locations;
		use hiweb\string;


		class field{

			use admin;
			use properties;
			use value;

			/** @var location */
			private $location;


			public function __construct( $id = null ){
				$this->id = trim( $id ) == '' ? string::rand() : $id;
				$this->location = locations::register( $this );
				$this->admin_input_attributes_set( 'name', $this->id );
			}


			/**
			 * @return location
			 */
			public function location(){
				return $this->location;
			}


			/**
			 * @return string
			 */
			public function get_type(){
				if( __CLASS__ == 'filed' ){
					return '';
				} else {
					$namespace_path = explode( '\\', get_class( $this ) );
					return end( $namespace_path );
				}
			}


		}
	}