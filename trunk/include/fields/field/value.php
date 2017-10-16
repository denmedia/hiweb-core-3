<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2017
	 * Time: 15:45
	 */

	namespace hiweb\fields\field;


	trait value{

		/** @var mixed */
		private $value;
		/** @var array|value_context[] */
		private $value_contexts = [];


		/**
		 * @param null|string|array $value
		 * @return string|$this
		 */
		public function value_default( $value = null ){
			if( is_null( $value ) ){
				return $this->value;
			} else {
				$this->value = $value;
				return $this;
			}
		}


		/**
		 * @param $value
		 * @return mixed
		 */
		public function value_sanitize( $value ){
			return $value;
		}


		/**
		 * @param null $contextObject
		 * @return value_context
		 */
		public function value_context( $contextObject = null ){
			$location_id = \hiweb\fields\functions\get_contextId_from_contextObject( $contextObject );
			///
			if( !array_key_exists( $location_id, $this->value_contexts ) ){
				$this->value_contexts[ $location_id ] = new value_context( $this, $contextObject );
			}
			return $this->value_contexts[ $location_id ];
		}

	}