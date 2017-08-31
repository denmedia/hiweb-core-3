<?php


	class hiweb{

		/**
		 * Return path to base hiweb core dir
		 * @return string
		 */
		static function DIR(){
			return dirname( __DIR__ );
		}


		/**
		 * Return url to base hiweb core dir
		 * @return string
		 */
		static function URL(){
			return hiweb\path::path_to_url( self::DIR() );
		}


		/**
		 * @return string
		 */
		static function DIR_INCLUDE(){
			return self::DIR() . '/include';
		}


		/**
		 * @return string
		 */
		static function URL_INCLUDE(){
			return hiweb\path::path_to_url( self::DIR_INCLUDE() );
		}


		static function DIR_VIEWS(){
			return self::DIR() . '/views';
		}


		/**
		 * @return mixed
		 */
		static function URL_VIEWS(){
			return hiweb\path::path_to_url( self::DIR_VIEWS() );
		}


		/**
		 * @return string
		 */
		static function DIR_ASSETS(){
			return self::DIR() . '/assets';
		}


		/**
		 * @return mixed
		 */
		static function URL_ASSETS(){
			return hiweb\path::path_to_url( self::DIR_ASSETS() );
		}


		/**
		 * @return string
		 */
		static function DIR_CSS(){
			return self::DIR_ASSETS() . '/css';
		}


		/**
		 * @return mixed
		 */
		static function URL_CSS(){
			return hiweb\path::path_to_url( self::DIR_CSS() );
		}


		/**
		 * @return string
		 */
		static function DIR_JS(){
			return self::DIR_ASSETS() . '/js';
		}


		/**
		 * @return mixed
		 */
		static function URL_JS(){
			return hiweb\path::path_to_url( self::DIR_JS() );
		}


		/**
		 * @return string
		 */
		static function DIR_VENDORS(){
			return self::DIR() . '/vendors';
		}


		/**
		 * @return mixed
		 */
		static function URL_VENDORS(){
			return hiweb\path::path_to_url( self::DIR_VENDORS() );
		}


	}