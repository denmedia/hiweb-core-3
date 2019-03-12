<?php

	namespace hiweb;


	use hiweb\files\file;
	use hiweb\images\editor;
	use hiweb\images\image;
	use hiweb\paths\path;


	class images{

		/** @var images\image[] */
		static private $images = [];
		static private $editors = [];
		/** @var null|file */
		static private $default_image_file = null;
		/** @var path */
		static private $upload_dirs = [];

		static $original_size_name = 'original';

		static $mime_default_priority = [ 'png', 'gif', 'jpg', 'jpe', 'jpeg'/*, 'webp', 'jp2', 'jxr'*/ ];
		static $classic_file_types = [ 'png', 'jpg', 'jpeg', 'jpe', 'gif' ];
		static $progressive_types = [ 'jxr', 'webp', 'jp2' ];
		static $progressive_create_on_upload = false;
		static $meta_key_optimized = 'hiweb-optimized';
		static $extension_priority = [ 'png', 'gif', 'jpg', 'jpe', 'jpeg',/*'webp', 'jxr', 'jp2'*/ ];
		static $default_quality = 75;


		/**
		 * @param int $attachIdPathOrUrl
		 * @version 1.2
		 * @return image
		 */
		static public function get( $attachIdPathOrUrl ){
			if( is_string( $attachIdPathOrUrl ) && !is_numeric( $attachIdPathOrUrl ) ){
				$attachIdPathOrUrl = paths::get( $attachIdPathOrUrl )->get_url( false );
				global $wpdb;
				$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $attachIdPathOrUrl ) );
				$attachIdPathOrUrl = $attachment[0];
			} elseif( $attachIdPathOrUrl instanceof \WP_Post && $attachIdPathOrUrl->post_type == 'attachment' ) {
				$attachIdPathOrUrl = $attachIdPathOrUrl->ID;
			}
			///
			if( !isset( self::$images[ $attachIdPathOrUrl ] ) ){
				require_once __DIR__ . '/-image.php';
				self::$images[ $attachIdPathOrUrl ] = new image( $attachIdPathOrUrl );
			}
			return self::$images[ $attachIdPathOrUrl ];
		}


		/**
		 * @param $pathOrUrl
		 * @return editor
		 */
		static public function get_editor( $pathOrUrl ){
			if( !array_key_exists( $pathOrUrl, self::$editors ) ){
				require_once __DIR__ . '/-editor.php';
				self::$editors[ $pathOrUrl ] = new editor( $pathOrUrl );
			}
			return self::$editors[ $pathOrUrl ];
		}


		/**
		 * Set default image url/path
		 * @param strings $urlOrPathOrAttachID
		 * @return bool
		 */
		static public function set_default_src( $urlOrPathOrAttachID ){
			if( !is_string( $urlOrPathOrAttachID ) ){
				//
			} else {
				$file = files::get( $urlOrPathOrAttachID );
				if( $file->is_readable() ){
					self::$default_image_file = $file;
					return true;
				} else {
					//
				}
			}
			return false;
		}


		/**
		 * @param bool $force_hiweb_default
		 * @return bool|strings
		 */
		static public function get_default_src( $force_hiweb_default = false ){
			$default_hiweb_src = HIWEB_URL_ASSETS . '/img/noimg.svg';
			return ( !$force_hiweb_default && self::$default_image_file instanceof file ) ? self::$default_image_file->get_url() : $default_hiweb_src;
		}


		/**
		 * @return path
		 */
		static function get_upload_dirs(){
			if( !self::$upload_dirs[ get_current_blog_id() ] instanceof path ){
				if( function_exists( 'wp_get_upload_dir' ) ){
					self::$upload_dirs[ get_current_blog_id() ] = paths::get( arrays::get_temp( wp_get_upload_dir() )->value_by_key( 'basedir' ) );
				} else {
					self::$upload_dirs[ get_current_blog_id() ] = paths::get( WP_CONTENT_DIR . '/uploads' );
				}
			}
			return self::$upload_dirs[ get_current_blog_id() ];
		}


		/**
		 * @return path
		 */
		static function get_upload_path_dirs(){
			if( !self::$upload_dirs[ get_current_blog_id() ] instanceof path ){
				if( function_exists( 'wp_get_upload_dir' ) ){
					self::$upload_dirs[ get_current_blog_id() ] = paths::get( arrays::get_temp( wp_get_upload_dir() )->value_by_key( 'path' ) );
				} else {
					self::$upload_dirs[ get_current_blog_id() ] = paths::get( WP_CONTENT_DIR . '/uploads/'.date('Y', time()).'/'.date('m', time()) );
				}
			}
			return self::$upload_dirs[ get_current_blog_id() ];
		}


		/**
		 * @param $extension
		 * @return bool
		 */
		static function is_extension_classic( $extension ){
			return array_key_exists( $extension, array_flip( self::$classic_file_types ) );
		}


		/**
		 * @param $extension
		 * @return bool
		 */
		static function is_extension_progressive( $extension ){
			return array_key_exists( $extension, array_flip( self::$progressive_types ) );
		}


	}