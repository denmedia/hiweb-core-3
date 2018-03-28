<?php

	namespace hiweb\fields\locations\options;


	class columns_manager extends options{

		private $parent_options;


		public function __construct( options $parent_options ){
			$this->parent_options = $parent_options;
			$this->position( 3 );
		}


		/**
		 * @param int $set
		 * @return $this
		 */
		public function position( $set = 3 ){
			$this->set_option( __FUNCTION__, $set );
			return $this;
		}


		/**
		 * @param string $set
		 * @return $this
		 */
		public function name( $set = null ){
			$this->set_option( __FUNCTION__, $set );
			return $this;
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function callback( $set ){
			$this->set_option( __FUNCTION__, $set );
			return $this;
		}


		/**
		 * @param bool $set
		 * @return $this
		 */
		public function sortable( $set = true ){
			$this->set_option( __FUNCTION__, $set );
			return $this;
		}

	}