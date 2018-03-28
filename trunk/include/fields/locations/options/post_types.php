<?php

	namespace hiweb\fields\locations\options;


	use hiweb\fields\locations\location;
	use hiweb\fields\locations\locations;


	class post_types extends options{

		private $columns_manager;
		private $position;
		private $meta_box;


		public function __construct( location $location ){
			parent::__construct( $location );
			$this->POSITION();
		}


		/**
		 * @param string $label
		 * @return $this
		 */
		public function label( $label ){
			$this->set_option( __FUNCTION__, $label );
			return $this;
		}


		/**
		 * @param $description
		 * @return $this
		 */
		public function description( $description ){
			$this->set_option( __FILE__, $description );
			return $this;
		}


		/**
		 * @param string $post_type
		 * @return $this
		 */
		public function post_type( $post_type ){
			$this->set_option( __FUNCTION__, $post_type );
			return $this;
		}


		/**
		 * @param int|string|int[]|string[] $ID
		 * @return $this
		 */
		public function ID( $ID ){
			$this->set_option( __FUNCTION__, $ID );
			return $this;
		}


		/**
		 * @param string|string[] $post_name
		 * @return $this
		 */
		public function post_name( $post_name ){
			$this->set_option( __FUNCTION__, $post_name );
			return $this;
		}


		/**
		 * @param string|string[] $post_status
		 * @return $this
		 */
		public function post_status( $post_status ){
			$this->set_option( __FUNCTION__, $post_status );
			return $this;
		}


		/**
		 * @param bool $comment_status
		 * @return $this
		 */
		public function comment_status( $comment_status ){
			$this->set_option( __FUNCTION__, $comment_status );
			return $this;
		}


		/**
		 * @param string|string[] $post_parent
		 * @return $this
		 */
		public function post_parent( $post_parent ){
			$this->set_option( __FUNCTION__, $post_parent );
			return $this;
		}


		/**
		 * @param string|string[] $taxonomy_name
		 * @return $this
		 */
		public function has_taxonomy( $taxonomy_name ){
			$this->set_option( __FUNCTION__, $taxonomy_name );
			return $this;
		}


		/**
		 * @param bool $is_front_page
		 * @return $this
		 */
		public function front_page( $is_front_page = true ){
			$this->set_option( __FUNCTION__, $is_front_page );
			return $this;
		}


		/**
		 * Set position without Meta Box
		 * @return post_types_position_simple
		 */
		public function POSITION(){
			if( !$this->position instanceof post_types_position_simple ){
				$this->position = new post_types_position_simple( $this );
			}
			return $this->position;
		}


		/**
		 * Set position by Meta Box
		 * @return post_types_position_metabox
		 */
		public function META_BOX(){
			if( !$this->meta_box instanceof post_types_position_metabox ){
				$this->meta_box = new post_types_position_metabox( $this );
				locations::$last_field_location_metabox = $this->meta_box;
			}
			return $this->meta_box;
		}


		/**
		 * @param $template - template php-file path
		 * @return $this
		 */
		public function template( $template ){
			$this->set_option( __FUNCTION__, $template );
			return $this;
		}


		/**
		 * @return columns_manager
		 */
		public function COLUMNS_MANAGER(){
			if( !$this->columns_manager instanceof columns_manager ) $this->columns_manager = new columns_manager( $this );
			$this->set_option( __FUNCTION__, $this->columns_manager );
			return $this->columns_manager;
		}

	}
