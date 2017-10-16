<?php

	namespace hiweb\fields\functions;


	use hiweb\fields;
	use hiweb\fields\field;


	/**
	 * @param $id
	 * @return string
	 */
	function get_field_input_name( $id ){
		return 'hiweb-field-' . $id;
	}

	/**
	 * @param string $field_id
	 * @param string $page_slug
	 * @return string
	 */
	function get_options_field_id( $page_slug, $field_id ){
		return $page_slug . '-' . $field_id;
	}

	/**
	 * @param $page_slug
	 * @return string
	 */
	function get_options_group_id( $page_slug ){
		return 'hiweb-options-group-' . $page_slug;
	}

	/**
	 * @param $field_id
	 * @return string
	 */
	function get_columns_field_id( $field_id ){
		return 'hiweb-column-' . $field_id;
	}

	/**
	 * Смена глобального ID для поля
	 * @param $oldGlobalId
	 * @param $newGlobalId
	 * @return bool
	 */
	function change_globalId( $oldGlobalId, $newGlobalId ){
		if( !isset( fields::$fields[ $oldGlobalId ] ) ) return false;
		$field = fields::$fields[ $oldGlobalId ];
		unset( fields::$fields[ $oldGlobalId ] );
		fields::$fields[ $newGlobalId ] = $field;
		if( isset( fields::$fieldId_globalId[ $field->id() ] ) && is_array( fields::$fieldId_globalId[ $field->id() ] ) ) foreach( fields::$fieldId_globalId[ $field->id() ] as $index => $globalIds ){
			if( $globalIds == $oldGlobalId ){
				fields::$fieldId_globalId[ $field->id() ][ $index ] = $newGlobalId;
			}
		}
		if( isset( fields::$globalId_fieldId[ $oldGlobalId ] ) && is_array( fields::$globalId_fieldId[ $oldGlobalId ] ) ){
			$ids = fields::$globalId_fieldId[ $oldGlobalId ];
			unset( fields::$globalId_fieldId[ $oldGlobalId ] );
			fields::$globalId_fieldId[ $newGlobalId ] = $ids;
		}
		return true;
	}

	function get_locationOptions_from_contextObject( $contextObject = null ){
		$R = [];
		if( $contextObject instanceof \WP_Post ){
			$R['post_types']['post_name'] = $contextObject->post_name;
			$R['post_types']['post_type'] = $contextObject->post_type;
			$R['post_types']['front_page'] = get_option( 'page_on_front' ) == $contextObject->ID;
			$R['post_types']['comment_status'] = $contextObject->comment_status;
			$R['post_types']['has_taxonomy'] = get_object_taxonomies( $contextObject, $output = 'names' );
			$R['post_types']['ID'] = $contextObject->ID;
			$R['post_types']['post_parent'] = $contextObject->post_parent;
			$R['post_types']['post_status'] = $contextObject->post_status;
		} elseif( $contextObject instanceof \WP_Term ) {
			$R['taxonomies']['term_id'] = $contextObject->term_id;
			$R['taxonomies']['term_taxonomy_id'] = $contextObject->term_taxonomy_id;
			$R['taxonomies']['name'] = $contextObject->name;
			$R['taxonomies']['taxonomy'] = $contextObject->taxonomy;
			$R['taxonomies']['slug'] = $contextObject->slug;
			$R['taxonomies']['count'] = $contextObject->count;
			$R['taxonomies']['parent'] = $contextObject->parent;
			$R['taxonomies']['term_group'] = $contextObject->term_group;
		} elseif( $contextObject instanceof \WP_User ) {
			$R['users']['ID'] = $contextObject->ID;
			$R['users']['display_name'] = $contextObject->display_name;
			$R['users']['first_name'] = $contextObject->first_name;
			$R['users']['last_name'] = $contextObject->last_name;
			$R['users']['locale'] = $contextObject->locale;
			$R['users']['nickname'] = $contextObject->nickname;
			$R['users']['user_email'] = $contextObject->user_email;
			$R['users']['user_firstname'] = $contextObject->user_firstname;
			$R['users']['user_lastname'] = $contextObject->user_lastname;
			$R['users']['user_level'] = $contextObject->user_level;
			$R['users']['user_login'] = $contextObject->user_login;
			$R['users']['user_nicename'] = $contextObject->user_nicename;
			$R['users']['user_registered'] = $contextObject->user_registered;
			$R['users']['user_status'] = $contextObject->user_status;
			$R['users']['user_url'] = $contextObject->user_url;
			$R['users']['roles'] = $contextObject->roles;
		} elseif( is_string( $contextObject ) ) {
			$R['admin_menus']['slug'] = $contextObject;
		}
		return $R;
	}

	/**
	 * @param null|\WP_Post|\WP_Term|\WP_User $contextObject
	 * @return fields\locations\location
	 */
	function get_newLocation_from_contextObject( $contextObject = null ){
		$location = new fields\locations\location();
		$location->set_options( get_locationOptions_from_contextObject( $contextObject ) );
		return $location;
	}

	/**
	 * @param array $options
	 * @return string
	 */
	function get_contextId_from_options( array $options ){
		return md5( json_encode( $options ) );
	}

	/**
	 * @param null $object
	 * @return string
	 */
	function get_contextId_from_contextObject( $object = null ){
		return get_contextId_from_options( get_locationOptions_from_contextObject( $object ) );
	}

	/**
	 * @param array $fields
	 */
	function the_form_fields( array $fields, $contextObjects = null ){
		$fields_by_template = [];
		$templates_by_index = [];
		$last_template = '';
		$template_index = - 1;
		///
		foreach( $fields as $field_id => $field ){
			if( !$field instanceof field ) continue;
			if( $field->admin_template() != $last_template ){
				$template_index ++;
				$last_template = $field->admin_template();
			}
			$fields_by_template[ $template_index ][] = $field;
			$templates_by_index[ $template_index ] = $last_template;
		}
		///
		$R = [];
		if( count( $fields_by_template ) > 0 ){
			foreach( $fields_by_template as $index => $fields ){
				$template_name = $templates_by_index[ $index ];
				ob_start();
				include __DIR__ . '/field/templates/' . $template_name . '-form.php';
				$form_html = ob_get_clean();
				$fields_html = [];
				/**
				 * @var string $field_id
				 * @var field  $field
				 */
				foreach( $fields as $field_id => $field ){
					$fields_html[] = $field->admin_get_field( $field->value_context( $contextObjects )->value() );
				}
				$R[] = str_replace( '<!--fields-->', implode( '<!--field-->', $fields_html ), $form_html );
			}
		}
		echo implode( '<!--form-->', $R );
		///
	}