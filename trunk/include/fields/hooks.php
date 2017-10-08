<?php

	//BACKEND HOOKS
	//Post Type
	add_action( 'edit_form_top', 'hiweb\\fields\\locations\\backend::edit_form_top' );
	add_action( 'edit_form_before_permalink', 'hiweb\\fields\\locations\\backend::edit_form_before_permalink' );
	add_action( 'edit_form_after_title', 'hiweb\\fields\\locations\\backend::edit_form_after_title' );
	add_action( 'edit_form_after_editor', 'hiweb\\fields\\locations\\backend::edit_form_after_editor' );
	add_action( 'submitpost_box', 'hiweb\\fields\\locations\\backend::submitpost_box' );
	add_action( 'submitpage_box', 'hiweb\\fields\\locations\\backend::submitpost_box' );
	add_action( 'edit_form_advanced', 'hiweb\\fields\\locations\\backend::edit_form_advanced' );
	add_action( 'edit_page_form', 'hiweb\\fields\\locations\\backend::edit_form_advanced' );
	add_action( 'dbx_post_sidebar', 'hiweb\\fields\\locations\\backend::dbx_post_sidebar' );
	///Posts List Columns
	add_action( 'manage_pages_custom_column', 'hiweb\\fields\\locations\\backend::manage_posts_custom_column', 10, 2 );
	add_action( 'manage_posts_custom_column', 'hiweb\\fields\\locations\\backend::manage_posts_custom_column', 10, 2 );
	add_filter( 'manage_pages_columns', 'hiweb\\fields\\locations\\backend::manage_posts_columns', 10, 1 );
	add_filter( 'manage_posts_columns', 'hiweb\\fields\\locations\\backend::manage_posts_columns', 10, 2 );
	//	///Post Save
	add_action( 'save_post', 'hiweb\\fields\\locations\\backend::save_post', 10, 3 );
	//	////////
	//	///TAXONOMIES BACKEND
	//	add_action( 'init', function(){
	//		if( function_exists( 'get_taxonomies' ) && is_array( get_taxonomies() ) ) foreach( get_taxonomies() as $taxonomy_name ){
	//			//add
	//			add_action( $taxonomy_name . '_add_form_fields', [ $this, 'taxonomy_add_form_fields' ] );
	//			//edit
	//			add_action( $taxonomy_name . '_edit_form', [ $this, 'taxonomy_edit_form' ], 10, 2 );
	//		}
	//	}, 100 );
	//	///TAXONOMY SAVE
	//	add_action( 'create_term', [ $this, 'edited_term' ], 10, 3 );
	//	add_action( 'edited_term', [ $this, 'edited_term' ], 10, 3 );
	//	///OPTIONS FIELDS
	//	add_action( 'admin_init', [ $this, 'options_page_add_fields' ], 999999 );
	//	///ADMIN MENU FIELDS
	//	add_action( 'current_screen', [ $this, 'admin_menu_fields' ], 999999 );
	//	/// USERS SETTINGS
	//	add_action( 'admin_color_scheme_picker', [ $this, 'admin_color_scheme_picker' ] );
	//	add_action( 'personal_options', [ $this, 'personal_options' ] );
	//	add_action( 'profile_personal_options', [ $this, 'profile_personal_options' ] );
	//	add_action( 'show_user_profile', [ $this, 'show_user_profile' ] );
	//	add_action( 'edit_user_profile', [ $this, 'edit_user_profile' ] );
	//	/// USERS SAVE
	//	add_action( 'personal_options_update', [ $this, 'edit_user_profile_update' ] );
	//	add_action( 'edit_user_profile_update', [ $this, 'edit_user_profile_update' ] );