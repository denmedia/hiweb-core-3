<?php

	namespace hiweb\fields\locations\options;


	class post_types_position_metabox_context{

		/** @var post_types */
		private $post_types;
		/** @var post_types_position_metabox */
		private $post_types_meta_boxes;
		private $sub_options = [];


		public function __construct( post_types_position_metabox $post_types_meta_boxes, post_types $post_types ){
			$this->post_types = $post_types;
			$this->post_types_meta_boxes = $post_types_meta_boxes;
		}


		/**
		 * @param $key
		 * @return post_types_position_metabox
		 */
		private function set( $key ){
			$this->post_types->options['meta_box']['context'] = $key;
			$this->post_types_meta_boxes->sub_options['context'] = $key;
			return $this->post_types_meta_boxes;
		}


		/**
		 * @return post_types_position_metabox
		 */
		public function normal(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return post_types_position_metabox
		 */
		public function advanced(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return post_types_position_metabox
		 */
		public function side(){
			return $this->set( __FUNCTION__ );
		}

	}