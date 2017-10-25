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


		use hiweb\console;
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
				$this->admin_input_set_attributes( 'name', $this->id );
			}


			/**
			 * @param $key
			 * @param null $value
			 * @return $this|null
			 */
			protected function set_property( $key, $value = null ){
				if( is_null( $value ) ){
					return property_exists( $this, $key ) ? $this->{$key} : null;
				} else {
					if( property_exists( $this, $key ) ) $this->{$key} = $value; else {
						console::debug_warn( 'Попытка установить несуществующее свойтсво [' . $key . ']', $value );
					}
					return $this;
				}
			}


			/**
			 * @param bool $use_last_location - set TRUE, if you wanna use previous location
			 * @return location
			 */
			public function location( $use_last_location = false ){
				if( $use_last_location ){
					if( locations::$last_field_location instanceof location ) $this->location->set_options( locations::$last_field_location->get_options() ); else console::debug_error( 'Попытка использовать последнюю локацию для поля, но ее нет', [ $this->get_type(), $this->id() ] );
				}
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