<?php

	namespace hiweb\fields\locations;


	use hiweb\arrays;
	use hiweb\console;
	use hiweb\fields\field;


	class locations{

		/** @var location[] */
		static $locations = [];
		/** @var array */
		static $rules = [];
		/** @var array */
		static $rulesId = [];
		/** @var location */
		static $last_field_location;
		static $last_field_location_metabox;


		/**
		 * @param null|field $field
		 * @return location
		 */
		static function register( $field = null ){
			$location = new location( $field );
			$location_id = spl_object_hash( $location );
			self::$locations[ $location_id ] = $location;
			self::$rules[ $location_id ] = [];
			self::$rulesId[ $location_id ] = '';
			return $location;
		}


		/**
		 * @param array $options
		 * @return string
		 */
		static function get_contextId_from_options( array $options ){
			return md5( json_encode( $options ) );
		}


		/**
		 * @param null $object
		 * @return string
		 */
		static function get_contextId_from_contextObject( $object = null ){
			return self::get_contextId_from_options( self::get_locationOptions_from_contextObject( $object ) );
		}


		/**
		 * Return register locations by context location
		 * @param location $context_location
		 * @return location[]
		 */
		static function get_locations_by_contextLocation( location $context_location ){
			$R = [];
			foreach( $context_location->_get_options() as $context_options_type => $context_options ){
				foreach( self::$locations as $location_id => $register_location ){
					foreach( $register_location->_get_options() as $register_options_type => $register_options ){
						if( $context_options_type != $register_options_type ) continue;
						if( self::get_options_compare( $context_options, $register_options ) ){
							$R[ spl_object_hash( $register_location ) ] = $register_location;
							continue 2;
						}
					}
				}
			}
			return $R;
		}


		/**
		 * Get fields by context location
		 * @param location $context_location
		 * @return array|field[]
		 */
		static function get_fields_by_contextLocation( location $context_location ){
			$R = [];
			$locations = locations::get_locations_by_contextLocation( $context_location );
			foreach( $locations as $location_id => $location ){
				if( $location->_get_parent_field() instanceof field ) $R[ $location_id ] = $location->_get_parent_field();
			}
			return $R;
		}


		/**
		 * @param null $contextObject
		 * @return array|field[]
		 */
		static function get_fields_by_contextObject( $contextObject = null ){
			$location = locations::get_abstractLocation_from_contextObject( $contextObject );
			return self::get_fields_by_contextLocation( $location );
		}


		/**
		 * @param array $context_options
		 * @param array $register_options
		 * @return bool
		 */
		static private function get_options_compare( $context_options = [], $register_options = [] ){
			$intersect_keys = array_keys( array_intersect_key( $context_options, $register_options ) );
			foreach( $intersect_keys as $key ){
				$haystack_a = (array)( $context_options[ $key ] );
				$haystack_b = (array)( $register_options[ $key ] );
				$intersect_values = count( @array_intersect_assoc( $haystack_a, $haystack_b ) );
				if( $intersect_values == 0 ) return false;
			}
			return true;
		}


		/**
		 * @param null|\WP_Post|\WP_Term|\WP_User $contextObject
		 * @return location
		 */
		static function get_abstractLocation_from_contextObject( $contextObject = null ){
			$location = new location();
			$location->_set_options( self::get_locationOptions_from_contextObject( $contextObject ) );
			return $location;
		}


		/**
		 * @param $contextOptions
		 * @return false|\WP_Post|\WP_Term|\WP_User
		 */
		static function get_contextObject_from_contextOptions( $contextOptions ){
			if( !is_null( arrays::get_value_by_key( $contextOptions, [ 'post_types', 'ID' ] ) ) ){
				$R = get_post( $contextOptions['post_types']['ID'] );
				return $R instanceof \WP_Post ? $R : false;
			} elseif( !is_null( arrays::get_value_by_key( $contextOptions, [ 'taxonomies', 'term_id' ] ) ) ) {
				$R = get_term_by( 'id', $contextOptions['taxonomies']['term_id'] );
				return $R instanceof \WP_Term ? $R : false;
			} elseif( !is_null( arrays::get_value_by_key( $contextOptions, [ 'users', 'ID' ] ) ) ) {
				$R = get_user_by( 'ID', $contextOptions['taxonomies']['term_id'] );
				return $R instanceof \WP_User ? $R : false;
			} elseif( is_string( arrays::get_value_by_key( $contextOptions, [ 'admin_menus', 'menu_slug' ] ) ) ) {
				return $contextOptions['admin_menus']['menu_slug'];
			}
			return false;
		}


		/**
		 * @param null $contextObject
		 * @return array
		 */
		static function get_locationOptions_from_contextObject( $contextObject = null ){
			$R = [];
			if( is_null( $contextObject ) || empty( $contextObject ) || is_numeric( $contextObject ) ){
				if( !function_exists( 'get_queried_object' ) || !did_action( 'wp' ) ){
					console::debug_warn( 'Попытка получения контекста локации, но функция [get_queried_object] не существует!' );
					return [];
				} elseif( is_object( get_queried_object() ) ) {
					if( is_numeric( $contextObject ) ){
						if( $contextObject instanceof \WP_Post ){
							//TODO
						} elseif( $contextObject instanceof \WP_Term ) {
							//TODO
						} elseif( $contextObject instanceof \WP_User ) {
							//TODO
						}
					} else {
						return self::get_locationOptions_from_contextObject( get_queried_object() );
					}
				}
			}
			if( $contextObject instanceof \WP_Post ){
				$R['post_types']['post_name'] = $contextObject->post_name;
				$R['post_types']['post_type'] = $contextObject->post_type;
				$R['post_types']['front_page'] = get_option( 'page_on_front' ) == $contextObject->ID;
				$R['post_types']['comment_status'] = $contextObject->comment_status;
				$R['post_types']['has_taxonomy'] = get_object_taxonomies( $contextObject, $output = 'names' );
				$R['post_types']['ID'] = $contextObject->ID;
				$R['post_types']['post_parent'] = $contextObject->post_parent;
				$R['post_types']['post_status'] = $contextObject->post_status;
				$R['post_types']['template'] = get_page_template_slug( $contextObject->ID );
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
				$R['admin_menus']['menu_slug'] = $contextObject;
			}
			return $R;
		}


	}
