<?php

	namespace {


		if( !function_exists( 'include_js' ) ){

			/**
			 * @param $jsPathOrURL
			 * @param array $deeps
			 * @param bool $inFooter
			 * @return bool
			 */
			function include_js( $jsPathOrURL, $deeps = [], $inFooter = false ){
				return \hiweb\js( $jsPathOrURL, $deeps, $inFooter );
			}
		} else {
			hiweb\console::debug_error( 'Не возможно определить функция [include_js], она уже существует!' );
		}
	}

	namespace hiweb {


		/**
		 * @param string $jsPathOrURL
		 * @param array $deeps
		 * @param bool $inFooter
		 * @return bool
		 */
		function js( $jsPathOrURL, $deeps = [], $inFooter = false ){
			return js\enqueue::add( $jsPathOrURL, $deeps, $inFooter );
		}
	}