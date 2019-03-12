<?php

	//	namespace {
	//
	//
	//		if( !function_exists( 'add_field' ) ){
	//			/**
	//			 * @param $fieldId
	//			 * @return \hiweb\fields\field
	//			 */
	//			function add_field( $fieldId ){
	//				$field = new hiweb\fields\field( $fieldId );
	//				hiweb\fields::register_field( $field );
	//				return $field;
	//			}
	//		}
	//	}

	namespace hiweb\fields {


		use hiweb\console;
		use hiweb\fields\field\context;
		use hiweb\fields\field\form;
		use hiweb\fields\locations\location;
		use hiweb\fields\locations\locations;
		use hiweb\hidden_methods;
		use hiweb\strings;


		class field{

			use hidden_methods;

			/** @var strings */
			protected $id = '';
			/** @var strings */
			protected $global_id = '';
			/** @var strings */
			protected $label = '';
			/** @var strings */
			protected $description = '';

			/** @var location */
			private $location;
			/** @var input */
			protected $input;
			/** @var value */
			protected $value;
			/** @var array|context[] */
			private $contexts = [];
			/** @var form */
			private $form;

			private $template = 'default';


			public function __construct( $id = null ){
				$this->id = trim( $id ) == '' ? strings::rand() : $id;
				$this->location = locations::register( $this );
				$this->INPUT()->name( $this->id() );
			}


			public function __clone(){
				$old_global_location_id = spl_object_hash( $this->location );
				$this->location = clone $this->location;
				$new_global_location_id = spl_object_hash( $this->location );
				locations::$locations[ $new_global_location_id ] = locations::$locations[ $old_global_location_id ];
				locations::$rules[ $new_global_location_id ] = locations::$rules[ $old_global_location_id ];
				locations::$rulesId[ $new_global_location_id ] = locations::$rulesId[ $old_global_location_id ];
			}


			/**
			 * @param null $set
			 * @return field|null
			 */
			public function template( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return field|string
			 */
			public function id( $set = null ){
				if( is_null( $set ) ){
					return $this->id;
				} else {
					$this->id = $set;
					$this->INPUT()->name( $this->id() );
					return $this;
				}
			}


			/**
			 * @param null|string $set
			 * @return field|string
			 */
			public function global_id( $set = null ){
				if( is_null( $set ) ){
					return $this->global_id;
				} else {
					$this->global_id = $set;
					return $this;
				}
			}


			/**
			 * @param      $key
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


			protected function set_input_property( $key, $value = null ){
				if( is_null( $value ) ){
					return property_exists( $this, $key ) ? $this->INPUT()->{$key} : null;
				} else {
					if( property_exists( $this->INPUT(), $key ) ) $this->INPUT()->{$key} = $value; else {
						console::debug_warn( 'Попытка установить несуществующее свойтсво в input объекте [' . $key . ']', $value );
					}
					return $this;
				}
			}


			/**
			 * Set|get field label
			 * @param null|strings $label
			 * @return field|null|string
			 */
			public function label( $label = null ){
				return $this->set_property( __FUNCTION__, $label );
			}


			/**
			 * Set|get field description
			 * @param null|strings $description
			 * @return field|null|string
			 */
			public function description( $description = null ){
				return $this->set_property( __FUNCTION__, $description );
			}


			/**
			 * @param bool $use_last_location - set TRUE, if you wanna use previous location
			 * @return location
			 */
			public function LOCATION( $use_last_location = false ){
				//TODO:$use_last_location не работает для meta_box!
				if( $use_last_location ){
					if( locations::$last_field_location instanceof location ){
						$this->location->_set_options( locations::$last_field_location->_get_options() );
					} else {
						console::debug_error( 'Попытка использовать последнюю локацию для поля, но ее нет', [ $this->global_id(), $this->id() ] );
					}
				}
				return $this->location;
			}


			/**
			 * @return string
			 */
			public function get_type(){
				return get_class( $this );
			}


			/**
			 * Get input class
			 * @return string
			 */
			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			/**
			 * @return input
			 */
			final public function INPUT(){
				if( !$this->input instanceof input ){
					$className = $this->get_input_class();
					if( class_exists( $className ) ){
						$input = new $className( $this, $this->VALUE() );
						if( $input instanceof input ){
							$this->input = $input;
						} else {
							$this->input = new input( $this, $this->VALUE() );
						}
					} else {
						console::debug_error( 'Для типа [' . $this->get_type() . '] экземпляр класса input не был найден', $className );
						$this->input = new input( $this, $this->VALUE() );
					}
				}
				return $this->input;
			}


			/**
			 * Get value class
			 * @return string
			 */
			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


			/**
			 * @param null $default
			 * @return value
			 */
			final public function VALUE( $default = null ){
				if( !$this->value instanceof value ){
					$className = $this->get_value_class();
					if( class_exists( $className ) ){
						$value = new $className( $this );
						if( $value instanceof value ){
							$this->value = $value;
						} else {
							$this->value = new value( $this );
						}
					} else {
						console::debug_error( 'Для типа [' . $this->get_type() . '] экземпляр класса value не был найден', $className );
						$this->value = new value( $this );
					}
				}
				if( !is_null( $default ) ) $this->value->set( $default );
				return $this->value;
			}


			/**
			 * @param null $contextObject
			 * @return context
			 */
			public function CONTEXT( $contextObject = null ){
				$location_id = locations::get_contextId_from_contextObject( $contextObject );
				///
				if( !array_key_exists( $location_id, $this->contexts ) ){
					/** @var field $this */
					$this->contexts[ $location_id ] = new context( $this, $contextObject );
				}
				return $this->contexts[ $location_id ];
			}


			/**
			 * Set / get form properties
			 * @return form
			 */
			public function FORM(){
				if( !$this->form instanceof form ){
					$this->form = new form( $this );
				}
				return $this->form;
			}


		}
	}