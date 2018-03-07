<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2017
	 * Time: 15:45
	 */

	namespace hiweb\fields;


	use hiweb\console;
	use hiweb\fields\field\value_rows;


	class value{

		protected $parent_field;
		/** @var mixed */
		public $data;

		private $ROWS;


		/**
		 * value constructor.
		 * @param \hiweb\fields\field $field
		 * @param null|mixed          $default
		 */
		public function __construct( field $field, $default = null ){
			$this->parent_field = $field;
			$this->data = $default;
		}


		/**
		 * Clone process
		 */
		public function __clone(){
			$this->ROWS = null;
		}


		/**
		 * Set value
		 * @param mixed $mixed
		 */
		final public function set( $mixed ){
			$this->data = $mixed;
		}


		/**
		 * @return field
		 */
		final public function get_parent_field(){
			return $this->parent_field;
		}


		/**
		 * Get value
		 * @return string|$this
		 */
		final public function get(){
			return $this->data;
		}


		/**
		 * Sanitize function
		 * @return mixed
		 */
		final public function get_sanitized(){
			return $this->sanitize( $this->get() );
		}


		/**
		 * @param $value
		 * @return mixed
		 */
		public function sanitize( $value ){
			return $value;
		}


		/**
		 * Get value content
		 * @return mixed
		 */
		public function get_content(){
			return $this->get_sanitized();
		}


		/**
		 * @return value_rows
		 */
		public function rows(){
			if( !$this->ROWS instanceof value_rows ){
				$this->ROWS = new value_rows( $this );
			}
			return $this->ROWS;
		}


	}