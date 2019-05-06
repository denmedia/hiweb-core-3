<?php

	namespace hiweb\traits;


	use hiweb\hidden_methods;


	trait options{

		use hidden_methods;

		protected $options = [];


		/**
		 * @param      $key
		 * @param      $value
		 * @param bool $merge_if_array
		 * @return $this
		 */
		protected function set_value( $key, $value, $merge_if_array = false ){
			if( $merge_if_array && isset( $this->options[ $key ] ) && is_array( $this->options[ $key ] ) ){
				if( !is_array( $value ) ) $value = [ $value ];
				$this->options[ $key ] = array_merge( $this->options[ $key ], $value );
			} else {
				$this->options[ $key ] = $value;
			}
			return $this;
		}


		/**
		 * @param      $key
		 * @param null $default
		 * @return mixed|null
		 */
		protected function get_value( $key, $default = null ){
			return array_key_exists( $key, $this->options ) ? $this->options[ $key ] : $default;
		}


		/**
		 * @return array
		 */
		public function get(){
			return $this->options;
		}

	}