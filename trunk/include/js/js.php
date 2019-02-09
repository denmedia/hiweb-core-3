<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05/12/2018
	 * Time: 00:00
	 */

	namespace hiweb;


	use hiweb\js\file;


	/**
	 * Class js
	 * @package hiweb\js
	 */
	class js{

		/** @var file[] */
		private static $queue = [];
		/** @var file[] */
		private static $registred = [];
		/**
		 * @var array
		 */
		private static $past_line = [];

		/**
		 * @var array
		 */
		private static $handles = [];


		/**
		 * @param string $pathOrHandle
		 * @return mixed
		 */
		static public function get_handle( $pathOrHandle = 'jquery-core' ){
			if( !array_key_exists( $pathOrHandle, self::$handles ) ){
				global $wp_scripts;
				if( is_array( $wp_scripts->registered ) && array_key_exists( $pathOrHandle, $wp_scripts->registered ) ){
					self::$handles[ $pathOrHandle ] = $pathOrHandle;//$wp_scripts->registeredх[ $pathOrHandle ]->src;
				} else {
					$path = paths::get( $pathOrHandle );
					self::$handles[ $pathOrHandle ] = $path->handle();
					if( is_array( $wp_scripts->registered ) )
						foreach( $wp_scripts->registered as $handle => $file_data ){
							$test_path = paths::get( $file_data->src );
							if( $path->get_path_relative() == $test_path->get_path_relative() ){
								self::$handles[ $pathOrHandle ] = $handle;
								break;
							}
						}
				}
			}
			return self::$handles[ $pathOrHandle ];
		}


		/**
		 * Register file in queue
		 * @param $pathOrUrl
		 * @return file
		 */
		static function add( $pathOrUrl ){
			require_once __DIR__ . '/-hooks.php';
			$handle = self::get_handle( $pathOrUrl );
			if( !array_key_exists( $handle, self::$registred ) ){
				$new_file = new file( $pathOrUrl );
				self::$queue[ $handle ] = $new_file;
				self::$registred[ $handle ] = $new_file;
			}
			return self::$registred[ $handle ];
		}


		/**
		 * Enqueue Scripts
		 */
		static function _enqueue_scripts(){
			if( did_action( 'admin_print_footer_scripts' ) === 0 && did_action( 'wp_print_footer_scripts' ) === 0 ){
				///
				while( $file = self::next_queue( 1 ) ){
					if( $file instanceof file ){
						$handle = self::get_handle( $file->get() );
						foreach( $file->get_deeps() as $deep_handle ){
							self::$queue[ $deep_handle ];
						}
						wp_register_script( $handle, $file->get_url(), $file->get_deeps(), filemtime( $file->get_path() ), $file->is_in_footer() );
						wp_enqueue_script( $handle );
					}
				}
				///
			} else {
				///
				while( $file = self::next_queue( - 1 ) ){
					self::the_prefetch_deeps_from_file( $file );
				}
				///
			}
		}


		/**
		 * @param $file
		 */
		static private function the_prefetch_deeps_from_file( $file ){
			if( !$file instanceof file )
				return;
			///
			foreach( $file->get_deeps() as $handle ){
				if( array_key_exists( $handle, self::$queue ) ){
					$pre_file = self::$queue[ $handle ];
					unset( self::$queue[ $handle ] );
					self::$past_line[] = $handle;
					self::the_prefetch_deeps_from_file( $pre_file );
				}
			}
			///
			$file->the();
		}


		/**
		 * @param int $footer_status - 1 | true - in footer, 0 | false - in header | -1 - irrelevant
		 * @return false|file
		 */
		static private function next_queue( $footer_status = - 1 ){
			$footer_status = (int)$footer_status;
			foreach( self::$queue as $handle => $file ){
				if( $footer_status < 0 || ( $footer_status == 0 && $file->is_in_footer() ) || ( $footer_status > 0 && !$file->is_in_footer() ) ){
					unset( self::$queue[ $handle ] );
					self::$past_line[] = $handle;
					return $file;
				}
			}
			return false;
		}


		/**
		 * @param $tag
		 * @param $handle
		 * @param $src
		 * @return null|string
		 */
		static public function _add_filter_script_loader_tag( $tag, $handle, $src ){
			if( array_key_exists( $handle, self::$registred ) ){
				$js = self::$registred[ $handle ];
				if( $js instanceof file ){
					self::$past_line[] = $handle;
					return $js->html();
				}
			}
			return $tag;
		}

	}