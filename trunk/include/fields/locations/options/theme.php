<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 19.02.2018
	 * Time: 10:24
	 */

	namespace hiweb\fields\locations\options;


	class theme extends options{

		/**
		 * @param null|string $set
		 * @return $this
		 */
		public function section_title( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @param null|string $set
		 * @return $this
		 */
		public function section_description( $set = null ){
			return $this->set_option( __FUNCTION__, $set );
		}

	}