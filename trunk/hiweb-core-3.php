<?php
	/*
	Plugin Name: hiWeb Core 3
	Plugin URI: http://plugins.hiweb.moscow/core
	Description: Special framework plugin for WordPress min 4.8
	Version: 3.0.0.0
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/

	if( version_compare( PHP_VERSION, '5.4.0' ) >= 0 ){
		///
		require_once 'include/_spl_autoload_register.php';
		require_once 'include/hiweb.php';
		//		add_action( 'plugins_loaded', function(){
		//			$mo_file_path = __DIR__ . '/languages/hw-core-2-' . get_locale() . '.mo';
		//			load_textdomain( 'hw-core-3', $mo_file_path );
		//		} );
		///
		//		require_once 'include/core.php';
		//		require_once 'include/short_functions.php';
		//		require_once 'include/ajax.php';
		///
		//		if( file_exists( hiweb()->dir . '/test.php' ) && is_readable( hiweb()->dir . '/test.php' ) ){
		//			include_once hiweb()->dir . '/test.php';
		//		}
	} else {
		//		function hw_core_php_version_error(){
		//			include 'views/core-error-php-version.php';
		//		}
		//
		//		add_action( 'admin_notices', 'hw_core_php_version_error' );
	}

	//require 'include/home.php';

	$location = hiweb\fields\locations::register();

	hiweb\dump($location);