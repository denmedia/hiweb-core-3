<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 02.10.2017
	 * Time: 15:24
	 */

	namespace hiweb;


	use hiweb\fields\field;
	use hiweb\fields\separator;


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
		 * @param        $fieldId
		 * @param string $type
		 * @param null   $fieldName
		 * @return field
		 */
		static function register_field( $fieldId, $type = 'text', $fieldName = null ){
			$field = new field( $fieldId, $type );
			$field->label( $fieldName );
			self::$fields[ $field->global_id() ] = $field;
			self::$fieldId_globalId[ $fieldId ][] = $field;
			self::$globalId_fieldId[ $field->global_id() ][] = $field;
			return $field;
		}


		/**
		 * @param $haystack
		 * @return bool
		 */
		static function is_field($haystack){
			return ($haystack instanceof field) || ($haystack instanceof separator);
		}


		/**
		 * @param        $label
		 * @param string $description
		 * @return separator
		 */
		static function register_separator( $label, $description = '' ){
			$field_separator = new separator( $label, $description );
			self::$fields[ $field_separator->global_id() ] = $field_separator;
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
				return self::register_field( $field_id );
			}
			return end( self::$fieldId_globalId[ $field_id ] );
		}


	}