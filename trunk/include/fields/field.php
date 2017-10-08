<?php

	namespace hiweb\fields;


	use hiweb\fields\locations\location;


	class field{

		//use hw_hidden_methods_props;

		private $id;
		private $global_id;
		///
		/** @var  location */
		private $location;
		///
		public $name;
		public $description;
		private $on_change = [];
		///
		/** @var mixed Значение по-умолчанию */
		private $value_default;

		private $form_template = '';


		/**
		 * field constructor.
		 * @param        $fieldId - индификатор поля
		 * @param string $fieldType
		 */
		public function __construct( $fieldId, $fieldType = 'text' ){
			$this->id = mb_strtolower( $fieldId );
			$this->global_id = spl_object_hash( $this );
			$this->name = $fieldId;
			//$this->make_input( $fieldType );
		}


		public function id(){
			return $this->id;
		}


		/**
		 * @return string
		 */
		public function global_id(){
			return $this->global_id;
		}


		/**
		 * Установить/получить имя поля
		 * @param null $set
		 * @return field|string
		 */
		public function label( $set = null ){
			if( is_null( $set ) ){
				return $this->name;
			}
			$this->name = $set;
			return $this;
		}


		/**
		 * Установить/получить пояснение для поля
		 * @param null $set
		 * @return field|string
		 */
		public function description( $set = null ){
			if( is_null( $set ) ){
				return $this->description;
			}
			$this->description = $set;
			return $this;
		}


		/**
		 * @param null $set
		 * @return field|string
		 */
		public function prepend( $set = null ){
			if( is_null( $set ) ){
				return $this->{__FUNCTION__};
			} else {
				$this->{__FUNCTION__} = $set;
				return $this;
			}
		}


		/**
		 * @param null $set
		 * @return field|string
		 */
		public function append( $set = null ){
			if( is_null( $set ) ){
				return $this->{__FUNCTION__};
			} else {
				$this->{__FUNCTION__} = $set;
				return $this;
			}
		}


		/**
		 * Установить/получить значение поля по-умолчания
		 * @param null $set
		 * @return field|string
		 */
		public function value_default( $set = null ){
			if( is_null( $set ) ){
				return $this->value_default;
			}
			$this->value_default = $set;
			return $this;
		}

		///////////////////////


		/**
		 * @param null $set
		 * @return field|string
		 */
		public function form_template( $set = null ){
			if( is_string( $set ) && trim( $set ) != '' ){
				$this->form_template = $set;
				return $this;
			} else {
				return $this->form_template;
			}
		}


		/**
		 * @return location
		 */
		public function location(){
			if( !$this->location instanceof location ){
				$this->location = locations\locations::register();
				$this->location->add_field( $this );
			}
			return $this->location;
		}


		/**
		 * @param null $callback
		 * @return array|field
		 */
		public function on_change( $callback = null ){
			if( is_null( $callback ) ){
				return $this->on_change;
			} else {
				$this->on_change[] = $callback;
				return $this;
			}
		}


	}