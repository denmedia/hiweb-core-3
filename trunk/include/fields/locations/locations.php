<?php

	namespace hiweb\fields\locations;


	use hiweb\fields\field;


	class locations{

		/** @var location[] */
		static $locations = [];
		/** @var array */
		static $rules = [];
		/** @var array */
		static $rulesId = [];


		/**
		 * @return location
		 */
		static function register(){
			$location = new location();
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
							$R[ $location_id ] = $register_location;
							continue 2;
						}
					}
				}
			}
			return $R;
		}


		/**
		 * @param array $context_options
		 * @param array $register_options
		 * @return bool
		 */
		static private function get_options_compare( $context_options = [], $register_options = [] ){
			$intersect_keys = array_keys( array_intersect_key( $context_options, $register_options ) );
			foreach( $intersect_keys as $key ){
				$intersect_values = count(array_intersect( (array)$context_options[ $key ], (array)$register_options[ $key ] ));
				if( $intersect_values == 0 ) return false;
			}
			return true;
		}

	}
