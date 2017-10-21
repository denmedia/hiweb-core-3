<?php

	namespace hiweb\fields\locations;


	use hiweb\console;
	use hiweb\fields\field;
	use hiweb\fields\forms;
	use hiweb\path;


	class admin{

		/**
		 * @param     $post
		 * @param int $position
		 * @return field[]
		 */
		private static function get_post_type_context_fields( $post, $position = null ){
			$R = [];
			if( !$post instanceof \WP_Post ){
				console::debug_warn( 'Для функции передан не WP_Post объект!', $post );
			} else {
				$context_location = locations::get_abstractLocation_from_contextObject( $post );
				if( !is_null( $position ) ) $context_location->post_types()->position( $position );
				$locations = locations::get_locations_by_contextLocation( $context_location );
				$R = [];
				foreach( $locations as $location ){
					if( $location->field instanceof field ){
						$R[ $location->field->id() ] = $location->field;
					}
				}
			}
			return $R;
		}


		static function edit_form_top( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 0 ), $post );
		}


		static function edit_form_before_permalink( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 1 ), $post );
		}


		static function edit_form_after_title( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 2 ), $post, 'post-box' );
		}


		static function edit_form_after_editor( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 3 ), $post );
		}


		static function submitpost_box( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 4 ), $post );
		}


		static function edit_form_advanced( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 5 ), $post );
		}


		static function dbx_post_sidebar( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, 6 ), $post );
		}


		//COLUMNS POST TYPE MANAGE

		static function manage_posts_custom_column( $columns_name, $post_id ){
			//todo
		}


		static function manage_posts_columns( $posts_columns, $post_type = 'page' ){
			//todo
		}


		static function save_post( $post_id, $post, $update ){
			$location = locations::get_abstractLocation_from_contextObject( $post );
			unset( $location->post_types()->options['position'] );
			$fields = locations::get_fields_by_contextLocation( $location );
			foreach( $fields as $field_id => $field ){
				update_post_meta( $post_id, $field->id(), path::request( forms::get_field_input_name( $field ) ) );
			}
		}


		static function taxonomy_add_form_fields( $taxonomy ){
			$context_location = new location();
			$context_location->taxonomies( $taxonomy );
			$fields = locations::get_fields_by_contextLocation( $context_location );
			foreach( $fields as $field ){
				if( trim( $field->admin_template() ) == 'default' ) $field->admin_template( 'term-add' );
			}
			forms::the_form_by_fields( $fields );
		}


		static function taxonomy_edit_form( $term, $taxonomy ){
			$context_location = locations::get_abstractLocation_from_contextObject( $term );
			$fields = locations::get_fields_by_contextLocation( $context_location );
			foreach( $fields as $field ){
				if( trim( $field->admin_template() ) == 'default' ) $field->admin_template( 'term-edit' );
			}
			forms::the_form_by_fields( $fields );
		}


		static function taxonomy_edited_term( $term_id, $tt_id, $taxonomy ){
			$term = get_term_by( 'id', $term_id );
			if( $term instanceof \WP_Term ){
				$location = locations::get_abstractLocation_from_contextObject( $term );
				$fields = locations::get_fields_by_contextLocation( $location );
				foreach( $fields as $field_id => $field ){
					update_term_meta( $term_id, $field->id(), path::request( forms::get_field_input_name( $field ) ) );
				}
			}
		}


		///USERS


		/**
		 * @param \WP_User $wp_user
		 * @param null $position
		 * @return array|field[]
		 */
		static private function get_users_fields( $wp_user, $position = null ){
			if( $wp_user instanceof \WP_User ){
				$context_location = locations::get_abstractLocation_from_contextObject( $wp_user );
				$context_location->users()->position( $position );
				return locations::get_fields_by_contextLocation( $context_location );
			}
			return [];
		}


		static function user_new_form(){
			$contextLocation = new location();
			$contextLocation->users();
			$fields = locations::get_fields_by_contextLocation( $contextLocation );
			forms::the_form_by_fields( $fields, false, 'user-edit' );
		}


		/**
		 * @param \WP_User $wp_user
		 */
		static function personal_options( $wp_user ){
			if( $wp_user instanceof \WP_User ){
				$fields = self::get_users_fields( $wp_user, 0 );
				forms::the_form_by_fields( $fields, $wp_user, 'user-edit' );
			}
		}


		/**
		 * @param \WP_User $wp_user
		 */
		static function edit_user_profile( $wp_user ){
			if( $wp_user instanceof \WP_User ){
				$fields = self::get_users_fields( $wp_user, 1 );
				forms::the_form_by_fields( $fields, $wp_user, 'user-edit' );
			}
		}


		/**
		 * @param \WP_User $wp_user
		 */
		static function profile_personal_options( $wp_user ){
			if( $wp_user instanceof \WP_User ){
				$fields = self::get_users_fields( $wp_user, 2 );
				forms::the_form_by_fields( $fields, $wp_user, 'user-edit' );
			}
		}


		/**
		 * @param null|integer $user_id
		 */
		static function admin_color_scheme_picker( $user_id = null ){
			$wp_user = get_user_by( 'id', $user_id );
			if( $wp_user instanceof \WP_User ){
				$fields = self::get_users_fields( $wp_user, 3 );
				forms::the_form_by_fields( $fields, $wp_user );
			}
		}


		/**
		 * @param null|integer $user_id
		 */
		static function edit_user_profile_update( $user_id = null ){
			$wp_user = get_user_by( 'id', $user_id );
			if( $wp_user instanceof \WP_User ){
				$location = locations::get_abstractLocation_from_contextObject( $wp_user );
				unset( $location->users()->options['position'] );
				$fields = locations::get_fields_by_contextLocation( $location );
				foreach( $fields as $field_id => $field ){
					update_user_meta( $user_id, $field->id(), forms::get_field_input_name( $field ) );
				}
			}
		}

	}