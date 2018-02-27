<?php

	namespace hiweb\fields\locations\options;


	class post_types_position_metabox{

		private $post_types;

		private $context;
		private $priority;
		public $sub_options = [
			'title' => '&nbsp;',
			'context' => 'advanced',
			'priority' => 'default',
			'callback_args' => []
		];


		public function __construct( post_types $post_types ){
			$this->post_types = $post_types;
			$this->set_option();
		}


		/**
		 * @param $key
		 * @param $val
		 * @return $this
		 */
		private function set_option( $key = null, $val = null ){
			if( is_string( $key ) ){
				$this->sub_options[ $key ] = $val;
			}
			$this->post_types->options['meta_box'] = $this->sub_options;
			$this->post_types->options['position'] = 'meta_box';
			return $this;
		}


		/**
		 * @param $set
		 * @return post_types_position_metabox
		 */
		public function title( $set ){
			return $this->set_option( __FUNCTION__, $set );
		}


		/**
		 * @return post_types_position_metabox_context
		 */
		public function context(){
			if( !$this->context instanceof post_types_position_metabox_context ){
				$this->context = new post_types_position_metabox_context( $this, $this->post_types );
			}
			$this->set_option();
			return $this->context;
		}


		/**
		 * @return post_types_position_metabox_priority
		 */
		public function priority(){
			if( !$this->priority instanceof post_types_position_metabox_priority ){
				$this->priority = new post_types_position_metabox_priority( $this, $this->post_types );
			}
			$this->set_option();
			return $this->priority;
		}


		/**
		 * @param $array
		 * @return post_types_position_metabox
		 */
		public function callback_args( $array ){
			return $this->set_option( __METHOD__, $array );
		}

	}