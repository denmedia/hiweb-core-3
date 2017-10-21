<?php

	namespace {


		use hiweb\admin;


		if( !function_exists( 'add_admin_menu_page' ) ){
			function add_admin_menu_page( $slug, $title, $parent_slug = null ){
				return admin::add_page( $slug, $title, $parent_slug );
			}
		}
	}