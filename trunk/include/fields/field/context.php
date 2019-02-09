<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;
	use hiweb\fields\input;
	use hiweb\fields\locations\locations;
	use hiweb\fields\forms;
	use hiweb\fields\value;


	class context{

		private $field;
		/** @var null|\WP_Term|\WP_User|string */
		private $contextObject;
		/** @var array */
		private $contextOptions = [];


		public function __construct( field $field, $contextObject = null ){
			$this->field = $field;
			$this->contextObject = $contextObject;
			$this->contextOptions = locations::get_locationOptions_from_contextObject( $contextObject );
		}


		/**
		 * Return value
		 * @return mixed
		 */
		public function data(){
			$data = null;
			if( key_exists( 'post_types', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['post_types'] ) ){
				$post_id = $this->contextOptions['post_types']['ID'];
				$data = metadata_exists( 'post', $post_id, $this->field->id() ) ? get_post_meta( $post_id, $this->field->id(), true ) : $this->field->VALUE()->get();
				$data = apply_filters( 'hiweb\\fields\\field\\context\\value', $data, $this->field, $this );
			} elseif( key_exists( 'taxonomies', $this->contextOptions ) && key_exists( 'term_id', $this->contextOptions['taxonomies'] ) ) {
				$term_id = $this->contextOptions['taxonomies']['term_id'];
				$data = metadata_exists( 'term', $term_id, $this->field->id() ) ? get_term_meta( $term_id, $this->field->id(), true ) : $this->field->VALUE()->get();
			} elseif( key_exists( 'users', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['users'] ) ) {
				$user_id = $this->contextOptions['users']['ID'];
				$data = metadata_exists( 'user', $user_id, $this->field->id() ) ? get_user_meta( $user_id, $this->field->id(), true ) : $this->field->VALUE()->get();
			} elseif( key_exists( 'admin_menus', $this->contextOptions ) && key_exists( 'menu_slug', $this->contextOptions['admin_menus'] ) ) {
				$data = get_option( forms::get_field_input_option_name( $this->field ), $this->field->VALUE()->get() );
			} elseif( key_exists( 'comments', $this->contextOptions ) ) {
				$comment_id = $this->contextOptions['comments']['comment_ID'];
				$data = metadata_exists( 'comment', $comment_id, $this->field->id() ) ? get_comment_meta( $comment_id, $this->field->id(), true ) : $this->field->VALUE()->get();
			} else {
				console::debug_error( 'Попытка получения значения из контекста, но опции не подходят ни под одину из опций', $this->get_contextOptions() );
			}
			return $data;
		}


		/**
		 * @return field
		 */
		public function get_parent_field(){
			return $this->field;
		}


		/**
		 * @return null|string|\WP_Term|\WP_User
		 */
		public function get_contextObject(){
			return $this->contextObject;
		}


		/**
		 * @return array
		 */
		public function get_contextOptions(){
			return $this->contextOptions;
		}


		/**
		 * @return string
		 */
		public function id(){
			return md5( json_encode( $this->get_contextOptions() ) );
		}


		///////

		private $input;
		private $value;


		/**
		 * Get clone of input instance by context
		 * @return input
		 */
		public function INPUT(){
			if( !$this->input instanceof input ){
				$this->input = clone $this->field->INPUT();
				$this->input->_set_value( $this->VALUE() );
			}
			return $this->input;
		}


		/**
		 * Get clone of value instance by context
		 * @return value
		 */
		public function VALUE(){
			if( !$this->value instanceof value ){
				$this->value = clone $this->field->VALUE();
				$this->value->set( $this->data() );
			}
			return $this->value;
		}


	}