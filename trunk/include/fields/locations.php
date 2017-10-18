<?php

	namespace hiweb\fields;


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
		 * Return register locations by context location
		 * @param location $context_location
		 * @return location[]
		 */
		static function get_locations_by_contextLocation( location $context_location ){
			$R = [];
			foreach( $context_location->get_options() as $context_options_type => $context_options ){
				foreach( self::$locations as $location_id => $register_location ){
					foreach( $register_location->get_options() as $register_options_type => $register_options ){
						if( $context_options_type != $register_options_type ) continue;
						if( self::get_options_compare( $context_options, $register_options ) ){
							$R[ $register_location->global_id() ] = $register_location;
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
			$locations = locations::get_locations_by_contextLocation( $context_location );
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

	}
