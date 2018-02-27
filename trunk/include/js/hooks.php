<?php

	namespace hiweb;


	add_action( 'wp_enqueue_scripts', 'hiweb\\js\\enqueue::wp_register_script' );
	add_action( 'admin_enqueue_scripts', 'hiweb\\js\\enqueue::wp_register_script' );
	add_action( 'login_enqueue_scripts', 'hiweb\\js\\enqueue::wp_register_script' );
	add_action( 'customize_render_control', 'hiweb\\js\\enqueue::the' );
	add_action( 'wp_footer', 'hiweb\\js\\enqueue::wp_register_script' );
	add_action( 'admin_footer', 'hiweb\\js\\enqueue::wp_register_script' );
	add_action( 'shutdown', 'hiweb\\js\\enqueue::wp_register_script' );