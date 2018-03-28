<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 13.03.2018
	 * Time: 13:26
	 */

	namespace hiweb;


	class cache{

		static $option_name = 'hiweb_cache';
		static $life_time = 84600;


		/**
		 * @param $key
		 * @param null $value
		 * @return bool|null
		 */
		static function set_cache( $key, $value = null ){
			$R = self::get_cache();
			if( !is_string( $key ) ){
				console_warn( 'Попытука установить кэш без ключа' );
				return null;
			}
			$R[ $key ] = [ $value, microtime( true ) ];
			return update_option( self::$option_name, $R, true );
		}


		/**
		 * @param null $key
		 * @param null $default
		 * @return array
		 */
		static function get_cache( $key = null, $default = null ){
			$R = get_option( self::$option_name, [] );
			$R = is_array( $R ) ? $R : [];
			if( !is_string( $key ) ){
				return $R;
			} else {
				return array_key_exists( $key, $R ) ? $R[ $key ][0] : $default;
			}
		}


		/**
		 * @param $key
		 * @param bool $use_alive
		 * @return bool|null
		 */
		static function get_cache_exists( $key, $use_alive = true ){
			if( !is_string( $key ) ){
				console_warn( 'Попытука получить кэш без ключа' );
				return null;
			}
			$cache = self::get_cache();
			$exists = array_key_exists( $key, $cache );
			if( array_key_exists( $key, $cache ) && intval( $cache[ $key ][1] ) == 0 ) return false;
			return ( !$exists || !$use_alive ) ? $exists : ( microtime( true ) - intval( $cache[ $key ][1] ) < intval( self::$life_time ) );
		}


		/**
		 * @param null $key
		 * @return bool
		 */
		static function clear_cache( $key = null ){
			if( !is_string( $key ) ){
				$R = [];
			} else {
				$R = self::get_cache();
				unset( $R[ $key ] );
			}
			return update_option( self::$option_name, $R, true );
		}

	}