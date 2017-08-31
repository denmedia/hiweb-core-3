<?php

	namespace hiweb;


	class post_types{

		/** @var array */
		static private $types = [];


		/**
		 * Возвращает корневой CPT класс для работы с кастомным типом поста
		 * @param $post_type
		 * @return post_types\post_type
		 */
		static function register( $post_type ){
			$post_type_sanit = sanitize_file_name( strtolower( $post_type ) );
			if( !array_key_exists( $post_type_sanit, self::$types ) ){
				self::$types[ $post_type_sanit ] = new post_types\post_type( $post_type );
			}
			return self::$types[ $post_type_sanit ];
		}

	}