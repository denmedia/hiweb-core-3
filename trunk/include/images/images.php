<?php

	namespace hiweb;


	use hiweb\files\file;
	use hiweb\images\image;


	class images{

		/** @var images\image[] */
		static private $images = [];
		/** @var null|file */
		static private $default_image_file = null;


		/**
		 * @param int $attach_id
		 *
		 * @return image
		 */
		static public function get( $attach_id ){
			if( !isset( self::$images[ $attach_id ] ) ){
				self::$images[ $attach_id ] = new image( $attach_id );
			}
			return self::$images[ $attach_id ];
		}


		/**
		 * Set default image url/path
		 *
		 * @param strings $urlOrPath
		 *
		 * @return bool
		 */
		static public function set_default_src( $urlOrPath ){
			if( !is_string( $urlOrPath ) ){
				//
			} else {
				$file = \hiweb\files::get( $urlOrPath );
				if( $file->is_exists_and_readable() ){
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
		 *
		 * @return bool|strings
		 */
		static public function get_default_src( $force_hiweb_default = false ){
			$default_hiweb_src = HIWEB_URL_ASSETS . '/img/noimg.png';
			return ( !$force_hiweb_default && self::$default_image_file instanceof file ) ? self::$default_image_file->url : $default_hiweb_src;
		}

	}