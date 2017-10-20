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
			//TODO
			console::debug_info( 'Register post types...' );
			/**
			 * @var string    $post_type_name
			 * @var post_type $post_type
			 */
			foreach( self::$post_types as $post_type_name => $post_type ){
				register_post_type($post_type_name, $post_type->get_args());
			}
		}

	}
