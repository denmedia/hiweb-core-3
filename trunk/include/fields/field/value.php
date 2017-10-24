<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2017
	 * Time: 15:45
	 */

	namespace hiweb\fields\field;


	use hiweb\fields\field;
	use hiweb\fields\locations\locations;


	trait value{

		/** @var mixed */
		protected $value_default;
		/** @var array|context[] */
		private $value_contexts = [];


		/**
		 * @param null|mixed $value
		 * @return string|$this
		 */
		public function value_default( $value = null ){
			if( is_null( $value ) ){
				return $this->value_default;
			} else {
				$this->value_default = $value;
				return $this;
			}
		}


		/**
		 * @param $value
		 * @return mixed
		 */
		public function get_value_sanitize( $value ){
			return $value;
		}


		/**
		 *
		 * @param $value
		 * @return mixed
		 */
		public function get_value_content( $value ){
			return $this->get_value_sanitize( $value );
		}


		/**
		 * @param null $contextObject
		 * @return context
		 */
		public function context( $contextObject = null ){
			$location_id = locations::get_contextId_from_contextObject( $contextObject );
			///
			if( !array_key_exists( $location_id, $this->value_contexts ) ){
				/** @var field $this */
				$this->value_contexts[ $location_id ] = new context( $this, $contextObject );
			}
			return $this->value_contexts[ $location_id ];
		}

	}