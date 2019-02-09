<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05/12/2018
	 * Time: 00:07
	 */

	namespace hiweb\js;


	use hiweb\js;
	use hiweb\paths;
	use hiweb\paths\path;


	/**
	 * Class file
	 * @package hiweb\js
	 */
	class file extends path{

		/**
		 * @var array
		 */
		private $deeps = [];

		/**
		 * @var bool
		 */
		private $footer = false;

		/**
		 * @var string
		 */
		private $async = 'defer';


		/**
		 * @return $this
		 */
		public function put_to_footer(){
			$this->footer = true;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function is_in_footer(){
			return $this->footer;
		}


		/**
		 * @return $this
		 */
		public function set_async(){
			$this->async = 'async';
			return $this;
		}


		/**
		 * @return $this
		 */
		public function set_defer(){
			$this->async = 'defer';
			return $this;
		}


		/**
		 * @return $this
		 */
		public function set_disable_async(){
			$this->async = '';
			return $this;
		}


		/**
		 * @return string
		 */
		public function get_async(){
			return $this->async;
		}


		/**
		 * Add deeps styles
		 * @param null|string|array $deeps
		 * @return array
		 */
		public function add_deeps( $deeps = null ){
			if( !is_null( $deeps ) ){
				$deeps = is_array( $deeps ) ? $deeps : [ $deeps ];
				$this->deeps = array_merge( $this->deeps, $deeps );
			}
			return $this->deeps;
		}


		/**
		 * @return array
		 */
		public function get_deeps(){
			return $this->deeps;
		}


		/**
		 * Return (echo) link rel html
		 * @return null|string
		 */
		public function html(){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


		/**
		 *
		 */
		public function the(){
			?>
<script <?=$this->get_async() != '' ? $this->get_async() : ''?> data-handle="<?=$this->handle()?>" src="<?= $this->get_url() ?>"></script>
<?php
		}


		/**
		 * @return string
		 */
		public function handle(){
			return js::get_handle( $this->get_path_relative() );
		}


	}