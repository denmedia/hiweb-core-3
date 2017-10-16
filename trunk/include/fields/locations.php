<?php

	namespace hiweb\fields;


	use hiweb\console;
	use hiweb\fields\functions;
	use hiweb\fields\locations\location;


	class locations{

		/** @var location[] */
		static $locations = [];
		/** @var array */
		static $rules = [];
		/** @var array */
		static $rulesId = [];


		/**
		 * @param null|field $field
		 * @return location
		 */
		static function register( $field = null ){
			$location = new location( $field );
			self::$locations[ $location->global_id() ] = $location;
			self::$rules[ $location->global_id() ] = [];
			self::$rulesId[ $location->global_id() ] = '';
			return $location;
		}


		/**
		 * @param $globalId
		 * @return bool|location
		 */
		static function get( $globalId ){
			if( !isset( self::$locations[ $globalId ] ) ) return false;
			return self::$locations[ $globalId ];
		}


		/**
		 * Return register locations by context location
		 * @param location $context_location
		 * @return location[]
		 */
		static function get_locations_by_context( location $context_location ){
			$R = [];
			foreach( $context_location->get_options() as $context_options_type => $context_options ){
				foreach( self::$locations as $location_id => $register_location ){
					foreach( $register_location->get_options() as $register_options_type => $register_options ){
						if( $context_options_type != $register_options_type ) continue;
						if( self::get_options_compare( $context_options, $register_options ) ){
							$R[ $register_location->id() ] = $register_location;
							continue 2;
						}
					}
				}
			}
			return $R;
		}


		/**
		 * Get fields by context location
		 * @param location $context_location
		 * @return array|field[]
		 */
		static function get_fields_by_contextLocation( location $context_location ){
			$R = [];
			$locations = locations::get_locations_by_context( $context_location );
			foreach( $locations as $location_id => $location ){
				if( $location->get_field() instanceof field ) $R[ $location_id ] = $location->get_field();
			}
			return $R;
		}


		/**
		 * @param null $contextObject
		 * @return array|field[]
		 */
		static function get_fields_by_contextObject( $contextObject = null ){
			$location = functions\get_newLocation_from_contextObject( $contextObject );
			return self::get_fields_by_contextLocation( $location );
		}


		/**
		 * @param array $context_options
		 * @param array $register_options
		 * @return bool
		 */
		static private function get_options_compare( $context_options = [], $register_options = [] ){
			$intersect_keys = array_keys( array_intersect_key( $context_options, $register_options ) );
			foreach( $intersect_keys as $key ){
				$intersect_values = count( array_intersect( (array)$context_options[ $key ], (array)$register_options[ $key ] ) );
				if( $intersect_values == 0 ) return false;
			}
			return true;
		}


		/**
		 * @param null|\WP_Post|\WP_User|\WP_Term|string $object
		 * @return location
		 */
		static function get_location_from_context( $object = null ){
//			$object_id = null;
//			$context_location = new location();
//			///
//			if( is_null( $object ) || empty( $object ) ){
//				if( !function_exists( 'get_queried_object' ) || !did_action( 'wp' )  ){
//					console::debug_error( 'Попытка получить контекст, но функция [get_queried_object] не существует', $object );
//				} else {
//					if( is_null( $object ) || empty( $object ) ){
//						return self::get_location_from_context( get_queried_object() ); //todo: проверить работоспособность...
//					} else if( is_numeric( $object ) ){
//						$object = get_queried_object();
//						$object_id = get_queried_object_id();
//					} else {
//						console::debug_warn( 'Что-то еще...1' );
//					}
//				}
//			}
//			///
//			if( $object instanceof \WP_Post ){
//				$context_location->options = functions\get_locationOptions_from_contextObject( $object );
//			} elseif( $object instanceof \WP_Term ) {
//				//TODO
//			} elseif( $object instanceof \WP_User ) {
//				//TODO
//			} elseif( is_string( $object ) ) {
//				//TODO
//			} else {
//				console::debug_warn( 'Что-то еще...2', $object );
//			}
//			return $context_location;
		}

	}
