<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 20.10.2017
	 * Time: 16:25
	 */

	namespace hiweb\post_types\post_type;


	class rewrite{

		public $rewrite = [];


		/**
		 * @param array $set
		 * @return $this
		 */
		public function slug( $set = [] ){
			$this->rewrite['slug'] = $set;
			return $this;
		}


		/**
		 * @param bool $set
		 * @return $this
		 */
		public function with_front( $set = true ){
			$this->rewrite['with_front'] = $set;
			return $this;
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function feeds( $set ){
			$this->rewrite['feeds'] = $set;
			return $this;
		}


		/**
		 * @param bool $set
		 * @return $this
		 */
		public function pages( $set = true ){
			$this->rewrite['pages'] = $set;
			return $this;
		}


		/**
		 * @param $set
		 * @return $this
		 */
		public function ep_mask( $set ){
			$this->rewrite['ep_mask'] = $set;
			return $this;
		}

	}