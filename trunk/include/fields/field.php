<?php

	namespace hiweb\fields;


	use hiweb\console;
	use hiweb\fields\field\backend;
	use hiweb\fields\field\frontend;
	use hiweb\fields\locations\location;


	class field{

		/** @var location */
		private $location;
		/** @var backend */
		private $backend;
		/** @var frontend */
		private $frontend;
		/** @var null|mixed */
		private $default_value = null;


		public function __construct(){
			$this->location = new location();
			$this->location->add_field( $this );
			$this->backend = new backend( $this );
			$this->frontend = new frontend( $this );
		}


		/**
		 * @return string
		 */
		public function global_id(){
			return spl_object_hash( $this );
		}


		/**
		 * @return location
		 */
		public function location(){
			return $this->location;
		}


		/**
		 * @return backend
		 */
		public function backend(){
			return $this->backend;
		}


		/**
		 * @return frontend
		 */
		public function frontend(){
			return $this->frontend;
		}


		/**
		 * @param null $set
		 * @return $this|mixed|null
		 */
		public function default_value( $set = null ){
			if( is_null( $set ) ){
				return $this->default_value;
			} else {
				$this->default_value = $set;
				return $this;
			}
		}


	}