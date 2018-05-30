<?php

	namespace hiweb;



	class context{

		/**
		 * @version 1.1
		 * @return bool
		 */
		static function is_frontend_page(){
			return ( $_SERVER['SCRIPT_FILENAME'] == path::base_dir() . '/index.php' ) && !self::is_rest_api();
		}


		/**
		 * @param null|string|int|\WP_Post $postOrId
		 * @return bool
		 */
		static function is_front_page( $postOrId = null ){
			if( !is_null( $postOrId ) ){
				if( is_numeric( $postOrId ) && false ){
					return intval( $postOrId ) == get_option( 'page_on_front' );
				} elseif( is_string( $postOrId ) ) {
					$args = [
						'post_name' => $postOrId,
						'post_status' => 'publish',
						'post_per-Page' => 1
					];
					$my_posts = get_posts( $args );
					if( is_array( $my_posts ) && count( $my_posts ) > 0 ){
						return reset( $my_posts )->ID == get_option( 'page_on_front' );
					}
					return false;
				}
			}
			return \is_front_page();
		}


		/**
		 * @return bool
		 */
		static function is_admin_page(){
			return \is_admin() && !self::is_ajax() && !self::is_rest_api();
		}


		/**
		 * @return bool
		 */
		static function is_login_page(){
			return array_key_exists( $GLOBALS['pagenow'], array_flip( [
				'wp-login.php',
				'wp-register.php'
			] ) );
		}


		/**
		 * @return bool
		 */
		static function is_ajax(){
			return ( defined( 'DOING_AJAX' ) && DOING_AJAX );
		}


		/**
		 * @return bool
		 */
		static function is_rest_api(){
			$dirs = path::get_url_info()['dirs_arr'];
			return reset( $dirs ) == 'wp-json';
		}


		/**
		 * Return TRUE, if context is CRON (request url domain.com/wp-cron.php)
		 * @return bool
		 */
		static function is_cron(){
			return defined( 'DOING_CRON' ) && DOING_CRON;
		}


		static function get_current_page(){
			return null;
			//return $this->get_current_page();
		}
	}