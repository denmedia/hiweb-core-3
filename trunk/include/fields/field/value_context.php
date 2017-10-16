<?php

	namespace hiweb\fields\field;


	use hiweb\fields\field;


	class value_context{

		private $field;
		private $contextObject;
		private $contextOptions;


		public function __construct( field $field, $contextObject = null ){
			$this->field = $field;
			$this->contextObject = $contextObject;
			$this->contextOptions = \hiweb\fields\functions\get_locationOptions_from_contextObject( $contextObject );
		}


		/**
		 * @return mixed
		 */
		public function value(){
			$value = null;
			if( key_exists( 'post_types', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['post_types'] ) ){
				$post_id = $this->contextOptions['post_types']['ID'];
				$value = metadata_exists( 'post', $post_id, $this->field->id() ) ? get_post_meta( $post_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'taxonomies', $this->contextOptions ) && key_exists( 'term_id', $this->contextOptions['taxonomies'] ) ) {
				$term_id = $this->contextOptions['taxonomies']['term_id'];
				$value = metadata_exists( 'term', $term_id, $this->field->id() ) ? get_term_meta( $term_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'users', $this->contextOptions ) && key_exists( 'ID', $this->contextOptions['users'] ) ) {
				$user_id = $this->contextOptions['users']['ID'];
				$value = metadata_exists( 'user', $user_id, $this->field->id() ) ? get_user_meta( $user_id, $this->field->id(), true ) : $this->field->value_default();
			} elseif( key_exists( 'admin_menus', $this->contextOptions ) && key_exists( 'slug', $this->contextOptions['admin_menus'] ) ) {
				$value = get_option( \hiweb\fields\functions\get_options_field_id( $this->contextOptions['admin_menus']['slug'], $this->field->id() ), $this->field->value_default() );
			}
			return $this->field->value_sanitize( $value );
		}


	}