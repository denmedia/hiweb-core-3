<?php

	namespace hiweb\forms;


	class forms{

		/** @var form[] */
		static $forms = [];


		static function register( $id = null ){
			$form = new form( $id );
			self::$forms[ spl_object_hash( $form ) ] = $form;
			return $form;
		}


		/**
		 * @param $id
		 * @return form
		 */
		static function get( $id ){
			$id = sanitize_file_name( strtolower( $id ) );
			if( !array_key_exists( $id, self::$forms ) ){
				self::$forms[ $id ] = new form( $id );
			}
			return self::$forms[ $id ];
		}

	}
	
