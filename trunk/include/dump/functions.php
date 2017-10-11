<?php

	namespace hiweb;


	use hiweb\dump;


	/**
	 * @param $variable
	 * @return string
	 */
	function dump( $variable ){
		dump::the( $variable );
	}