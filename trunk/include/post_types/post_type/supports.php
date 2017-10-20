<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 20.10.2017
	 * Time: 16:08
	 */

	namespace hiweb\post_types\post_type;


	class supports{

		public $supports = [];


		/**
		 * @param string $support_name
		 * @return supports
		 */
		private function set( $support_name = 'title' ){
			$this->supports[] = $support_name;
			return $this;
		}


		/**
		 * @return supports
		 */
		public function title(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function editor(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function author(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function thumbnail(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function excerpt(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function trackback(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function custom_fields(){
			return $this->set( 'custom-fields' );
		}


		/**
		 * @return supports
		 */
		public function revisions(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function post_formats(){
			return $this->set( 'post-formats' );
		}

	}