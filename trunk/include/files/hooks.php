<?php

	namespace hiweb;


	add_action( 'wp_enqueue_scripts', 'hiweb\js::wp_register_script' );
	add_action( 'admin_enqueue_scripts', 'hiweb\js::wp_register_script' );
	add_action( 'login_enqueue_scripts', 'hiweb\js::wp_register_script' );
	add_action( 'wp_footer', 'hiweb\js::wp_register_script' );
	add_action( 'admin_footer', 'hiweb\js::wp_register_script' );