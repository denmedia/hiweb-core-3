<?php

	namespace hiweb\fields\locations\options;


	use hiweb\console;


	class post_types_position_simple{

		/** @var post_types */
		private $post_types;


		public function __construct( post_types $post_types ){
			$this->post_types = $post_types;
			$this->edit_form_after_editor();
		}


		/**
		 * @return \hiweb\fields\locations\location
		 */
		public function _get_parent_location(){
			return $this->post_types->_get_parent_location();
		}


		/**
		 * @return post_types
		 */
		public function edit_form_top(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}


		/**
		 * @return post_types
		 */
		public function edit_form_before_permalink(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}


		/**
		 * @return post_types
		 */
		public function edit_form_after_title(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}


		/**
		 * @return post_types
		 */
		public function edit_form_after_editor(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}


		/**
		 * @return post_types
		 */
		public function submitpost_box(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}


		/**
		 * @return post_types
		 */
		public function edit_form_advanced(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}


		/**
		 * @return post_types
		 */
		public function dbx_post_sidebar(){
			$this->post_types->options['position'] = __FUNCTION__;
			return $this->post_types;
		}

	}