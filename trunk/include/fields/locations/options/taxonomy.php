<?php

	namespace hiweb\fields\options;


	use hiweb\fields\field;
	use hiweb\fields\separator;


	class taxonomy{

		/** @var location */
		private $location_root;


		public function __construct( location $location_root ){
			$this->location_root = $location_root;
		}


		public function hierarchical( $set = true ){
			$this->location_root->rules['taxonomy'][ __FUNCTION__ ] = $set;
			$this->location_root->update_rulesId();
			return $this;
		}


		public function name( $set ){
			$this->location_root->rules['taxonomy'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function object_type( $set ){
			$this->location_root->rules['taxonomy'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function term( $taxonomy_name, $terms, $term_field = 'slug' ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ][ $taxonomy_name ][ $term_field ] = is_array( $terms ) ? $terms : [ $terms ];
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
