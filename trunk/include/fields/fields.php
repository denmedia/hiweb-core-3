<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 02.10.2017
	 * Time: 15:24
	 */

	namespace hiweb;


	use hiweb\fields\field;
	use hiweb\fields\input;
	use hiweb\fields\locations\locations;


	class fields{

		/** @var field[] */
		static $fields = [];
		/** @var array */
		static $fields_by_id = [];
		/** @var array */
		static $field_by_globalId = [];
		/** @var input[] */
		static $inputs = [];


		static private function get_free_global_id( $fieldId, array $haystack ){
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $fieldId . '_' . $count;
				if( !isset( $haystack[ $input_name_id ] ) )
					return $input_name_id;
			}
			return false;
		}


		/**
		 * @param field $field
		 * @return false|strings
		 */
		static function register_field( field $field ){
			$global_id = self::get_free_global_id( $field->id(), self::$fields );
			if( $global_id === false )
				return false;
			//
			$field->global_id( $global_id );
			self::$fields[ $global_id ] = $field;
			self::$fields_by_id[ $field->id() ][] = $field;
			self::$field_by_globalId[ $global_id ][] = $field;
			return $global_id;
		}


		/**
		 * @param field|string $fieldOrGlobalId
		 * @param $new_id
		 * @return bool|field
		 */
		static function clone_field( $fieldOrGlobalId, $new_id ){
			if( $fieldOrGlobalId instanceof field ){
				$global_id = $fieldOrGlobalId->global_id();
				$field = $fieldOrGlobalId;
			} elseif( self::is_register( $fieldOrGlobalId ) ) {
				$global_id = $fieldOrGlobalId;
				$field = self::is_register( $fieldOrGlobalId, true );
				if(!$field instanceof field) $field = new field();
			} else {
				$field = new field();
			}
			$field = clone $field;
			$field->id($new_id);
			$global_id = self::get_free_global_id( $field->id(), self::$fields );
			$field->global_id($global_id);
			return $field;
		}


		/**
		 * @param input $input
		 * @return bool|strings
		 */
		static function register_input( input $input ){
			$global_id = self::get_free_global_id( $input->get_parent_field()->id(), self::$inputs );
			if( $global_id === false )
				return false;
			self::$inputs[ $global_id ] = $input;
			return $global_id;
		}


		/**
		 * Return TRUE, if field exists
		 * @param      $fieldOrGlobalId
		 * @param bool $return_field_if_exists
		 * @return bool
		 */
		static function is_register( $fieldOrGlobalId, $return_field_if_exists = false ){
			if( $return_field_if_exists ){
				if( isset( self::$fields_by_id[ $fieldOrGlobalId ] ) && is_array( self::$fields_by_id[ $fieldOrGlobalId ] ) ){
					return reset( self::$fields_by_id[ $fieldOrGlobalId ] );
				} elseif( isset( self::$field_by_globalId[ $fieldOrGlobalId ] ) && is_array( self::$field_by_globalId[ $fieldOrGlobalId ] ) ) {
					return reset( self::$field_by_globalId[ $fieldOrGlobalId ] );
				}
				return false;
			}
			return isset( self::$fields_by_id[ $fieldOrGlobalId ] ) || isset( self::$field_by_globalId[ $fieldOrGlobalId ] );
		}


		/**
		 * @param $field_id
		 * @return field
		 */
		static function get( $field_id ){
			if( !isset( self::$fields_by_id[ $field_id ] ) ){
				console::warn( sprintf( __( 'Field id:[%s] not found to display value by context', 'hw-core-3' ), $field_id ) );
				return new field( $field_id );
			}
			return end( self::$fields_by_id[ $field_id ] );
		}


		/**
		 * @param         $field_id
		 * @param strings $contextObject
		 * @return field
		 */
		static function get_by_context( $field_id, $contextObject = 'options' ){
			$fields = locations::get_fields_by_contextObject( $contextObject );
			foreach( $fields as $global_id => $field ){
				if( $field->id() == $field_id )
					return $field;
			}
			return self::get( $field_id );
		}


	}