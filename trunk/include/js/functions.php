<?php

	namespace {


		if ( ! function_exists( 'include_js' ) ) {
			/**
			 * @param string $jsPathOrURL
			 * @param array $deeps
			 * @param bool $inFooter
			 *
			 * @return bool
			 */
			function include_js( $jsPathOrURL, $deeps = [], $inFooter = true ) {
				return hiweb\js\enqueue::add( $jsPathOrURL, $deeps, $inFooter );
			}
		} else {
			hiweb\console::debug_warn( 'Function [include_js] is exists...' );
		}
	}

	namespace hiweb {


		/**
		 * @param string $jsPathOrURL
		 * @param array $deeps
		 * @param bool $inFooter
		 *
		 * @return bool
		 */
		function js( $jsPathOrURL, $deeps = [], $inFooter = true ) {
			return js\enqueue::add( $jsPathOrURL, $deeps, $inFooter );
		}
	}