<?php

	namespace hiweb;


	//Header
	add_action( 'wp_enqueue_scripts', 'hiweb\\js::_enqueue_scripts' );
	add_action( 'admin_enqueue_scripts', 'hiweb\\js::_enqueue_scripts' );
	add_action( 'login_enqueue_scripts', 'hiweb\\js::_enqueue_scripts' );
	//Footer
	//add_action( 'get_footer', 'hiweb\\js::_enqueue_scripts' );
	//add_action( 'wp_footer', 'hiweb\\js::_enqueue_scripts' );
	add_action( 'wp_print_footer_scripts', 'hiweb\\js::_enqueue_scripts', 2, 9999 );
	add_action( 'hiweb_theme_body_sufix_before', 'hiweb\\js::_enqueue_scripts', 2, 9999 );
	add_action( 'hiweb_theme_body_sufix_after', 'hiweb\\js::_enqueue_scripts', 2, 9999 );

	add_action( 'admin_footer', 'hiweb\\js::_enqueue_scripts' );
	add_action( 'admin_print_footer_scripts', 'hiweb\\js::_enqueue_scripts', 2, 9999 );
	//Check if not included
	add_action( 'shutdown', 'hiweb\\js::_enqueue_scripts', 2, 9999 );
	//
	//add_action( 'customize_render_control', 'hiweb\\js::the', 2, 9999 );

	add_filter( 'script_loader_tag', '\\hiweb\\js::_add_filter_script_loader_tag', 10, 3 );