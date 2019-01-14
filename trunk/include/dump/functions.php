<?php

	namespace {


		if( !function_exists( 'dump_var' ) ){
			/**
			 * @param $variable
			 */
			function dump_var( $variable ){
				hiweb\dump::the( $variable );
			}
		} else {
			hiweb\console::debug_warn( 'Function [dump_var] is exists...' );
		}
	}