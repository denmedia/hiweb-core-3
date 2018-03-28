<?php

	namespace hiweb\fields\locations\options;


	class post_types_position_metabox_priority{

		/** @var post_types */
		private $post_types;
		/** @var post_types_position_metabox */
		private $post_types_meta_boxes;


		public function __construct( post_types_position_metabox $post_types_meta_boxes, post_types $post_types ){
			$this->post_types = $post_types;
			$this->post_types_meta_boxes = $post_types_meta_boxes;
		}


		/**
		 * @param $key
		 * @return post_types_position_metabox
		 */
		private function set( $key ){
			$this->post_types->options['meta_box']['priority'] = $key;
			$this->post_types_meta_boxes->sub_options['priority'] = $key;
			return $this->post_types_meta_boxes;
		}


		public function high(){
			return $this->set( __FUNCTION__ );
		}


		public function low(){
			return $this->set( __FUNCTION__ );
		}


		public function default_(){
			return $this->set( 'default' );
		}

	}