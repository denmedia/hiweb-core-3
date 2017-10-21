<?php

	use hiweb\console;


	if(!function_exists('console_info')){
		/**
		 * Print the console.info()
		 * @param $info
		 * @return hiweb\console\message
		 */
		function console_info( $info ){
			return console::info( $info );
		}
	}

	if(!function_exists('console_warn')){
		/**
		 * Print the console.warn()
		 * @param $info
		 * @return hiweb\console\message
		 */
		function console_warn( $info ){
			return console::warn( $info );
		}
	}

	if(!function_exists('console_error')){
		/**
		 * Print the console.error()
		 * @param $info
		 * @return hiweb\console\message
		 */
		function console_error( $info ){
			return console::error( $info );
		}
	}

	if(!function_exists('console_log')){
		/**
		 * Print the console.log()
		 * @param $info
		 * @return hiweb\console\message
		 */
		function console_log( $info ){
			return console::log( $info );
		}
	}
