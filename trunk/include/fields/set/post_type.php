<?php

	namespace hiweb\fields\set;


	use hiweb\fields\field;
	use hiweb\fields\separator;


	class post_type{

		/** @var root */
		private $location_root;
		private $columns_manager;

		private $save_post_callback;


		public function __construct( root $location_root ){
			$this->location_root = $location_root;
			$this->location_root->rules['post_type']['position'] = [ 3 ];
			$this->location_root->update_rulesId();
		}


		public function ID( $set ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function post_name( $set ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function post_type( $set = 'page' ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function post_status( $set ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function comment_status( $set ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function post_parent( $set ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function have_taxonomy( $set ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = is_array( $set ) ? $set : [ $set ];
			$this->location_root->update_rulesId();
			return $this;
		}


		public function front_page( $set = true ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = ( $set == true );
			$this->location_root->update_rulesId();
			return $this;
		}


		public function taxonomy_term( $taxonomy, $terms, $term_field = 'slug' ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ][ $taxonomy ][ $term_field ] = is_array( $terms ) ? $terms : [ $terms ];
			$this->location_root->update_rulesId();
			return $this;
		}


		/**
		 * @param int $position - position in post edit page: 1 - after title, 2 - before editor, 3 - after editor, 4 - over sidebar, 5 - bottom on edit page
		 * @return $this
		 */
		public function position( $position = 3 ){
			$this->location_root->rules['post_type'][ __FUNCTION__ ] = [ $position ];
			$this->location_root->update_rulesId();
			return $this;
		}


		/**
		 * @return columns_manager
		 */
		public function columns_manager(){
			if( !$this->columns_manager instanceof columns_manager ){
				$this->columns_manager = new columns_manager( $this );
				$this->location_root->rules['post_type'][ __FUNCTION__ ] = [];
				$this->location_root->update_rulesId();
			}
			return $this->columns_manager;
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


		/**
		 * @param $callback
		 */
		public function save_post( $callback ){
			if( is_callable( $callback ) ) $this->save_post_callback = $callback; else unset( $this->save_post_callback );
		}

	}
