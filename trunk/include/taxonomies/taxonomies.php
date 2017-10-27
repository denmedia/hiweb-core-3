<?php

	namespace hiweb;


	use hiweb\taxonomies\taxonomy;


	class taxonomies{

		/** @var taxonomy[] */
		static private $taxonomies = [];


		/**
		 * @param $taxonomy_name
		 * @param string|array $object_type - post type / post types
		 * @return taxonomy
		 */
		static function register( $taxonomy_name, $object_type ){
			$taxonomy_name_sanitized = sanitize_file_name( strtolower( $taxonomy_name ) );
			if( !array_key_exists( $taxonomy_name_sanitized, self::$taxonomies ) ){
				self::$taxonomies[ $taxonomy_name_sanitized ] = new taxonomy( $taxonomy_name_sanitized, $object_type );
			}
			return self::$taxonomies[ $taxonomy_name_sanitized ];
		}


		static function do_register_taxonomies(){
			/**
			 * @var string $taxonomy_name
			 * @var taxonomy $taxonomy
			 */
			foreach( self::$taxonomies as $taxonomy_name => $taxonomy ){
				if( !$taxonomy instanceof taxonomy ){
					console::debug_error( 'В массиве типов постов попался посторонний объект', [ $taxonomy_name, $taxonomy ] );
					continue;
				}
				if( post_type_exists( $taxonomy_name ) ){
					$wp_taxonomy = get_taxonomy( $taxonomy_name );
					foreach( $taxonomy->get_args() as $key => $value ){
						if( property_exists( $wp_taxonomy, $key ) ){
							if( is_array( $wp_taxonomy->{$key} ) ){
								$wp_taxonomy->{$key} = array_merge( $wp_taxonomy->{$key}, is_array( $value ) ? $value : [ $value ] );
							} else $wp_taxonomy->{$key} = $value;
						}
					}
					$taxonomy->wp_taxonomy = $wp_taxonomy;
				} else {
					$wp_taxonomy = register_taxonomy( $taxonomy_name, [], $taxonomy->get_args() );
					if( $wp_taxonomy instanceof \WP_Taxonomy ){
						$taxonomy->wp_post_type = $wp_taxonomy;
					} else {
						console::debug_error( 'Во время регистрации типа поста произошла ошибка', $wp_taxonomy );
					}
				}
			}
		}

	}