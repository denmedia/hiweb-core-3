<?php

	namespace hiweb;


	add_action( 'wp_enqueue_scripts', 'hiweb\\css\\enqueue::wp_register_script' );
	add_action( 'admin_enqueue_scripts', 'hiweb\\css\\enqueue::wp_register_script' );
	add_action( 'login_enqueue_scripts', 'hiweb\\css\\enqueue::wp_register_script' );
	add_action( 'customize_render_control', 'hiweb\\css\\enqueue::the' );
	add_action( 'wp_footer', 'hiweb\\css\\enqueue::wp_register_script' );
	add_action( 'admin_footer', 'hiweb\\css\\enqueue::wp_register_script' );
	add_action( 'shutdown', 'hiweb\\css\\enqueue::wp_register_script' );