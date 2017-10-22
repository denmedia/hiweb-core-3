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
		require_once 'include/spl_autoload_register.php';
		require_once 'include/define.php';
		require_once 'include/init.php';
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

	//TODO-
	add_admin_menu_page( 'test', 'Просто тест' )->page_title( '⚙️ Просто страница тестовых настроек' )->function_page( function( $params = null, $page = null ){
		\hiweb\dump( [ $params, $page ] );
	}, [ 'foo', 'bar' ] );

	$field = add_field_script('32543re');
	$field->location()->admin_menus('test');
	$field->value_default('#ff4499');
	$field->admin_label('Проверка чекбокса');
	$field->admin_description('Более подробное описание поля. Более подробное описание поля. Более подробное описание поля. Более подробное описание поля. Более подробное описание поля. Более подробное описание поля. Более подробное описание поля. Более подробное описание поля.');
	$field->admin_template('post-box');