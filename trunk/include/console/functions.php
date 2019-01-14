<?php

	use hiweb\console;


	if( !function_exists( 'console_info' ) ){
		/**
		 * Print the console.info()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\console\message
		 */
		function console_info( $info, $groupTitle = '', $additionData = [] ){
			return console::info( $info, $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_warn' ) ){
		/**
		 * Print the console.warn()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\console\message
		 */
		function console_warn( $info, $groupTitle = '', $additionData = [] ){
			return console::warn( $info, $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_error' ) ){
		/**
		 * Print the console.error()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\console\message
		 */
		function console_error( $info, $groupTitle = '', $additionData = [] ){
			return console::error( $info, $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_log' ) ){
		/**
		 * Print the console.log()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\console\message
		 */
		function console_log( $info, $groupTitle = '', $additionData = [] ){
			return console::log( $info, $groupTitle, $additionData );
		}
	}
