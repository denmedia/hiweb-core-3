<?php

	namespace hiweb\fields\locations;


	use hiweb\fields\field;
	use hiweb\fields\separator;


	class options{

		/** @var root */
		private $location_root;

		private $slug = '';


		public function __construct( root $location_root ){
			$this->location_root = $location_root;
			$this->location_root->rules['options_page']['section_title'] = [ '' ];
			$this->location_root->update_rulesId();
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function slug( $set ){
			$set = preg_replace( [ '/^options-/', '/.php$/' ], '', $set );
			$this->slug = $set;
			$this->location_root->rules['options_page'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		/**
		 * @return string
		 */
		public function get_slug(){
			return $this->slug;
		}


		public function section_title( $set ){
			$this->location_root->rules['options_page'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
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
		 * @return root
		 */
		public function get_location(){
			return $this->location_root;
		}
	}
