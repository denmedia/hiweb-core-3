<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	class type{

		public $field;
		public $type = 'text';
		public $tags = [];
		public $value;
		public $attributes = [];


		/**
		 * input constructor.
		 * @param field  $field
		 * @param string $type
		 */
		public function __construct( field $field, $type = 'text' ){
			$this->type = $type;
			$this->field = $field;
		}


		/**
		 * @param string $key
		 * @param null   $value
		 * @return $this|mixed|null
		 */
		public function attributes( $key = '', $value = null ){
			if( !is_array( $this->attributes ) ){
				console::debug_error( 'Свойство attributes не являеться массивом', $this->attributes );
			}
			if( is_null( $value ) ){
				if( !is_array( $this->attributes ) ){
					return null;
				} else {
					return array_key_exists( $key, $this->attributes ) ? $this->attributes[ $key ] : null;
				}
			} else {
				$this->attributes[ $key ] = $value;
				return $this;
			}
		}


		/**
		 * @return string
		 */
		public function get_tags(){
			$R = [];
			if( is_array( $this->tags ) ) foreach( $this->tags as $key => $value ){
				$R[] = $key . '="' . htmlentities( is_array( $value ) ? json_encode( $value ) : $value, ENT_QUOTES, 'UTF-8' ) . '"';
			}
			return implode( ' ', $R );
		}


		/**
		 * @param mixed $value
		 * @return mixed
		 */
		public function sanitize( $value ){
			return htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		}


		/**
		 * @return mixed
		 */
		final public function value(){
			return $this->sanitize( $this->value );
		}


		/**
		 * @return string
		 */
		protected function get_input(){
			$this->tags['type'] = $this->type;
			$this->tags['value'] = $this->sanitize( $this->value );
			ob_start();
			?><input <?= $this->get_tags() ?>/><?php
			return ob_get_clean();
		}


		/**
		 * Get html string of the input
		 * @return string
		 */
		final public function HTML(){
			return $this->get_input();
		}


		/**
		 * Echo html of the input
		 */
		final public function THE(){
			echo $this->get_input();
		}
	}