<?php

	namespace hiweb\fields\locations;


	use hiweb\console;
	use hiweb\fields;
	use hiweb\fields\field;
	use hiweb\fields\locations\options\admin_menus;
	use hiweb\fields\locations\options\post_types;
	use hiweb\fields\locations\options\taxonomies;
	use hiweb\fields\locations\options\theme;
	use hiweb\fields\locations\options\users;


	class location{

		/** @var options\options[] */
		public $options = [];
		/** @var field */
		private $parent_field;


		public function __construct( $field = null ){
			if( $field instanceof field ) $this->parent_field = $field;
		}


		/**
		 * @return string
		 */
		public function rules_id(){
			return locations::get_contextId_from_options( $this->options );
		}


		/**
		 * @return field|null
		 */
		public function _get_parent_field(){
			return $this->parent_field;
		}


		/**
		 * @param string $type
		 * @return array|bool
		 */
		public function _get_options_by_type( $type = 'post_types' ){
			if( !isset( $this->options[ $type ] ) || !$this->options[ $type ] instanceof fields\locations\options\options ) return [];
			$location_options = [];
			if( is_array( $this->options[ $type ]->options ) ){
				foreach( $this->options[ $type ]->options as $key => $option ){
					if( $option instanceof fields\locations\options\options ){
						$location_options[ $key ] = $option->options;
					} else {
						$location_options[ $key ] = $option;
					}
				}
			} else $location_options = $this->options[ $type ]->options;
			return is_array( $location_options ) ? $location_options : false;
		}


		/**
		 * @return array
		 */
		public function _get_options(){
			$R = [];
			if( is_array( $this->options ) ) foreach( $this->options as $rule_type => $rules_of_type ){
				$type_options = $this->_get_options_by_type( $rule_type );
				if( is_array( $type_options ) ) $R[ $rule_type ] = $type_options;
			}
			return $R;
		}


		/**
		 * @param array $optionsByType
		 * @return options\options[]
		 */
		public function _set_options( array $optionsByType ){
			foreach( $optionsByType as $option_type => $sub_options ){
				if( !is_array( $sub_options ) ){
					console::debug_warn( 'Попытка установки опций не массивом', $sub_options );
					continue;
				}
				$options = false;
				switch( $option_type ){
					case 'post_types':
						$options = $this->POST_TYPES();
						break;
					case 'taxonomies':
						$options = $this->TAXONOMIES();
						break;
					case 'users':
						$options = $this->USERS();
						break;
					case 'admin_menus':
						$options = $this->ADMIN_MENUS();
						break;
					default:
						console::debug_warn( 'Попытка установки неуществующего типа опций для локации', $option_type );
						break;
				}
				if( $options !== false ){
					foreach( $sub_options as $key => $value ){
						if( method_exists( $options, $key ) ){
							$options->{$key}( $value );
						}
					}
				}
			}
			return $this->options;
		}


		/**
		 * Set post type options
		 * @param $post_type
		 * @return post_types
		 */
		public function POST_TYPES( $post_type = null ){
			if( !isset( $this->options['post_types'] ) ) $this->options['post_types'] = new post_types( $this );
			/** @var post_types $post_types */
			$post_types = $this->options['post_types'];
			$post_types->post_type( $post_type );
			return $post_types;
		}


		/**
		 * @param null $taxonomy
		 * @return taxonomies
		 */
		public function TAXONOMIES( $taxonomy = null ){
			if( !isset( $this->options['taxonomies'] ) ) $this->options['taxonomies'] = new taxonomies( $this );
			/** @var taxonomies $taxonomies */
			$taxonomies = $this->options['taxonomies'];
			$taxonomies->taxonomy( $taxonomy );
			return $taxonomies;
		}


		/**
		 * @param string|array $roles
		 * @return options\options|users
		 */
		public function USERS( $roles = null ){
			if( !isset( $this->options['users'] ) ) $this->options['users'] = new users( $this );
			/** @var users $users */
			$users = $this->options['users'];
			$users->roles( $roles );
			return $users;
		}


		/**
		 * @param null $menu_slug
		 * @return admin_menus|options\options
		 */
		public function ADMIN_MENUS( $menu_slug = null ){
			if( !isset( $this->options['admin_menus'] ) ) $this->options['admin_menus'] = new admin_menus( $this );
			/** @var admin_menus $admin_menu */
			$admin_menu = $this->options['admin_menus'];
			$admin_menu->menu_slug( $menu_slug );
			//register_setting( fields\forms::get_option_group_id( $menu_slug ), fields\forms::get_field_input_option_name( $this->parent_field ) );
			return $admin_menu;
		}


		/**
		 * @return theme|options\options
		 */
		public function THEME(){
			if( !isset( $this->options['hiweb_theme'] ) ) $this->options['hiweb_theme'] = new theme( $this );
			return $this->options['hiweb_theme'];
		}

	}
