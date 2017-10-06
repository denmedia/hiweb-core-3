<?php

	namespace hiweb\fields;


	use hiweb\fields\options\location;


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
		 * Return array of locations
		 * @param string|array $groups - allow forms: 'post_type','taxonomy','user','options'
		 * @return location[]
		 * @internal param bool $like
		 */
		static function get_byGroup( $groups = [ 'post_type' ] ){
			$R = [];
			if( !is_array( $groups ) ) $groups = [ $groups ];
			foreach( self::$rulesId as $location_id => $rule ){
				foreach( $groups as $group_name ){
					if( preg_match( '/^' . $group_name . ':.*/i', $rule ) ){
						$R[] = self::$locations[ $location_id ];
					}
				}
			}
			return $R;
		}


		/**
		 * Сопоставляет правило и заданному фильтру контекста, возвращая TRUE или FALSE.
		 * По указанию аргумента $return_result_array - возвращает составленные паттерны и результат соответствия
		 * @param       $ruleId              - строка правила
		 * @param       $group               - наименование группы
		 * @param array $filter              - массив контекста
		 * @param array $required_filter     - обязательные параметры в массиве контекста
		 * @param bool  $return_result_array - возвратить массив паттернов с рузельтатами
		 * @return string
		 */
		static function get_context_compare( $ruleId, $group, $filter = [], $required_filter = [], $return_result_array = false ){
			$R = true;
			$PATTERNS[ $ruleId ] = [];
			////
			if( !is_array( $filter ) ) $filter = [ $filter ];
			//attributes filter
			$filter_pattern = [];
			foreach( $required_filter as $key => $val ){
				if( is_int( $key ) ){
					$filter_pattern[] = '"' . $val . '":(?:{|\[).*(?:}|\])';
				} else {
					$filter_pattern[] = '"' . $key . '":\[' . trim( json_encode( $val ), '[]{}' ) . '\]';
				}
			}
			if( count( $filter_pattern ) > 0 ){
				$filter_pattern = '/(?:(?>' . implode( '),?|(?>', $filter_pattern ) . ')){' . count( $required_filter ) . '}/i';
				if( !array_key_exists( $filter_pattern, $PATTERNS[ $ruleId ] ) ){
					$match = preg_match( $filter_pattern, $ruleId );
					$PATTERNS[ $ruleId ][ $filter_pattern ] = $match;
					if( $match == 0 ) $R = false;
				}
			}
			foreach( $filter as $key => $val ){
				$filter_pattern = [ '(?:"' . $key . '":(?>' ];
				if( !is_array( $val ) && !is_bool( $val ) ) $val = [ $val ];
				$pattern_val = [];
				if( count( $val ) > 1 ){
					foreach( $val as $key2 => $val2 ){
						$pattern_val[] = json_encode( $val2, JSON_UNESCAPED_UNICODE );
					}
					$filter_pattern[] = '\[' . implode( '|', $pattern_val ) . '\]';
				} else {
					$filter_pattern[] = strtr( json_encode( $val, JSON_UNESCAPED_UNICODE ), [ '[' => '\[', ']' => '\]' ] );
				}
				$filter_pattern[] = ')|(?!"' . $key . '":\[?(?:"[\w-.\d]+"|\d)' . ( is_bool( $val ) ? '|' . ( $val ? 'false' : 'true' ) : '' ) . '\]?).)*';
				//(?:"slug":\["theme"\]|(?!"slug":\["\w+"\]).)
				$filter_pattern = '/^' . $group . ':(?:{|\[)' . implode( '', $filter_pattern ) . '(?:}|\])$/i';
				if( !array_key_exists( $filter_pattern, $PATTERNS[ $ruleId ] ) ){
					$match = preg_match( $filter_pattern, $ruleId );
					$PATTERNS[ $ruleId ][ $filter_pattern ] = $match;
					if( $match == 0 ) $R = false;
				}
			}
			//
			return $return_result_array ? $PATTERNS : $R;
		}


		/**
		 * Return Locations by filter
		 * USE: $locations = hiweb()->locations()->get_by( $group = 'post_type', $filter = [ 'post_type' => 'page' ], $like = true );
		 * @param string $group
		 * @param array  $filter
		 * @param array  $required_filter
		 * @return location[]
		 */
		static function get_by( $group, $filter = [], $required_filter = [] ){
			$R = [];
			foreach( self::$rulesId as $location_id => $ruleStr ){
				if( self::get_context_compare( $ruleStr, $group, $filter, $required_filter ) ){
					$R[] = self::$locations[ $location_id ];
				}
			}
			return $R;
		}


		/**
		 * Get fields by filtered locations
		 * @param       $group
		 * @param array $filter
		 * @param array $required_filter
		 * @return field[]
		 */
		static function get_fields_by( $group, $filter = [], $required_filter = [] ){
			$locations = self::get_by( $group, $filter, $required_filter );
			$R = [];
			foreach( $locations as $location ){
				if( $location->get_field() instanceof field ){
					$R[ $location->get_field()->id() ] = $location->get_field();
				} else {
					$R[ $location->get_field()->global_id() ] = $location->get_field();
				}
			}
			return $R;
		}

	}
