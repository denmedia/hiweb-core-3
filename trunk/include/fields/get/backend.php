<?php

	namespace hiweb\fields\get;


	use hiweb\fields\field;
	use hiweb\fields\locations;
	use hiweb\forms;
	use hiweb\string;


	class backend{

		//POST TYPES

		/**
		 * @param null $post
		 * @param int  $position
		 * @return field[]
		 */
		private function get_fields_by_post_type_position( $post = null, $position = 3 ){
			$R = [];
//			if( function_exists( 'get_current_screen' ) && is_object( get_current_screen() ) ){
//				///GET LOCATIONS by CONTEXT
//				/** @var field[] $R */
//				$args = [];
//				if( is_int( $position ) ) $args['position'] = $position;
//				$args['post_type'] = get_current_screen()->id;
//
//				//POST SETTINGS
//				if( $post instanceof \WP_Post ){
//					//Front Page Fields
//					$args['front_page'] = intval( $post->ID ) == intval( get_option( 'page_on_front' ) );
//					$args['ID'] = $post->ID;
//					$args['post_name'] = $post->post_name;
//				}
//				$R = locations::get_fields_by( 'post_type', $args );
//				if( $post instanceof \WP_Post ){
//					foreach( $R as $field ){
//						if( $field instanceof field ) $field->value( get_post_meta( $post->ID, $field->id(), true ) );
//					}
//				}
//			}
			return $R;
		}


		/**
		 * @param null $post
		 * @param int  $position
		 */
		private function the_form_post( $post = null, $position = 3 ){
			$fields = $this->get_fields_by_post_type_position( $post, $position );
			if( is_array( $fields ) && count( $fields ) > 0 ) forms::register( string::rand() )->add_fields( $fields )->the_noform( __FUNCTION__ );
		}


		//Post Type, Position 0
		public function edit_form_top( $post = null ){
			$this->the_form_post( $post, 0 );
		}


		//Post Type, Position 1
		public function edit_form_before_permalink( $post = null ){
			$this->the_form_post( $post, 1 );
		}


		//Post Type, Position 2
		public function edit_form_after_title( $post = null ){
			$this->the_form_post( $post, 2 );
		}


		//Post Type, Position 3
		public function edit_form_after_editor( $post = null ){
			$this->the_form_post( $post, 3 );
		}


		//Post Type, Position 4
		public function submitpost_box( $post = null ){
			$this->the_form_post( $post, 4 );
		}


		//Post Type:PAGE, Position 4
		public function submitpage_box( $post = null ){
			$this->the_form_post( $post, 4 );
		}


		//Post Type, Position 5
		public function edit_form_advanced( $post = null ){
			$this->the_form_post( $post, 5 );
		}


		//Post Type: PAGE, Position 5
		public function edit_page_form( $post = null ){
			$this->the_form_post( $post, 5 );
		}


		//Post Type: PAGE, Position 6
		public function dbx_post_sidebar( $post = null ){
			$this->the_form_post( $post, 6 );
		}



		//EDIT PAGE
		//SAVE

	}