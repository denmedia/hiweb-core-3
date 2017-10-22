<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 02.10.2017
	 * Time: 15:24
	 */

	namespace hiweb;


	use hiweb\fields\field;


	class fields{

		/** @var field[] */
		static $fields = [];
		/** @var array */
		static $fieldId_globalId = [];
		/** @var array */
		static $globalId_fieldId = [];


		static private function get_free_global_id( $fieldId ){
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $fieldId . '_' . $count;
				if( !isset( self::$fields[ $input_name_id ] ) ) return $input_name_id;
			}
			return false;
		}


		/**
		 * @param field $field
		 * @return false|string
		 */
		static function register_field( field $field ){
			$global_id = self::get_free_global_id( $field->id() );
			if( $global_id === false ) return false;
			//
			$field->global_id( $global_id );
			self::$fields[ $global_id ] = $field;
			self::$fieldId_globalId[ $field->id() ][] = $field;
			self::$globalId_fieldId[ $global_id ][] = $field;
			return $global_id;
		}


		/**
		 * Return TRUE, if field exists
		 * @param $fieldOrGlobalId
		 * @return bool
		 */
		static function is_register( $fieldOrGlobalId ){
			return isset( self::$fieldId_globalId[ $fieldOrGlobalId ] ) || isset( self::$globalId_fieldId[ $fieldOrGlobalId ] );
		}


		/**
		 * @param $field_id
		 * @return field
		 */
		static function get( $field_id ){
			if( !isset( self::$fieldId_globalId[ $field_id ] ) ){
				console::warn( sprintf( __( 'Field id:[%s] not found to display value by context', 'hw-core-2' ), $field_id ) );
				return new field( $field_id );
			}
			return end( self::$fieldId_globalId[ $field_id ] );
		}


	}