<?php

	namespace hiweb\fields\locations;


	use hiweb\console;
	use hiweb\dump;
	use hiweb\fields\field;


	class backend{

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
				$context_location = new location();
				$context_options = $context_location->post_types();
				$context_options->post_name( $post->post_name );
				$context_options->post_type( $post->post_type );
				$context_options->front_page( get_option( 'page_on_front' ) == $post->ID );
				if( !is_null( $position ) ) $context_options->position( $position );
				$context_options->comment_status( $post->comment_status );
				$context_options->has_taxonomy( get_object_taxonomies( $post, $output = 'names' ) );
				$context_options->ID( $post->ID );
				$context_options->post_parent( $post->post_parent );
				$context_options->post_status( $post->post_status );
				$locations = locations::get_locations_by_context( $context_location );
				$R = [];
				foreach( $locations as $location_id => $location ){
					foreach( $location->get_fields() as $field_id => $field ){
						$field->type()->value = get_post_meta( $post->ID, $field_id, true );
						$R[ $field_id ] = $field;
					}
				}
			}
			return $R;
		}


		static function edit_form_top( $post ){
			$fields = self::get_post_type_context_fields( $post, 0 );
			include __DIR__ . '/views-backend/default.php';
		}


		static function edit_form_before_permalink( $post ){
			$fields = self::get_post_type_context_fields( $post, 1 );
			include __DIR__ . '/views-backend/default.php';
		}


		static function edit_form_after_title( $post ){
			$fields = self::get_post_type_context_fields( $post, 2 );
			include __DIR__ . '/views-backend/default.php';
		}


		static function edit_form_after_editor( $post ){
			$fields = self::get_post_type_context_fields( $post, 3 );
			include __DIR__ . '/views-backend/default.php';
		}


		static function submitpost_box( $post ){
			$fields = self::get_post_type_context_fields( $post, 4 );
			include __DIR__ . '/views-backend/default.php';
		}


		static function edit_form_advanced( $post ){
			$fields = self::get_post_type_context_fields( $post, 5 );
			include __DIR__ . '/views-backend/default.php';
		}


		static function dbx_post_sidebar( $post ){
			$fields = self::get_post_type_context_fields( $post, 6 );
			include __DIR__ . '/views-backend/default.php';
		}


		//COLUMNS POST TYPE MANAGE

		static function manage_posts_custom_column( $columns_name, $post_id ){
			//todo
		}


		static function manage_posts_columns( $posts_columns, $post_type = 'page' ){
			//todo
		}


		static function save_post( $post_id, $post, $update ){
			$fields = self::get_post_type_context_fields( $post );
			foreach( $fields as $field_id => $field ){
				$input_name = \hiweb\fields\get_field_input_name( $field_id );
				update_post_meta( $post_id, $field_id, \hiweb\path\request( $input_name ) );
			}
		}

	}