<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 03/12/2018
	 * Time: 22:10
	 */

	namespace hiweb;


	use hiweb\urls\url;


	/**
	 * Класс-менеджер URL адресов
	 * Class urls
	 * @package hiweb
	 */
	class urls{

		/** @var url[] */
		private static $urls = [];
		/** @var string */
		private static $current_url;
		static $use_noscheme_urls = true;


		/**
		 * Возвращает текущий адрес URL
		 * @version 1.0.2
		 * @param bool $trimSlashes
		 * @return string
		 */
		static private function get_current_url( $trimSlashes = true ){
			if( !is_string( self::$current_url ) ){
				$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
				self::$current_url = rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], '/' ) . ( $trimSlashes ? rtrim( $_SERVER['REQUEST_URI'], '/\\' ) : $_SERVER['REQUEST_URI'] );
			}
			return self::$current_url;
		}


		static function set_current_url( $url ){
			self::$current_url = $url;
		}


		/**
		 * @param null $url
		 * @return url
		 */
		static function get( $url = null ){
			if( is_null( $url ) || (string)$url == '' ) $url = self::get_current_url();
			if( !array_key_exists( $url, self::$urls ) ){
				self::$urls[ $url ] = new url( $url );
			}
			return self::$urls[ $url ];
		}


		/**
		 * Test string to url
		 * @param string $test_url_string
		 * @return bool
		 */
		static function is_url($test_url_string){
			return is_string( $test_url_string ) && ( strpos( $test_url_string, '//' ) === 0 || filter_var( $test_url_string, FILTER_VALIDATE_URL ) );
		}


		/**
		 * Возвращает корневой URL
		 * @param null|bool $use_noscheme
		 * @return string
		 * @version 1.0
		 */
		static function root($use_noscheme = null){
			return self::get()->root($use_noscheme);
		}


		/**
		 * Возвращает запрошенный GET или POST параметр
		 * @param       $key
		 * @param mixed $default
		 * @return mixed
		 */
		static function request( $key, $default = null ){
			$R = $default;
			if( array_key_exists( $key, $_GET ) ){
				$R = $_GET[ $key ];
			}
			if( array_key_exists( $key, $_POST ) ){
				$R = is_string( $_POST[ $key ] ) ? stripslashes( $_POST[ $key ] ) : $_POST[ $key ];
			}

			return $R;
		}

	}