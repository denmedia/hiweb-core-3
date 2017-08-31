<?php

	namespace hiweb;


	/**
	 * @param $cssFilePath
	 * @return bool
	 */
	function css( $cssFilePath ){
		return css::enqueue( $cssFilePath );
	}


	class css{

		static private $files = [];


		//		static function __construct(){
		//			add_action( 'wp_enqueue_scripts', [
		//				$this,
		//				'_my_wp_enqueue_scripts'
		//			] );
		//			add_action( 'admin_enqueue_scripts', [
		//				$this,
		//				'_my_wp_enqueue_scripts'
		//			] );
		//			add_action( 'login_enqueue_scripts', [
		//				$this,
		//				'_my_wp_enqueue_scripts'
		//			] );
		//			add_action( 'wp_footer', [
		//				$this,
		//				'_my_wp_enqueue_scripts'
		//			] );
		//			add_action( 'admin_footer', [
		//				$this,
		//				'_my_wp_enqueue_scripts'
		//			] );
		//		}

		/**
		 * Поставить в очередь файл CSS
		 * @version 1.3
		 * @param      $file
		 * @param bool $in_footer
		 * @return bool
		 */
		static function enqueue( $file, $in_footer = false ){ //todo: in_footer
			if( strpos( $file, '/' ) === 0 ){
				$backtrace = debug_backtrace();
				if( strpos( $file, hiweb()->path()->base_dir() ) !== 0 ){
					$sourceDir = dirname( $backtrace[1]['file'] );
					$file = $sourceDir . $file;
				}
			}
			$url = hiweb()->path()->path_to_url( $file );
			$file = hiweb()->path()->url_to_path( $file );
			if( ( $url == $file ) || ( file_exists( $file ) && is_file( $file ) && is_readable( $file ) && $url != '' ) ){
				$id = md5( $url );
				self::$files[ $id ] = [
					$url,
					$file
				];

				return $id;
			} else {
				console::error( sprintf( __( 'File [%s] not found or not readable' ), $file ), 2 );

				return false;
			}
		}


		function _my_wp_enqueue_scripts(){
			foreach( self::$files as $slug => $path ){
				unset( self::$files[ $slug ] );
				wp_register_style( $slug, $path[0], [], $path[0] == $path[1] ? 0 : filemtime( $path[1] ) );
				wp_enqueue_style( $slug );
			}
		}


	}