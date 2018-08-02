<?php
	/*
	Plugin Name: hiWeb Core 3
	Plugin URI: http://plugins.hiweb.moscow/core
	Description: Special framework plugin for WordPress min 4.8
	Version: 3.3.3.2
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/

	if ( version_compare( PHP_VERSION, '5.4.0' ) >= 0 ) {
		///
		require_once __DIR__ . '/include/spl_autoload_register.php';
		require_once __DIR__ . '/traits/hidden_methods.php';
		require_once __DIR__ . '/include/define.php';
		require_once __DIR__ . '/include/init.php';
		///Include test.php if exists
		if ( file_exists( __DIR__ . '/test.php' ) && is_readable( __DIR__ . '/test.php' ) ) {
			add_action( 'init', function() {
				include_once __DIR__ . '/test.php';
			} );
		}
	} else {
		function hw_core_php_version_error() {
			include __DIR__ . '/views/core-error-php-version.php';
		}

		add_action( 'admin_notices', 'hw_core_php_version_error' );
	}
