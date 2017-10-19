<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	class context{

		private $field;
		/** @var null|\WP_Term|\WP_User|string */
		private $contextObject;
		/** @var array */
		private $contextOptions = [];

		private $rows = [];
		private $current_row;
		private $current_row_index = - 1;
		private $rows_limit = 9999;


		public function __construct( field $field, $contextObject = null ){
			$this->field = $field;
			$this->contextObject = $contextObject;
			$this->contextOptions = \hiweb\fields\functions\get_locationOptions_from_contextObject( $contextObject );
		}


		/**
		 * @return mixed
		 */
		public function value(){
			$value = null;
			if( key_exists( 'post_types', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['post_types'] ) ){
				$post_id = $this->contextOptions['post_types']['ID'];
				$value = metadata_exists( 'post', $post_id, $this->field->id() ) ? get_post_meta( $post_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'taxonomies', $this->contextOptions ) && key_exists( 'term_id', $this->contextOptions['taxonomies'] ) ) {
				$term_id = $this->contextOptions['taxonomies']['term_id'];
				$value = metadata_exists( 'term', $term_id, $this->field->id() ) ? get_term_meta( $term_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'users', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['users'] ) ) {
				$user_id = $this->contextOptions['users']['ID'];
				$value = metadata_exists( 'user', $user_id, $this->field->id() ) ? get_user_meta( $user_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'admin_menus', $this->contextOptions ) && key_exists( 'slug', $this->contextOptions['admin_menus'] ) ) {
				$value = get_option( \hiweb\fields\functions\get_options_field_id( $this->contextOptions['admin_menus']['slug'], $this->field->id() ), $this->field->value_default() );
			}
			return $this->field->value_sanitize( $value );
		}


		/**
		 * @return field
		 */
		public function get_field(){
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
			return md5(json_encode($this->get_contextOptions()));
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
		 * Return TRUE, if rows is exists
		 * @return bool
		 */
		public function have_rows(){
			if( !is_array( $this->value() ) || count( $this->value() ) == 0 ) return false;
			if( $this->current_row_index == - 1 ){
				$this->reset_rows();
				return true;
			} elseif( ( $this->current_row_index < count( $this->value() ) - 1 ) && $this->current_row_index < $this->rows_limit ) {
				return true;
			} elseif( $this->current_row_index >= $this->rows_limit ) {
				console::debug_warn( 'Превышен лимит строк массива для функции have_rows в поле [' . $this->field->id() . ']' );
				return false;
			} else {
				console::debug_info( 'Перебор строк массива для функции have_rows в поле [' . $this->field->id() . '] окончен' );
				$this->reset_rows();
				return false;
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
		 * @return mixed
		 */
		public function get_sub_field( $subFieldId ){
			$std = (object)$this->current_row;
			return $std->{$subFieldId};
		}


		/**
		 * @param $subFieldId
		 * @return bool
		 */
		public function has_sub_field( $subFieldId ){
			return is_array( $this->current_row ) && array_key_exists( $subFieldId, $this->current_row );
		}


		/**
		 * Get input html
		 * @return string
		 */
		public function get_input(){
			return $this->field->admin_get_input( $this->value() );
		}


		/**
		 * Echo input
		 */
		public function the_input(){
			echo $this->get_input();
		}


	}