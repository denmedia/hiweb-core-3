<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 17.10.2017
	 * Time: 15:01
	 */

	namespace hiweb\fields\locations\options;


	class taxonomies extends options{


		private $columns_manager;


		/**
		 * @param $term_id
		 * @return $this
		 */
		public function term_id( $term_id ){
			$this->set_option( __FUNCTION__, $term_id );
			return $this;
		}


		/**
		 * @param $term_taxonomy_id
		 * @return $this
		 */
		public function term_taxonomy_id( $term_taxonomy_id ){
			$this->set_option( __FUNCTION__, $term_taxonomy_id );
			return $this;
		}


		/**
		 * @param $name
		 * @return $this
		 */
		public function name( $name ){
			$this->set_option( __FUNCTION__, $name );
			return $this;
		}


		/**
		 * @param $taxonomy
		 * @return $this
		 */
		public function taxonomy( $taxonomy ){
			$this->set_option( __FUNCTION__, $taxonomy );
			return $this;
		}


		/**
		 * @param $slug
		 * @return $this
		 */
		public function slug( $slug ){
			$this->set_option( __FUNCTION__, $slug );
			return $this;
		}


		/**
		 * @param $count
		 * @return $this
		 */
		public function count( $count ){
			$this->set_option( __FUNCTION__, $count );
			return $this;
		}


		/**
		 * @param $parent
		 * @return $this
		 */
		public function parent( $parent ){
			$this->set_option( __FUNCTION__, $parent );
			return $this;
		}


		/**
		 * @param $term_group
		 * @return $this
		 */
		public function term_group( $term_group ){
			$this->set_option( __FUNCTION__, $term_group );
			return $this;
		}


	}