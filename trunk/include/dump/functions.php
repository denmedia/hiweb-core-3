<?php

	namespace hiweb;


	use hiweb\dump\subfunctions;


	/**
	 * @param $variable
	 * @return string
	 */
	function dump( $variable ){
		subfunctions::the( $variable );
	}