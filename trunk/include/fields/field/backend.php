<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	class backend{

		/** @var  field */
		private $field;

		/** @var string */
		private $label = '';
		/** @var string */
		private $description = '';
		/** @var string */
		private $template = '';

		public $context_value = [];


		public function __construct( field $field ){
			$this->field = $field;
		}


		public function __call( $name, $arguments ){
			return $this->set( $name, $arguments );
		}


		/**
		 * @param string     $property
		 * @param null|mixed $value
		 * @return $this|null|mixed
		 */
		private function set( $property = 'label', $value = null ){
			if( !property_exists( $this, $property ) ){
				console::debug_warn( 'Попытка установить не существующее свойство', [ $property, $value ] );
				if( is_null( $value ) ){
					return $this;
				} else {
					return null;
				}
			} else {
				if( is_null( $value ) ){
					return $this->{$property};
				} else {
					$this->{$property} = $value;
					return $this;
				}
			}
		}


		public function label( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		public function description( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		public function template( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		public function get_context_value( $context_id = '' ){
			if( !is_array( $context_id ) ) $context_id = json_encode( $context_id );
			if( array_key_exists( $context_id, $this->context_value ) ) {
				///todo!
			}
		}


		/**
		 * @param string $context_id
		 */
		public function the( $context_id = '' ){

		}

	}