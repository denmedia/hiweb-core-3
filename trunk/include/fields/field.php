<?php

	namespace hiweb\fields;


	use hiweb\fields\field\backend;
	use hiweb\fields\field\frontend;
	use hiweb\fields\field\type;
	use hiweb\fields\field\types;
	use hiweb\fields\locations\location;
	use hiweb\fields\locations\locations;


	class field{

		private $id = '';
		/** @var location */
		private $location;
		/** @var backend */
		private $backend;
		/** @var frontend */
		private $frontend;
		/** @var null|mixed */
		private $default_value = null;
		/** @var type */
		private $type;
		/** @var string */
		private $type_name = 'text';


		public function __construct( $id, $type_name = 'text' ){
			$this->id = $id;
			$this->location = locations::register();
			$this->location->add_field( $this );
			$this->backend = new backend( $this );
			$this->frontend = new frontend( $this );
			$this->type_name = $type_name;
		}


		/**
		 * @return string
		 */
		public function id(){
			return $this->id;
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


		/**
		 * @return string
		 */
		public function type_name(){
			return $this->type_name;
		}


		/**
		 * @return bool|type|string
		 */
		public function type(){
			if( !$this->type instanceof type ){
				$this->type = types::register( $this->type_name );
				$this->type->tags['name'] = 'hiweb-field-' . $this->id;
			}
			return $this->type;
		}


	}