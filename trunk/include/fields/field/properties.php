<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2017
	 * Time: 15:32
	 */

	namespace hiweb\fields\field;


	trait properties{

		/** @var string */
		private $id = '';

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

	}