<?php

	namespace hiweb;


	if( !defined( 'HIWEB_DIR' ) ) define( 'HIWEB_DIR', dirname( __DIR__ ) );
	if( !defined( 'HIWEB_URL' ) ) define( 'HIWEB_URL', get_path( HIWEB_DIR )->get_url() );
	//
	if( !defined( 'HIWEB_DIR_INCLUDE' ) ) define( 'HIWEB_DIR_INCLUDE', HIWEB_DIR . '/include' );
	if( !defined( 'HIWEB_URL_INCLUDE' ) ) define( 'HIWEB_URL_INCLUDE', get_path( HIWEB_DIR_INCLUDE )->get_url() );
	//if( !defined( 'HIWEB_DIR_TRAITS' ) ) define( 'HIWEB_DIR_TRAITS', HIWEB_DIR . '/traits' );
	//if( !defined( 'HIWEB_URL_TRAITS' ) ) define( 'HIWEB_URL_TRAITS', path::path_to_url( HIWEB_DIR_TRAITS ) );
	if( !defined( 'HIWEB_DIR_TOOLS' ) ) define( 'HIWEB_DIR_TOOLS', HIWEB_DIR . '/tools' );
	if( !defined( 'HIWEB_URL_TOOLS' ) ) define( 'HIWEB_URL_TOOLS', get_path( HIWEB_DIR_TOOLS )->get_url() );
	if( !defined( 'HIWEB_DIR_VENDORS' ) ) define( 'HIWEB_DIR_VENDORS', HIWEB_DIR . '/vendors' );
	if( !defined( 'HIWEB_URL_VENDORS' ) ) define( 'HIWEB_URL_VENDORS', get_path( HIWEB_DIR_VENDORS )->get_url() );
	if( !defined( 'HIWEB_DIR_LANGUAGES' ) ) define( 'HIWEB_DIR_LANGUAGES', HIWEB_DIR . '/languages' );
	if( !defined( 'HIWEB_URL_LANGUAGES' ) ) define( 'HIWEB_URL_LANGUAGES', get_path( HIWEB_DIR_LANGUAGES )->get_url() );
	if( !defined( 'HIWEB_DIR_ASSETS' ) ) define( 'HIWEB_DIR_ASSETS', HIWEB_DIR . '/assets' );
	if( !defined( 'HIWEB_URL_ASSETS' ) ) define( 'HIWEB_URL_ASSETS', get_path( HIWEB_DIR_ASSETS )->get_url() );
	if( !defined( 'HIWEB_DIR_JS' ) ) define( 'HIWEB_DIR_JS', HIWEB_DIR_ASSETS . '/js' );
	if( !defined( 'HIWEB_URL_JS' ) ) define( 'HIWEB_URL_JS', get_path( HIWEB_DIR_JS )->get_url() );
	if( !defined( 'HIWEB_DIR_CSS' ) ) define( 'HIWEB_DIR_CSS', HIWEB_DIR_ASSETS . '/css' );
	if( !defined( 'HIWEB_URL_CSS' ) ) define( 'HIWEB_URL_CSS', get_path( HIWEB_DIR_CSS )->get_url() );
	//
	if( !defined( 'HIWEB_DIR_FIELD_TYPES' ) ) define( 'HIWEB_DIR_FIELD_TYPES', HIWEB_DIR . '/types' );
	if( !defined( 'HIWEB_URL_FIELD_TYPES' ) ) define( 'HIWEB_URL_FIELD_TYPES', get_path( HIWEB_DIR_FIELD_TYPES )->get_url() );