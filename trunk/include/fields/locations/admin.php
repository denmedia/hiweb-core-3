<?php

	namespace hiweb\fields\locations;


	use hiweb\arrays;
	use hiweb\console;
	use hiweb\dump;
	use hiweb\fields;
	use hiweb\fields\field;
	use hiweb\fields\forms;
	use hiweb\strings;


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
				if( !is_null( $position ) ){
					if( $position == 'meta_box' ){
						$context_location->POST_TYPES()->META_BOX();
					} else if( method_exists( $context_location->POST_TYPES()->POSITION(), $position ) ){
						$context_location->POST_TYPES()->POSITION()->{$position}();
					} else {
						console::debug_error( 'Попытка установить позицию для абстрактной локации', $position );
					}
				}
				$locations = locations::get_locations_by_contextLocation( $context_location );
				$R = [];
				foreach( $locations as $location ){
					if( $location->_get_parent_field() instanceof field ){
						$R[ $location->_get_parent_field()->id() ] = $location->_get_parent_field();
					}
				}
			}
			return $R;
		}


		static function edit_form_top( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post );
		}


		static function edit_form_before_permalink( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post );
		}


		static function edit_form_after_title( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post, 'post-box' );
		}


		static function edit_form_after_editor( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post );
		}


		static function submitpost_box( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post );
		}


		static function edit_form_advanced( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post );
		}


		static function dbx_post_sidebar( $post ){
			forms::the_form_by_fields( self::get_post_type_context_fields( $post, __FUNCTION__ ), $post );
		}


		//Post Type Meta Boxes
		static function add_meta_boxes( $post_type = null, $post = null ){
			$fields = self::get_post_type_context_fields( $post, 'meta_box' );
			$fields_by_box = [];
			foreach( $fields as $field ){
				$meta_context = $field->LOCATION()->POST_TYPES()->options['meta_box']['context'];
				$meta_priority = $field->LOCATION()->POST_TYPES()->options['meta_box']['priority'];
				$meta_title = $field->LOCATION()->POST_TYPES()->options['meta_box']['title'];
				$fields_by_box[ $meta_context ][ $meta_priority ][ $meta_title ][] = $field;
			}
			//
			$meta_box_ids = [];
			foreach( $fields_by_box as $meta_context => $fields_by_priority ){
				foreach( $fields_by_priority as $meta_priority => $fields_by_title ){
					foreach( $fields_by_title as $meta_title => $fields_by_box ){
						$meta_box_id = 'hiweb-metabox-' . $meta_context . '-' . $meta_priority . '-' . strings::sanitize_id( $meta_title );
						if( array_key_exists( $meta_box_id, array_flip( $meta_box_ids ) ) ){
							for( $n = 0; $n < 999; $n ++ ){
								if( !array_key_exists( $meta_box_id . '-' . $n, array_flip( $meta_box_ids ) ) ){
									$meta_box_id .= '-' . $n;
									break;
								}
							}
						}
						add_meta_box( $meta_box_id, $meta_title, function( $post, $meta_box_args ){
							forms::the_form_by_fields( $meta_box_args['args'], $post, 'meta-box-post' );
						}, $post_type, $meta_context, $meta_priority, $fields_by_box );
					}
				}
			}
		}


		//COLUMNS POST TYPE MANAGE

		static function manage_posts_custom_column( $columns_name, $post_id ){
			if( strpos( $columns_name, 'hiweb-field-' ) === 0 ){
				$field_id = substr( $columns_name, strlen( 'hiweb-field-' ) );
				$field = fields::get( $field_id );
				if( array_key_exists( 'COLUMNS_MANAGER', $field->LOCATION()->_get_options_by_type( 'post_types' ) ) ){
					$column_manager_options = $field->LOCATION()->_get_options_by_type( 'post_types' )['COLUMNS_MANAGER'];
					if( isset( $column_manager_options['callback'] ) && is_callable( $column_manager_options['callback'] ) ){
						call_user_func( $column_manager_options['callback'], $post_id, $field );
					} else {
						echo $field->CONTEXT( get_post( $post_id ) )->VALUE()->get_sanitized();
					}
				}
			}
		}


		static function manage_posts_columns( $posts_columns, $post_type = 'page' ){
			$context_locations = locations::get_locations_by_options( [
				'post_types' => [
					'post_type' => $post_type
				]
			] );
			/** @var location $location */
			foreach( $context_locations as $location ){
				if( array_key_exists( 'COLUMNS_MANAGER', $location->_get_options_by_type( 'post_types' ) ) ){
					//					foreach( locations::get_fields_by_contextLocation( $location ) as $field_id => $field ){
					//						$column_manager_options = $location->_get_options_by_type( 'post_types' )['COLUMNS_MANAGER'];
					//						$posts_columns = arrays::push( $posts_columns, $column_manager_options['name'], $column_manager_options['position'], 'hiweb-field-' . $field->id() );
					//					}
					$column_manager_options = $location->_get_options_by_type( 'post_types' )['COLUMNS_MANAGER'];
					$posts_columns = arrays::push( $posts_columns, $column_manager_options['name'], $column_manager_options['position'], 'hiweb-field-' . $location->_get_parent_field()->id() );
				}
			}
			return $posts_columns;
		}


		/**
		 * @param $sortable_columns
		 * @return array
		 */
		static function manage_posts_sortable_columns( $sortable_columns ){
			$context_locations = locations::get_locations_by_options( [
				'post_types' => [
					'post_type' => get_current_screen()->post_type
				]
			] );
			foreach( $context_locations as $location ){
				$location_options = $location->_get_options_by_type( 'post_types' );
				if( array_key_exists( 'COLUMNS_MANAGER', $location_options ) && array_key_exists( 'sortable', $location_options['COLUMNS_MANAGER'] ) && $location_options['COLUMNS_MANAGER']['sortable'] ){
					foreach( locations::get_fields_by_contextLocation( $location ) as $field_id => $field ){
						$sortable_columns[ 'hiweb-field-' . $field->id() ] = 'hiweb-field-' . $field->id();
					}
				}
			}
			return $sortable_columns;
		}


		///SAVE POST

		static function save_post( $post_id, $post, $update ){
			$location = locations::get_abstractLocation_from_contextObject( $post );
			unset( $location->POST_TYPES()->options['position'] );
			$fields = locations::get_fields_by_contextLocation( $location );
			foreach( $fields as $field_id => $field ){
				if( isset( $_POST[ $field->INPUT()->name() ] ) ){
					update_post_meta( $post_id, $field->id(), $_POST[ $field->INPUT()->name() ] );
				} else {
					//Не удалять мета-данные, если они не передаются
				}
			}
		}


		static function taxonomy_add_form_fields( $taxonomy ){
			$context_location = new location();
			$context_location->TAXONOMIES( $taxonomy );
			$fields = locations::get_fields_by_contextLocation( $context_location );
			foreach( $fields as $field ){
				if( trim( $field->template() ) == 'default' )
					$field->template( 'term-add' );
			}
			forms::the_form_by_fields( $fields );
		}


		static function taxonomy_edit_form( $term, $taxonomy ){
			$context_location = locations::get_abstractLocation_from_contextObject( $term );
			$fields = locations::get_fields_by_contextLocation( $context_location );
			forms::the_form_by_fields( $fields, $term, 'term-edit' );
		}


		static function taxonomy_edited_term( $term_id, $tt_id, $taxonomy ){
			$term = get_term_by( 'id', $term_id, $taxonomy );
			if( $term instanceof \WP_Term ){
				$location = locations::get_abstractLocation_from_contextObject( $term );
				$fields = locations::get_fields_by_contextLocation( $location );
				foreach( $fields as $field_id => $field ){
					if( isset( $_POST[ $field->INPUT()->name() ] ) ){
						update_term_meta( $term_id, $field->id(), $_POST[ $field->INPUT()->name() ] );
					} else {
						update_term_meta( $term_id, $field->id(), '' );
					}
				}
			}
		}


		///USERS


		/**
		 * @param \WP_User $wp_user
		 * @param null     $position
		 * @return array|field[]
		 */
		static private function get_users_fields( $wp_user, $position = null ){
			if( $wp_user instanceof \WP_User ){
				$context_location = locations::get_abstractLocation_from_contextObject( $wp_user );
				$context_location->USERS()->position( $position );
				return locations::get_fields_by_contextLocation( $context_location );
			}
			return [];
		}


		static function user_new_form(){
			$contextLocation = new location();
			$contextLocation->USERS();
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
				unset( $location->USERS()->options['position'] );
				$fields = locations::get_fields_by_contextLocation( $location );
				foreach( $fields as $field_id => $field ){
					update_user_meta( $user_id, $field->id(), $_POST[ $field->INPUT()->name() ] );
				}
			}
		}


		static function customize_register( \WP_Customize_Manager $wp_customize ){
			///
			/** @var location[] $theme_locations */
			$theme_locations = [];
			if( is_array( locations::$locations ) )
				foreach( locations::$locations as $location_global_id => $location ){
					if( isset( $location->options['hiweb_theme'] ) ){
						$theme_locations[ $location_global_id ] = $location;
					}
				}
			///
			if( !is_array( $theme_locations ) || count( $theme_locations ) == 0 )
				return;
			///
			$sections = [];
			foreach( $theme_locations as $location_id => $location ){
				$options = $location->options['hiweb_theme']->options;
				$section_id = \hiweb\strings::sanitize_id( $options['section_title'] );
				if( !isset( $sections[ $section_id ] ) )
					$sections[ $section_id ]['args'] = [ 'capability' => 'edit_theme_options' ];
				if( !isset( $sections[ $section_id ]['args']['title'] ) )
					$sections[ $section_id ]['args']['title'] = $options['section_title'];
				if( !isset( $sections[ $section_id ]['args']['description'] ) )
					$sections[ $section_id ]['args']['description'] = $options['section_description'];
				$sections[ $section_id ]['fields'][] = $location->_get_parent_field();
			}
			/// ADD SECTIONS and SETTING FIELDS
			foreach( $sections as $section_id => $section ){
				$wp_customize->add_section( $section_id, $section['args'] );
				if( is_array( $section['fields'] ) && count( $section['fields'] ) > 0 )
					foreach( $section['fields'] as $field ){
						/** @var field $field */
						$wp_customize->add_setting( $field->id(), [ 'default' => '', 'type' => 'theme_mod' ] );
						$wp_customize->add_control( $field->INPUT()->THEME_CONTROL( $wp_customize, $section_id ) );
					}
			}
		}

	}