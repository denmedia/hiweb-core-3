<?php

	namespace hiweb\fields\locations\options;


	class post_type extends options{

		private $columns_manager;


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
		 * @param int $position - position in post edit page: 1 - after title, 2 - before editor, 3 - after editor, 4 - over sidebar, 5 - bottom on edit page, 6 - dbx_post_sidebar
		 * @return $this
		 */
		public function position( $position = 3 ){
			$this->set_option( __FUNCTION__, $position );
			return $this;
		}


		/**
		 * @return columns_manager
		 */
		public function columns_manager(){
			if( !$this->columns_manager instanceof columns_manager ) $this->columns_manager = new columns_manager( $this );
			$this->set_option( __FUNCTION__, $this->columns_manager );
			return $this->columns_manager;
		}

	}
