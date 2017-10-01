<?php

	namespace hiweb;


	/**
	 * @param       $jsFilePath
	 * @param array $afterJs
	 * @param bool  $inFooter
	 * @return bool
	 */
	function js( $jsFilePath, $afterJs = [], $inFooter = false ){
		return js::enqueue( $jsFilePath, $afterJs, $inFooter );
	}


	class js{

		static $enqueue = [];
		static $enqueue_frontend = [];
		static $enqueue_backend = [];


		/**
		 * Поставить в очередь файл JS
		 * @version  1.3
		 * @param string $filePathOrUrl
		 * @param array  $afterJS
		 * @param bool   $in_footer
		 * @return bool
		 */
		static function enqueue( $filePathOrUrl, $afterJS = [], $in_footer = false ){
			$file = \hiweb\file( $filePathOrUrl );
			if( !$file->is_readable ){
				console::error( 'файл [' . $file . '] не найден!', 2 );
				return false;
			} else {
				$id = md5( $file->url );
				self::$enqueue[ $id ] = [ $file, $afterJS, $in_footer ];
				return $id;
			}
		}


		static function wp_register_script(){
			foreach( self::$enqueue as $slug => $script ){
				unset( self::$enqueue[ $slug ] );
				wp_register_script( $slug, $script[0], $script[1], $script[0] == $script[3] ? 0 : filemtime( $script[3] ), $script[2] );
				wp_enqueue_script( $slug );
			}
		}


	}