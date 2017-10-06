<?php

	namespace hiweb\fields\options;


	use hiweb\fields\field;
	use hiweb\fields\separator;


	class user{

		/** @var location */
		private $location_root;


		public function __construct( location $location_root ){
			$this->location_root = $location_root;
			$this->location_root->rules['user']['position'] = [ 2 ];
			$this->location_root->update_rulesId();
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


		/**
		 * @param int $position - 0 → pre user fields, 1 → color scheme picker place, 2 → after user fields, 3 → personal fields
		 * @return $this
		 */
		public function position( $position = 2 ){
			$this->location_root->rules['user'][ __FUNCTION__ ] = [ $position ];
			$this->location_root->update_rulesId();
			return $this;
		}


		/**
		 * @param $id
		 * @return $this
		 */
		public function id( $id ){
			$this->location_root->rules['user'][ __FUNCTION__ ] = [ $id ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function role( $role ){
			$this->location_root->rules['user'][ __FUNCTION__ ] = is_array( $role ) ? $role : [ $role ];
			$this->location_root->update_rulesId();
			return $this;
		}
	}
