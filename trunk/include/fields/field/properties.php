<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2017
	 * Time: 15:32
	 */

	namespace hiweb\fields\field;


	use hiweb\fields\field;


	trait properties{

		/** @var string */
		private $id = '';
		private $global_id = '';


		/**
		 * @return string
		 */
		public function id(){
			return $this->id;
		}


		/**
		 * @param null|string $set
		 * @return field|string
		 */
		public function global_id( $set = null ){
			if( is_null( $set ) ){
				/** @var field $this */
				return $this->global_id;
			} else {
				$this->global_id = $set;
				return $this;
			}
		}

	}