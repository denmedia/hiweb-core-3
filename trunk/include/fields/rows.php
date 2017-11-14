<?php

	namespace {


		use hiweb\console;
		use hiweb\fields\rows;


		if( !function_exists( 'have_rows' ) ){
			/**
			 * @param      $fieldId
			 * @param null $contextObject
			 * @return bool
			 */
			function have_rows( $fieldId, $contextObject = null ){
				return rows::have_rows( $fieldId, $contextObject );
			}
		} else {
			console::debug_warn( 'Function [have_rows] is exists...' );
		}

		if( !function_exists( 'the_row' ) ){
			/**
			 * @return array|mixed|null
			 */
			function the_row(){
				return rows::the_row();
			}
		} else {
			console::debug_warn( 'Function [the_row] is exists...' );
		}

		if( !function_exists( 'reset_rows' ) ){
			/**
			 * @param      $fieldId
			 * @param null $contextObject
			 * @return bool|mixed
			 */
			function reset_rows( $fieldId, $contextObject = null ){
				return rows::reset_rows( $fieldId, $contextObject );
			}
		} else {
			console::debug_warn( 'Function [reset_rows] is exists...' );
		}

		if( !function_exists( 'get_sub_field' ) ){
			/**
			 * @param $subField
			 * @return mixed|null
			 */
			function get_sub_field( $subField ){
				return rows::get_sub_field( $subField );
			}
		} else {
			console::debug_warn( 'Function [get_sub_field] is exists...' );
		}

		if( !function_exists( 'get_sub_field_content' ) ){
			/**
			 * @param $subField
			 * @param null $arg_1
			 * @param null $arg_2
			 * @param null $arg_3
			 * @return mixed|null
			 */
			function get_sub_field_content( $subField, $arg_1 = null, $arg_2 = null, $arg_3 = null ){
				return rows::get_sub_field_content( $subField, func_get_arg( 1 ), func_get_arg( 2 ), func_get_arg( 3 ) );
			}
		} else {
			console::debug_warn( 'Function [get_sub_field_content] is exists...' );
		}

		if( !function_exists( 'each_rows' ) ){

			function each_rows( $fieldId, $contextObject = null, $callable ){
				$R = [];
				if( rows::have_rows( $fieldId, $contextObject ) ){
					while( rows::have_rows( $fieldId, $contextObject ) ){
						$row = rows::the_row();
						$R[] = call_user_func( $callable, $row );
					}
				}
				return $R;
			}
		} else {
			console::debug_warn( 'Function [each_rows] is exists...' );
		}

		if( !function_exists( 'get_rows' ) ){
			/**
			 * @param $fieldId
			 * @param null $contextObject
			 * @return array
			 */
			function get_rows( $fieldId, $contextObject = null ){
				if( !rows::have_rows( $fieldId, $contextObject ) ) return []; else {
					return rows::get_current_context()->get_rows();
				}
			}
		} else {
			console::debug_warn( 'Function [get_rows] is exists...' );
		}

		if( !function_exists( 'get_filter_rows_by_func' ) ){
			/**
			 * @param $fieldId
			 * @param null $contextObject
			 * @param $callable
			 * @param null $params
			 * @return array|null
			 */
			function get_filter_rows_by_func( $fieldId, $contextObject = null, $callable, $params = null ){
				if( !rows::have_rows( $fieldId, $contextObject ) ) return []; else {
					return rows::get_current_context()->get_filter_rows_by_func( $callable, $params );
				}
			}
		} else {
			console::debug_warn( 'Function [get_filter_rows_by_func] is exists...' );
		}

		if( !function_exists( 'the_row_is_first' ) ){
			/**
			 * @return bool
			 */
			function the_row_is_first(){
				return rows::the_row_is_first();
			}
		} else {
			console::debug_warn( 'Function [the_row_is_first] is exists...' );
		}

		if( !function_exists( 'the_row_is_last' ) ){
			/**
			 * @return bool
			 */
			function the_row_is_last(){
				return rows::the_row_is_last();
			}
		} else {
			console::debug_warn( 'Function [the_row_is_last] is exists...' );
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
			 * @param null $contextObject
			 * @return string
			 */
			static private function get_field_context_id( field $field, $contextObject = null ){
				return $field->global_id() . '-' . $field->context( $contextObject )->id();
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
					return $context->the_row();
				}
				return null;
			}


			/**
			 * @return bool
			 */
			static function the_row_is_first(){
				return self::get_current_context()->get_row_index() == 0;
			}


			/**
			 * @return bool
			 */
			static function the_row_is_last(){
				return self::get_current_context()->get_row_index() == count( self::get_current_context()->value() );
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
			 * @return field\context[]
			 */
			static function get_context_queue(){
				return self::$context_queue;
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
					if( !is_null( $context->get_sub_field( $subFieldId ) ) ) return $context->get_sub_field( $subFieldId );
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
					$sub_field_content = $context->get_sub_field_content( $subFieldId, $arg_1, $arg_2, $arg_3 );
					if( !is_null( $sub_field_content ) ) return $sub_field_content;
				}
				return null;
			}

		}
	}