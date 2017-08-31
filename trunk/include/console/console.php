<?php

	namespace hiweb;


	class console{

		/** @var array|console\message[] */
		static $messages = [];


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function info( $content, $debugMod = false ){
			return self::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function warn( $content, $debugMod = false ){
			return self::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function error( $content, $debugMod = false ){
			return self::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function log( $content, $debugMod = false ){
			return self::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param string $content
		 * @param string $type
		 * @param bool   $debugMod
		 * @return console\message
		 */
		static private function make( $content = '', $type = 'info', $debugMod = false ){
			$message = new console\message( $content, $type );
			$message->set_debugMod( $debugMod );
			$global_id = spl_object_hash( $message );
			self::$messages[ $global_id ] = $message;
			return $message;
		}


	}