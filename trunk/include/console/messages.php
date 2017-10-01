<?php

	namespace hiweb\console;


	class messages{


		/** @var array|message[] */
		static $messages = [];


		/**
		 * @param string $content
		 * @param string $type
		 * @param bool   $debugMod
		 * @return message
		 */
		static function make( $content = '', $type = 'info', $debugMod = false ){
			$message = new message( $content, $type );
			$message->set_debugMod( $debugMod );
			$global_id = spl_object_hash( $message );
			self::$messages[ $global_id ] = $message;
			return $message;
		}


		/**
		 * Print messages script
		 */
		static function the(){
			if( is_array( self::$messages ) ){
				foreach( self::$messages as $message ){
					if( $message instanceof message ) $message->the();
				}
			}
		}
	}