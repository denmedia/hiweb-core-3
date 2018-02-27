<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.02.2018
	 * Time: 21:29
	 */

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\value;


	class value_rows{

		/**
		 * @var value
		 */
		private $parent_value;

		private $rows = [];
		private $current_row;
		private $current_row_index = - 1;
		private $rows_limit = 999;


		/**
		 * value_rows constructor.
		 */
		public function __construct( value $parent_value ){
			$this->parent_value = $parent_value;
		}


		/**
		 * @return value
		 */
		public function get_parent_value(){
			return $this->parent_value;
		}

		///ROWS


		/**
		 * @return mixed
		 */
		public function reset_rows(){
			$value = $this->parent_value->get_sanitized();
			if( !is_array( $value ) ) return false;
			$this->rows = $this->parent_value->get_sanitized();
			$this->current_row_index = - 1;
			reset( $this->rows );
			return true;
		}


		/**
		 * Return TRUE, if row in process
		 * @return bool
		 */
		public function is_row_process(){
			return is_array( $this->parent_value->get_sanitized() ) && ( $this->current_row_index < count( $this->parent_value->get_sanitized() ) - 1 ) && $this->current_row_index < $this->rows_limit;
		}


		/**
		 * Return TRUE, if rows is exists
		 * @return bool
		 */
		public function have_rows(){
			if( !is_array( $this->parent_value->get_sanitized() ) || count( $this->parent_value->get_sanitized() ) == 0 ) return false;
			if( $this->current_row_index == - 1 ){
				$this->reset_rows();
				return true;
			} elseif( $this->is_row_process() ) {
				return true;
			} elseif( $this->current_row_index >= $this->rows_limit ) {
				console::debug_warn( 'Превышен лимит строк массива для функции have_rows в поле [' . $this->parent_value->get_parent_field()->id() . ']' );
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
		 * @param      $subFieldId
		 * @param null $arg_1
		 * @param null $arg_2
		 * @param null $arg_3
		 * @return mixed|null
		 * @internal param null $addition_args
		 */
		public function get_sub_field_content( $subFieldId, $arg_1 = null, $arg_2 = null, $arg_3 = null ){
			if( array_key_exists( $subFieldId, $this->current_row ) ){
				return ''; //TODO!
				//return $this->field->get_value_content( $this->current_row[ $subFieldId ], $arg_1, $arg_2, $arg_3, [ $subFieldId, $this->current_row_index ] );
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
	}