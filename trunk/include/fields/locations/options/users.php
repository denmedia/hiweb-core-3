<?php

	namespace hiweb\fields\locations\options;


	use hiweb\fields\locations\location;


	class users extends options{

		public function __construct( location $location ){
			parent::__construct( $location );
			$this->options['position'] = 2;
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function position( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function ID( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function display_name( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function first_name( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function last_name( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function locale( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function nickname( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_email( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_firstname( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_lastname( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_level( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_login( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_nicename( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_registered( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_status( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function user_url( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function roles( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}

	}