<?php

	namespace hiweb;


	class console{


		private static $str_debug_delimeter = '---------------------------------';


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return console\message
		 */
		static function info( $content, $groupTitle = '', $additionData = [] ){
			return console\messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return console\message
		 */
		static function warn( $content, $groupTitle = '', $additionData = [] ){
			return console\messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return console\message
		 */
		static function error( $content, $groupTitle = '', $additionData = [] ){
			return console\messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return console\message
		 */
		static function log( $content, $groupTitle = '', $additionData = [] ){
			return console\messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return console\message
		 */
		static function debug_info( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = console\messages::make( $content, 'info', 2 );
				if( !is_null( $addition_data ) ){
					console\messages::make( $addition_data, 'info', false );
					console\messages::make( self::$str_debug_delimeter, 'info', false );
				}
			}
			return $R;
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return console\message
		 */
		static function debug_warn( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = console\messages::make( $content, 'warn', 2 );
				if( !is_null( $addition_data ) ){
					console\messages::make( $addition_data, 'info', false );
					console\messages::make( self::$str_debug_delimeter, 'info', false );
				}
			}
			return $R;
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return console\message
		 */
		static function debug_error( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = console\messages::make( $content, 'error', 2 );
				if( !is_null( $addition_data ) ){
					console\messages::make( $addition_data, 'info', false );
					console\messages::make( self::$str_debug_delimeter, 'info', false );
				}
			}
			return $R;
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return console\message
		 */
		static function debug_log( $content, $addition_data = null ){
			if( false ){
				$R = console\messages::make( $content, 'log', 2 );
				if( !is_null( $addition_data ) ){
					console\messages::make( $addition_data, 'log', false );
					console\messages::make( self::$str_debug_delimeter, 'log', false );
				}
			}
			return $R;
		}


	}