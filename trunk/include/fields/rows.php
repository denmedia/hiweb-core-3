<?php

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
			 * @param null $contextObject
			 * @return string
			 */
			static private function get_field_context_id( field $field, $contextObject = null ){
				return $field->global_id() . '-' . $field->CONTEXT( $contextObject )->id();
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
				return $field->CONTEXT( $contextObject )->VALUE()->rows()->reset_rows();
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
				$context = $field->CONTEXT( $contextObject );
				$field_context_id = self::get_field_context_id( $field, $contextObject );
				///
				if( $field_context_id != self::$current_field_context_id ){
					self::$context_queue[ $field->global_id() ] = $context;
					self::$current_field_context_id = $field_context_id;
				}
				$have_rows = $context->VALUE()->rows()->have_rows();
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
					return $context->VALUE()->rows()->the_row();
				}
				return null;
			}


			/**
			 * @return bool
			 */
			static function the_row_is_first(){
				return self::get_current_context()->VALUE()->rows()->get_row_index() == 0;
			}


			/**
			 * @return bool
			 */
			static function the_row_is_last(){
				return self::get_current_context()->VALUE()->rows()->get_row_index() == count( self::get_current_context()->VALUE()->get_sanitized() );
			}

			/**
			 * @return int
			 */
			static function get_rows_count(){
				return count( self::get_current_context()->VALUE()->get_sanitized() );
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
			 * Return current row value
			 * @return mixed|null
			 */
			static function get_current_row(){
				$context = self::get_current_context();
				if( $context !== false ){
					return $context->VALUE()->rows()->get_row();
				}
				return null;
			}


			/**
			 * @return string
			 */
			static function get_row_layout(){
				return self::get_sub_field( '_flex_row_id' );
			}


			/**
			 * @param $subFieldId
			 * @return mixed|null
			 */
			static function get_sub_field( $subFieldId ){
				$context_queue = array_reverse( self::$context_queue );
				/**
				 * @var string $field_context_id
				 * @var fields\field\context $context
				 */
				foreach( $context_queue as $field_id => $context ){
					if( !$context instanceof fields\field\context ) continue;
					if( !is_null( $context->VALUE()->rows()->get_sub_field( $subFieldId ) ) ) return $context->VALUE()->rows()->get_sub_field( $subFieldId );
				}
				return null;
			}


			/**
			 * @param $subFieldId
			 * @param null $arg_1
			 * @param null $arg_2
			 * @param null $arg_3
			 * @return mixed|null
			 */
			static function get_sub_field_content( $subFieldId, $arg_1 = null, $arg_2 = null, $arg_3 = null ){
				$context_queue = array_reverse( self::$context_queue );
				/**
				 * @var string $field_context_id
				 * @var fields\field\context $context
				 */
				foreach( $context_queue as $field_id => $context ){
					if( !$context instanceof fields\field\context ) continue;
					$sub_field_content = $context->VALUE()->rows()->get_sub_field_content( $subFieldId, $arg_1, $arg_2, $arg_3 );
					if( !is_null( $sub_field_content ) ) return $sub_field_content;
				}
				return null;
			}

		}
	}