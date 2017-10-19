<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	trait backend{

		/** @var  field */
		private $field;

		/** @var string */
		private $label = '';
		/** @var string */
		private $description = '';
		/** @var string */
		private $template = '';

		public $context_value = [];


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


		/**
		 * @param null $value
		 */
		public function the( $value = null ){
			//$this->field->type()->value = $value;
			//$this->field->type()->the();
		}

	}