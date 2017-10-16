<?php

	namespace hiweb\fields\locations;


	use hiweb\console;
	use hiweb\dump;
	use hiweb\fields\field;
	use hiweb\fields\locations;


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
				$context_location = \hiweb\fields\functions\get_newLocation_from_contextObject( $post );
				if( !is_null( $position ) ) $context_location->post_types()->position( $position );
				$locations = locations::get_locations_by_context( $context_location );
				$R = [];
				foreach( $locations as $location_id => $location ){
					if( $location->get_field() instanceof field ){
						$R[ $location->get_field()->id() ] = $location->get_field();
					}
				}
			}
			return $R;
		}


		static function edit_form_top( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 0 ), $post );
		}


		static function edit_form_before_permalink( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 1 ), $post );
		}


		static function edit_form_after_title( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 2 ), $post );
		}


		static function edit_form_after_editor( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 3 ), $post );
		}


		static function submitpost_box( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 4 ), $post );
		}


		static function edit_form_advanced( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 5 ), $post );
		}


		static function dbx_post_sidebar( $post ){
			\hiweb\fields\functions\the_form_fields( self::get_post_type_context_fields( $post, 6 ), $post );
		}


		//COLUMNS POST TYPE MANAGE

		static function manage_posts_custom_column( $columns_name, $post_id ){
			//todo
		}


		static function manage_posts_columns( $posts_columns, $post_type = 'page' ){
			//todo
		}

//TODO!!!
		static function save_post( $post_id, $post, $update ){
			$fields = locations::get_fields_by_contextObject( $post );
			foreach( $fields as $field_id => $field ){
				update_post_meta( $post_id, $field->id(), \hiweb\path\request( $field->admin_input_name() ) );
				dump::to_file( [ $post_id, $field->id(), $field->admin_input_name(), \hiweb\path\request( $field->admin_input_name() ) ] );
			}
		}

	}