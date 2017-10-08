<?php

	namespace hiweb\fields\locations\options;


	use hiweb\fields\locations\location;


	abstract class options{

		/** @var location */
		protected $location;
		public $options = [];


		/**
		 * options constructor.
		 * @param location $location
		 */
		public function __construct( location $location ){
			$this->location = $location;
		}


		/**
		 * @param string $option_name
		 * @param string $option_value
		 * @return $this
		 */
		final protected function set_option( $option_name = 'post_type', $option_value = null ){
			if( !is_null( $option_value ) ){
				$this->options[ $option_name ] = $option_value;
			}
			return $this;
		}


		/**
		 * @return location
		 */
		final function get_location(){
			return $this->location;
		}

	}