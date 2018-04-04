<?php

	namespace hiweb;


	use hiweb\post_types\post_type;


	class post_types{

		/** @var array */
		static $post_types = [];


		/**
		 * Возвращает корневой CPT класс для работы с кастомным типом поста
		 * @param $post_type
		 * @return post_types\post_type
		 */
		static function register( $post_type ){
			$post_type_sanitized = sanitize_file_name( strtolower( $post_type ) );
			if( !array_key_exists( $post_type_sanitized, self::$post_types ) ){
				self::$post_types[ $post_type_sanitized ] = new post_types\post_type( $post_type );
			}
			return self::$post_types[ $post_type_sanitized ];
		}


		static function do_register_post_types(){
			/**
			 * @var strings    $post_type_name
			 * @var post_type $post_type
			 */
			foreach( self::$post_types as $post_type_name => $post_type ){
				if( !$post_type instanceof post_type ){
					console::debug_error( 'В массиве типов постов попался посторонний объект', [ $post_type_name, $post_type ] );
					continue;
				}
				if( post_type_exists( $post_type_name ) ){
					$wp_post_type = get_post_type_object( $post_type_name );
					foreach( $post_type->get_args_custom() as $key => $value ){
						if( property_exists( $wp_post_type, $key ) ){
							if( is_array( $wp_post_type->{$key} ) ){
								$wp_post_type->{$key} = array_merge( $wp_post_type->{$key}, is_array( $value ) ? $value : [ $value ] );
							} else $wp_post_type->{$key} = $value;
						}
					}
					$post_type->wp_post_type = $wp_post_type;
				} else {
					$wp_post_type = register_post_type( $post_type_name, $post_type->get_args_custom() );
					if( $wp_post_type instanceof \WP_Post_Type ){
						$post_type->wp_post_type = $wp_post_type;
					} else {
						console::debug_error( 'Во время регистрации типа поста произошла ошибка', $wp_post_type );
					}
				}
			}
		}

	}
