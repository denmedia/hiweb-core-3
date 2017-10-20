<?php

	namespace {


		use hiweb\fields\rows;


		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool
		 */
		function have_rows( $fieldId, $contextObject = null ){
			return rows::have_rows( $fieldId, $contextObject );
		}

		/**
		 * @return array|mixed|null
		 */
		function the_row(){
			return rows::the_row();
		}

		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool|mixed
		 */
		function reset_rows( $fieldId, $contextObject = null ){
			return rows::reset_rows( $fieldId, $contextObject );
		}

		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function get_sub_field( $subField ){
			return rows::get_sub_field( $subField );
		}
	}

	namespace hiweb\fields {


		use hiweb\console;
		use hiweb\fields;


		class rows{

			static $rows_limit = 9999;
			static private $current_field_context_id;
			/** @var fields\field\context[] */
			static private $context_queue = [];


			/**
			 * @param field $field
			 * @param null  $contextObject
			 * @return string
			 */
			static private function get_field_context_id( field $field, $contextObject = null ){
				return $field->id() . '-' . $field->context( $contextObject )->id();
			}


			/**
			 * @param      $fieldId
			 * @param null $contextObject
			 * @return bool|mixed
			 */
			static function reset_rows( $fieldId, $contextObject = null ){
				///Check field
				if( !fields::is_register( $fieldId ) ) return false;
				///Get field-context-id
				$field = fields::get( $fieldId );
				return $field->context( $contextObject )->reset_rows();
			}


			/**
			 * @param      $fieldId
			 * @param null $contextObject
			 * @return bool
			 */
			static function have_rows( $fieldId, $contextObject = null ){
				//Limit check
				if( self::$rows_limit > 0 ){
					self::$rows_limit --;
				} else {
					$current_field_id = self::get_current_context() instanceof field ? self::get_current_context()->id() : 'нет поля';
					console::debug_warn( 'Для глобального перебора строк массива have_rows был достигнут лимит!', $current_field_id );
					return false;
				}
				///Check field
				if( !fields::is_register( $fieldId ) ) return false;
				///Get field-context-id
				$field = fields::get( $fieldId );
				$context = $field->context( $contextObject );
				$field_context_id = self::get_field_context_id( $field, $contextObject );
				///
				if( $field_context_id != self::$current_field_context_id ){
					self::$context_queue[ $field->global_id() ] = $context;
					self::$current_field_context_id = $field_context_id;
				}
				$have_rows = $context->have_rows();
				if( $have_rows ){
					return true;
				} else {
					unset( self::$context_queue[ $field->global_id() ] );
					if( is_array( self::$context_queue ) && count( self::$context_queue ) > 0 ){
						$keys = array_keys( self::$context_queue );
						self::$current_field_context_id = end( $keys );
					} else {
						self::$current_field_context_id = null;
					}
				}
				return false;
			}


			/**
			 * @return null|array|mixed
			 */
			static function the_row(){
				$context = self::get_current_context();
				if( $context !== false ){
					console::info( '$context->the_row(' . $context->get_field()->id() . ');' );
					return $context->the_row();
				}
				return null;
			}


			/**
			 * @return bool|fields\field\context
			 */
			static function get_current_context(){
				$lastField = end( self::$context_queue );
				if( !$lastField instanceof fields\field\context ) return false;
				///
				return $lastField;
			}


			/**
			 * @param $subFieldId
			 * @return mixed|null
			 */
			static function get_sub_field( $subFieldId ){
				$context_queue = array_reverse( self::$context_queue );
				/**
				 * @var string               $field_context_id
				 * @var fields\field\context $context
				 */
				foreach( $context_queue as $field_id => $context ){
					if( !$context instanceof fields\field\context ) continue;
					if( !is_null( $context->get_sub_field( $subFieldId ) ) ) return $context->get_sub_field( $subFieldId );
				}
				return null;
			}

		}
	}