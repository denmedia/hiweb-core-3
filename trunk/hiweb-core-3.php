<?php
	/*
	Plugin Name: hiWeb Core 3
	Plugin URI: http://plugins.hiweb.moscow/core
	Description: Special framework plugin for WordPress min 4.8
	Version: 3.1.1.0
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/

	if( version_compare( PHP_VERSION, '5.4.0' ) >= 0 ){
		///
		require_once 'include/spl_autoload_register.php';
		require_once 'include/define.php';
		require_once 'include/init.php';

		///Include text.php if exists
		if( file_exists( __DIR__ . '/test.php' ) && is_readable( __DIR__ . '/test.php' ) ){
			include_once __DIR__ . '/test.php';
		}
	} else {
		function hw_core_php_version_error(){
			include 'views/core-error-php-version.php';
		}

		add_action( 'admin_notices', 'hw_core_php_version_error' );
	}