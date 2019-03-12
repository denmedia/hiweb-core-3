<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:44
	 */

	namespace hiweb;


	use hiweb\paths\path;


	class paths{

		/** @var path[] */
		static private $paths = [];
		static private $root;


		/**
		 * @param string $path
		 * @return path
		 */
		static function get( $path = '' ){
			$path = str_replace( '\\', '/', (string)$path );
			if( trim( $path, '/' ) == '' ) $path = self::root();
			///
			if( !array_key_exists( $path, self::$paths ) ){
				self::$paths[ $path ] = new path( $path );
			}
			return self::$paths[ $path ];
		}


		/**
		 * Возвращает корневую папку сайта. Данная функция автоматически определяет корневую папку сайта, отталкиваясь на поиске папок с файлом index.php
		 * @return string
		 * @version 1.5
		 */
		static function root(){
			if( !is_string( self::$root ) ){
				self::$root = '';
				$patch = explode( '/', trim( __DIR__ ) );
				$patches = [];
				$last_path = '';
				foreach( $patch as $dir ){
					if( $dir == '' ){
						continue;
					}
					$last_path .= '/' . $dir;
					$patches[] = $last_path;
				}
				$patches = array_reverse( $patches );
				foreach( $patches as $path ){
					$check_file = $path . '/wp-config.php';
					if( file_exists( $check_file ) && is_file( $check_file ) ){
						self::$root = $path;
						break;
					}
				}
			}
			return self::$root;
		}


		/**
		 * @param int|string $size
		 * @return string
		 */
		static function get_size_formatted($size){
			$size = intval( $size );
			if( $size < 1024 ){
				return $size . ' B';
			} elseif( $size < 1048576 ) {
				return round( $size / 1024, 2 ) . ' КБ';
			} elseif( $size < 1073741824 ) {
				return round( $size / 1048576, 2 ) . ' МБ';
			} elseif( $size < 1099511627776 ) {
				return round( $size / 1073741824, 2 ) . ' ГБ';
			} elseif( $size < 1125899906842624 ) {
				return round( $size / 1099511627776, 2 ) . ' ТБ';
			} elseif( $size < 1152921504606846976 ) {
				return round( $size / 1125899906842624, 2 ) . ' ПБ';
			} elseif( $size < 1180591620717411303424 ) {
				return round( $size / 1152921504606846976, 2 ) . ' ЭБ';
			} elseif( $size < 1208925819614629174706176 ) {
				return round( $size / 1180591620717411303424, 2 ) . ' ЗБ';
			} else {
				return round( $size / 1208925819614629174706176, 2 ) . ' YiB';
			}
		}

		static function file_extension($file_name){
			$pathInfo = pathinfo( $file_name );

			return isset( $pathInfo['extension'] ) ? $pathInfo['extension'] : '';
		}

	}