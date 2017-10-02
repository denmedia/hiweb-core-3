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
		//		/** @var hw_field_frontend[] */
		//		static $fields_byContext = [];
		/** @var array */
		static $fieldId_globalId = [];
		/** @var array */
		static $globalId_fieldId = [];


		/**
		 * Return array of all existing fields
		 * @return field[]
		 */
		static function fields(){
			return self::$fields;
		}


		/**
		 * @param        $fieldId
		 * @param string $type
		 * @param null   $fieldName
		 * @return field
		 */
		static function register( $fieldId, $type = 'text', $fieldName = null ){
			$global_id = string::rand();
			$field = new field( $fieldId, $global_id, $type );
			$field->label( $fieldName );
			self::$fields[ $global_id ] = $field;
			self::$fieldId_globalId[ $fieldId ][] = $field;
			self::$globalId_fieldId[ $global_id ][] = $field;
			return $field;
		}


		/**
		 * @param        $label
		 * @param string $description
		 * @return field\separator
		 */
		static function register_separator( $label, $description = '' ){
			$global_id = string::rand();
			$field_separator = new field\separator( $label, $description, $global_id );
			self::$fields[ $global_id ] = $field_separator;
			return $field_separator;
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
				return self::register( $field_id );
			}
			return end( self::$fieldId_globalId[ $field_id ] );
		}


	}