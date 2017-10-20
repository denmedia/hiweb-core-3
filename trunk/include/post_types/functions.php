<?php
	use hiweb\console;


	if( function_exists( 'add_post_type' ) ){
		console::debug_warn( 'Функция [add_post_type] существует' );
	} else {
		function add_post_type( $post_type ){
			return hiweb\post_types::register( $post_type );
		}
	}