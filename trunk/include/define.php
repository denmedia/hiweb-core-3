<?php

	namespace hiweb;


	if( !defined( 'HIWEB_DIR' ) ) define( 'HIWEB_DIR', dirname( __DIR__ ) );
	if( !defined( 'HIWEB_URL' ) ) define( 'HIWEB_URL', path::path_to_url( HIWEB_DIR ) );
	//
	if( !defined( 'HIWEB_DIR_INCLUDE' ) ) define( 'HIWEB_DIR_INCLUDE', HIWEB_DIR . '/include' );
	if( !defined( 'HIWEB_URL_INCLUDE' ) ) define( 'HIWEB_URL_INCLUDE', path::path_to_url( HIWEB_DIR_INCLUDE ) );
	if( !defined( 'HIWEB_DIR_TOOLS' ) ) define( 'HIWEB_DIR_TOOLS', HIWEB_DIR . '/tools' );
	if( !defined( 'HIWEB_URL_TOOLS' ) ) define( 'HIWEB_URL_TOOLS', path::path_to_url( HIWEB_DIR_TOOLS ) );
	if( !defined( 'HIWEB_DIR_VENDORS' ) ) define( 'HIWEB_DIR_VENDORS', HIWEB_DIR . '/vendors' );
	if( !defined( 'HIWEB_URL_VENDORS' ) ) define( 'HIWEB_URL_VENDORS', path::path_to_url( HIWEB_DIR_VENDORS ) );
	if( !defined( 'HIWEB_DIR_LANGUAGES' ) ) define( 'HIWEB_DIR_LANGUAGES', HIWEB_DIR . '/languages' );
	if( !defined( 'HIWEB_URL_LANGUAGES' ) ) define( 'HIWEB_URL_LANGUAGES', path::path_to_url( HIWEB_DIR_LANGUAGES ) );
	if( !defined( 'HIWEB_DIR_ASSETS' ) ) define( 'HIWEB_DIR_ASSETS', HIWEB_DIR . '/assets' );
	if( !defined( 'HIWEB_URL_ASSETS' ) ) define( 'HIWEB_URL_ASSETS', path::path_to_url( HIWEB_DIR_ASSETS ) );
	if( !defined( 'HIWEB_DIR_JS' ) ) define( 'HIWEB_DIR_JS', HIWEB_DIR_ASSETS . '/js' );
	if( !defined( 'HIWEB_URL_JS' ) ) define( 'HIWEB_URL_JS', path::path_to_url( HIWEB_DIR_JS ) );
	if( !defined( 'HIWEB_DIR_CSS' ) ) define( 'HIWEB_DIR_CSS', HIWEB_DIR_ASSETS . '/css' );
	if( !defined( 'HIWEB_URL_CSS' ) ) define( 'HIWEB_URL_CSS', path::path_to_url( HIWEB_DIR_CSS ) );
	//
	if( !defined( 'HIWEB_DIR_FIELD_TYPES' ) ) define( 'HIWEB_DIR_FIELD_TYPES', HIWEB_DIR_INCLUDE . '/fields/field/types' );
	if( !defined( 'HIWEB_URL_FIELD_TYPES' ) ) define( 'HIWEB_URL_FIELD_TYPES', path::path_to_url( HIWEB_DIR_FIELD_TYPES ) );