<?php

	namespace hiweb\fields\locations;


	use hiweb\fields\field;
	use hiweb\fields\separator;


	class admin_menu{

		/** @var location */
		private $location_root;


		public function __construct( location $location_root ){
			$this->location_root = $location_root;
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function slug( $set ){
			$this->location_root->options['admin_menu'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		/**
		 * @return field|separator
		 */
		public function get_field(){
			return $this->location_root->get_field();
		}


		/**
		 * @return location
		 */
		public function get_location(){
			return $this->location_root;
		}
	}
