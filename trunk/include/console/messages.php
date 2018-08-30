<?php

	namespace hiweb\console;


	use hiweb\context;


	class messages{


		/** @var array|message[] */
		static $messages = [];


		/**
		 * @param string $content
		 * @param string $type
		 * @param bool $debugMod
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
		 * @version 1.2
		 */
		static function the(){
			if( is_array( self::$messages ) && !context::is_ajax() && (context::is_frontend_page() || context::is_login_page() || context::is_admin_page()) ){
				foreach( self::$messages as $message ){
					if( $message instanceof message ) $message->the();
				}
			}
		}
	}
