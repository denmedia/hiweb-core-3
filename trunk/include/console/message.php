<?php

	namespace hiweb\console;


	class message{

		public $content = '';
		public $type = 'info';
		public $debugMod = false;


		public function __construct( $content = '', $type = 'info' ){
			$this->content = $content;
			$this->type = $type;
		}


		public function set_content( $content = null ){
			$this->content = $content;
		}


		/**
		 * @return string
		 */
		public function type(){
			$allow_types = [ 'info', 'log', 'warn', 'error' ];
			return array_search( $this->type, $allow_types ) === false ? 'info' : $this->type;
		}


		/**
		 * @return string
		 */
		public function html(){
			return '<script>console.' . $this->type() . '(' . json_encode( $this->content ) . ');</script>';
		}


		/**
		 * Print html
		 */
		public function the(){
			$R = $this->html();
			echo $R;
		}


		public function set_debugMod( $set = true ){
			$this->debugMod = $set;
		}

	}