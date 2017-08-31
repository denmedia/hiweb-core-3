<?php

	namespace hiweb;


	class forms{

		/** @var forms\form[] */
		static $forms = [];


		static function register( $id = null ){
			//TODO
		}


		/**
		 * @param $id
		 * @return forms\form
		 */
		static function get( $id ){
			$id = sanitize_file_name( strtolower( $id ) );
			if( !array_key_exists( $id, self::$forms ) ){
				self::$forms[ $id ] = new forms\form( $id );
			}
			return self::$forms[ $id ];
		}

	}
	
