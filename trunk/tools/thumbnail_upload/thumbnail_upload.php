<?php

	namespace hiweb\tools;


	use hiweb\tools\thumbnail_upload\post_type;


	class thumbnail_upload{

		private static $post_types = [];


		private static function include_scripts(){
			\hiweb\css( HIWEB_URL_ASSETS . '/css/tools-thumbnail-upload.css' );
			\hiweb\js( HIWEB_URL_ASSETS . '/js/tools-thumbnail-upload.js' );
			\hiweb\js( HIWEB_URL_VENDORS . '/dropzone.js' );
		}


		/**
		 * @param $post_type
		 */
		static function post_type( $post_type ){
			include_once 'post_type.php';
			self::include_scripts();
			if( !isset( self::$post_types[ $post_type ] ) ) self::$post_types[ $post_type ] = new post_type( $post_type );
		}


	}