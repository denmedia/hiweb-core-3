<?php

	namespace hiweb;


	class console{


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function info( $content, $debugMod = false ){
			return console\messages::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function warn( $content, $debugMod = false ){
			return console\messages::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function error( $content, $debugMod = false ){
			return console\messages::make( $content, __FUNCTION__, $debugMod );
		}


		/**
		 * @param      $content
		 * @param bool $debugMod
		 * @return console\message
		 */
		static function log( $content, $debugMod = false ){
			return console\messages::make( $content, __FUNCTION__, $debugMod );
		}


	}