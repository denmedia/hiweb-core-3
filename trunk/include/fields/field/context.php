<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;
	use hiweb\fields\locations\locations;
	use hiweb\fields\forms;


	class context{

		private $field;
		/** @var null|\WP_Term|\WP_User|string */
		private $contextObject;
		/** @var array */
		private $contextOptions = [];

		private $rows = [];
		private $current_row;
		private $current_row_index = - 1;
		private $rows_limit = 999;

		private $input_attributes = [];


		public function __construct( field $field, $contextObject = null ){
			$this->field = $field;
			$this->contextObject = $contextObject;
			$this->contextOptions = locations::get_locationOptions_from_contextObject( $contextObject );
		}


		/**
		 * Return value
		 * @return mixed
		 */
		public function value(){
			$value = null;
			if( key_exists( 'post_types', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['post_types'] ) ){
				$post_id = $this->contextOptions['post_types']['ID'];
				$value = metadata_exists( 'post', $post_id, $this->field->id() ) ? get_post_meta( $post_id, $this->field->id(), true ) : $this->field->value_default();
				$value = apply_filters( 'hiweb\\fields\\field\\context\\value', $value, $this->field, $this );
			} elseif( key_exists( 'taxonomies', $this->contextOptions ) && key_exists( 'term_id', $this->contextOptions['taxonomies'] ) ) {
				$term_id = $this->contextOptions['taxonomies']['term_id'];
				$value = metadata_exists( 'term', $term_id, $this->field->id() ) ? get_term_meta( $term_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'users', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['users'] ) ) {
				$user_id = $this->contextOptions['users']['ID'];
				$value = metadata_exists( 'user', $user_id, $this->field->id() ) ? get_user_meta( $user_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'admin_menus', $this->contextOptions ) && key_exists( 'menu_slug', $this->contextOptions['admin_menus'] ) ) {
				$value = get_option( forms::get_field_input_option_name( $this->field ), $this->field->value_default() );
			} else {
				console::debug_error( 'Попытка получения значения из контекста, но опции не подходят ни под одину из опций', $this->get_contextOptions() );
			}
			return $this->field->get_value_sanitize( $value );
		}


		/**
		 * Return content value
		 * @param null $arg_1
		 * @param null $arg_2
		 * @param null $arg_3
		 * @return mixed
		 */
		public function content( $arg_1 = null, $arg_2 = null, $arg_3 = null ){
			return $this->field->get_value_content( $this->value(), $arg_1, $arg_2, $arg_3 );
		}


		/**
		 * @return field
		 */
		public function get_field_object(){
			return $this->field;
		}


		/**
		 * @return null|string|\WP_Term|\WP_User
		 */
		public function get_contextObject(){
			return $this->contextObject;
		}


		/**
		 * @return array
		 */
		public function get_contextOptions(){
			return $this->contextOptions;
		}


		/**
		 * @return string
		 */
		public function id(){
			return md5( json_encode( $this->get_contextOptions() ) );
		}


		///ROWS


		/**
		 * @return mixed
		 */
		public function reset_rows(){
			$value = $this->value();
			if( !is_array( $value ) ) return false;
			$this->rows = $this->value();
			$this->current_row_index = - 1;
			reset( $this->rows );
			return true;
		}


		/**
		 * Return TRUE, if row in process
		 * @return bool
		 */
		public function is_row_process(){
			return is_array( $this->value() ) && ( $this->current_row_index < count( $this->value() ) - 1 ) && $this->current_row_index < $this->rows_limit;
		}


		/**
		 * Return TRUE, if rows is exists
		 * @return bool
		 */
		public function have_rows(){
			if( !is_array( $this->value() ) || count( $this->value() ) == 0 ) return false;
			if( $this->current_row_index == - 1 ){
				$this->reset_rows();
				return true;
			} elseif( $this->is_row_process() ) {
				return true;
			} elseif( $this->current_row_index >= $this->rows_limit ) {
				console::debug_warn( 'Превышен лимит строк массива для функции have_rows в поле [' . $this->field->id() . ']' );
				return false;
			} else {
				//console::debug_info( 'Перебор строк массива для функции have_rows в поле [' . $this->field->id() . '] окончен' );
				$this->reset_rows();
				return false;
			}
		}


		/**
		 * Get all current rows
		 * @return array
		 */
		public function get_rows(){
			return $this->rows;
		}


		/**
		 * @param $callable
		 * @param null $params
		 * @return array|null
		 */
		public function get_filter_rows_by_func( $callable, $params = null ){
			if( is_callable( $callable ) ){
				return call_user_func( $callable, @$this->rows, $params );
			} else {
				return null;
			}
		}


		/**
		 * @return mixed
		 */
		public function the_row(){
			$this->current_row = array_shift( $this->rows );
			$this->current_row_index ++;
			return $this->current_row;
		}


		/**
		 * @return mixed
		 */
		public function get_row(){
			return $this->current_row;
		}


		/**
		 * @return int
		 */
		public function get_row_index(){
			return $this->current_row_index;
		}


		/**
		 * @param $subFieldId
		 * @return null|mixed
		 */
		public function get_sub_field( $subFieldId ){
			return array_key_exists( $subFieldId, $this->current_row ) ? $this->current_row[ $subFieldId ] : null;
		}


		/**
		 * @param $subFieldId
		 * @param null $arg_1
		 * @param null $arg_2
		 * @param null $arg_3
		 * @return mixed|null
		 * @internal param null $addition_args
		 */
		public function get_sub_field_content( $subFieldId, $arg_1 = null, $arg_2 = null, $arg_3 = null ){
			if( array_key_exists( $subFieldId, $this->current_row ) ){
				return $this->field->get_value_content( $this->current_row[ $subFieldId ], $arg_1, $arg_2, $arg_3, [ $subFieldId, $this->current_row_index ] );
			}
			return null;
		}


		/**
		 * @param $subFieldId
		 * @return bool
		 */
		public function has_sub_field( $subFieldId ){
			return is_array( $this->current_row ) && array_key_exists( $subFieldId, $this->current_row );
		}


		/**
		 * Get input html by context value
		 * @param array $attributes
		 * @return string
		 */
		public function get_input( $attributes = [] ){
			if( is_array( $attributes ) && count( $attributes ) ) $this->input_attributes = array_merge( $this->input_attributes, $attributes );
			return $this->field->admin_get_input( $this->value(), $this->input_attributes );
		}


		/**
		 * Get fieldset html by context value
		 * @param array $attributes
		 * @return string
		 */
		public function get_fieldset( $attributes = [] ){
			if( is_array( $attributes ) && count( $attributes ) ) $this->input_attributes = array_merge( $this->input_attributes, $attributes );
			return $this->field->admin_get_fieldset( $this->value(), $this->input_attributes );
		}


	}