<?php

	namespace hiweb;


	/**
	 * @param string $jsPathOrURL
	 * @param array  $deeps
	 * @param bool   $inFooter
	 * @return bool
	 */
	function js( $jsPathOrURL, $deeps = [], $inFooter = false ){
		return js\enqueue::add( $jsPathOrURL, $deeps, $inFooter );
	}