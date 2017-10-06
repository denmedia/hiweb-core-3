<?php

	namespace hiweb\fields\options;


	class columns_manager{

		private $root_location_post_type;
		private $position = 3;
		private $name = null;
		private $callback = null;
		private $sort = false;


		public function __construct( post_type $location_post_type ){
			$this->root_location_post_type = $location_post_type;
			$this->position( 3 );
		}


		/**
		 * @param int $set
		 * @return $this
		 */
		public function position( $set = 3 ){
			$this->position = $set;
			$this->root_location_post_type->get_location()->rules['post_type']['columns_manager'][ __FUNCTION__ ] = $set;
			$this->root_location_post_type->get_location()->update_rulesId();
			return $this;
		}


		/**
		 * @param string $set
		 * @return $this
		 */
		public function name( $set = null ){
			$this->name = $set;
			$this->root_location_post_type->get_location()->rules['post_type']['columns_manager'][ __FUNCTION__ ] = $set;
			$this->root_location_post_type->get_location()->update_rulesId();
			return $this;
		}


		public function callback( $set ){
			$this->callback = $set;
			$this->root_location_post_type->get_location()->rules['post_type']['columns_manager'][ __FUNCTION__ ] = $set;
			$this->root_location_post_type->get_location()->update_rulesId();
			return $this;
		}


		public function sortable(){
			//TODO!
		}


		/**
		 *
		 */
		public function get_field(){
			$this->root_location_post_type->get_field();
		}

	}