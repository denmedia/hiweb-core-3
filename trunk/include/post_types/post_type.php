<?php

	namespace hiweb\post_types;


	class post_type{

		private $_type;
		/** @var \WP_Error|\WP_Post_Type */
		public $wp_post_type;
		private $args = [
			'label' => null,
			'labels' => [],
			'description' => '',
			'public' => true,
			'hierarchical' => false,
			'exclude_from_search' => null,
			'publicly_queryable' => null,
			'show_ui' => true,
			'show_in_menu' => null,
			'show_in_nav_menus' => null,
			'show_in_admin_bar' => null,
			'menu_position' => null,
			'menu_icon' => 'dashicons-sticky',
			'capability_type' => 'post',
			'capabilities' => [],
			'map_meta_cap' => null,
			'supports' => [],
			'register_meta_box_cb' => null,
			'taxonomies' => [],
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true,
			'can_export' => true,
			'delete_with_user' => null,
			'_builtin' => false,
			'_edit_link' => 'post.php?post=%d',
		];
		///////PROPS
		private $label;
		private $labels = [];
		private $description;
		private $public;
		private $hierarchical;
		private $exclude_from_search;
		private $publicly_queryable;
		private $show_ui;
		private $show_in_menu;
		private $show_in_nav_menus;
		private $show_in_admin_bar;
		private $menu_position;
		private $menu_icon;
		private $capability_type;
		private $capabilities;
		private $map_meta_cap;
		private $supports;
		private $register_meta_box_cb;
		private $taxonomies;
		private $has_archive;
		private $rewrite;
		private $query_var;
		private $can_export;
		private $delete_with_user;
		private $_builtin;
		private $_edit_link;

		private $columns_manager_thumbnail = false;


		public function __construct( $post_type ){
			$this->_type = sanitize_file_name( strtolower( $post_type ) );
			$this->label = $post_type;
			$this->set_props();
		}


		public function get_args(){
			return $this->args;
		}


		/**
		 * Set argument value
		 * @param string $arg_name
		 * @param mixed $value
		 * @return post_type|mixed|null
		 */
		public function set_arg( $arg_name, $value = null ){
			if( is_null( $value ) ){
				return array_key_exists( $arg_name, $this->args ) ? $this->args[ $arg_name ] : null;
			} else {
				$this->args[ $arg_name ] = $value;
				return $this;
			}
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function description( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function public_( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function hierarchical( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function exclude_from_search( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return $this
		 */
		public function publicly_queryable( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_ui( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_in_menu( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_in_nav_menus( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_in_admin_bar( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function menu_position( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function menu_icon( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function capability_type( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function map_meta_cap( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function supports( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function register_meta_box_cb( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function taxonomies( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function has_archive( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function rewrite( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function query_var( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function can_export( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function _edit_link( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function _builtin( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function delete_with_user( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}

		///////


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function label( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function labels( $set = null ){
			return $this->set_arg(__FUNCTION__, $set);
		}


		/**
		 * @param string $key
		 * @param string $value
		 * @return $this
		 */
		public function labels_set( $key = 'name', $value = '' ){
			if( is_object( $this->labels ) ) $this->labels->{$key} = $value; else $this->labels[ $key ] = $value;
			return $this;
		}


		public function __call( $name, $arguments ){
			switch( $name ){
				case 'add_action_init_create':
					$this->add_action_init_create();
					break;
			}
		}


		/**
		 * @return string
		 */
		public function type(){
			return $this->_type;
		}


		/**
		 * Возвращает массив установок
		 * @return array
		 */
		public function props(){
			$R = [];
			foreach( $this->args as $key => $def_value ){
				$R[ $key ] = ( !property_exists( $this, $key ) || is_null( $this->{$key} ) ) ? $def_value : $this->{$key};
			}
			return $R;
		}


		/**
		 * @return WP_Error|WP_Post_Type
		 */
		public function get(){
			return $this->wp_post_type;
		}


		/**
		 * @param string|int $id
		 * @param hw_post_type_meta_boxes $hiweb_meta_boxes
		 * @return hw_post_type_meta_boxes
		 */
		public function add_meta_box( $id, $hiweb_meta_boxes = null ){
			if( !isset( $this->_meta_boxes[ $id ] ) ){
				if( $hiweb_meta_boxes[ $id ] instanceof hw_post_type_meta_boxes ) $this->_meta_boxes = $hiweb_meta_boxes; else $this->_meta_boxes[ $id ] = new hw_post_type_meta_boxes( $id );
			}
			return $this->_meta_boxes[ $id ];
		}


		/**
		 * @param $name
		 * @return hw_taxonomy
		 */
		public function add_taxonomy( $name ){
			if( !isset( $this->_taxonomies[ $name ] ) ){
				$this->_taxonomies[ $name ] = hiweb()->taxonomies()->give( $name );
				$this->_taxonomies[ $name ]->object_type( $this->_type );
			}
			return $this->_taxonomies[ $name ];
		}


		/**
		 * @return hw_meta_boxes[]
		 */
		public function meta_boxes(){
			return $this->_meta_boxes;
		}


		/**
		 * @param bool $set
		 * @return post_type
		 */
		public function columns_manager_thumbnail( $set = true ){
			if( $set ){
				hiweb()->tools()->thumbnail_upload()->post_type( $this->type() );
			} else {
				hiweb()->tools()->thumbnail_upload()->remove_post_type( $this->type() );
			}
			return $this;
		}


		private function set_props(){
			if( post_type_exists( $this->_type ) ){
				$props = (array)get_post_type_object( $this->_type );
				foreach( $props as $key => $val ){
					if( property_exists( $this, $key ) ){
						if( $key != 'label' && $key != 'labels' ){
							$this->{$key} = $val;
						}
					}
				}
			}
		}


		/**
		 * Процедура регистрации типа поста
		 * @return \WP_Error|\WP_Post_Type
		 */
		private function add_action_init_create(){
			if( post_type_exists( $this->_type ) ){
				global $wp_post_types, $_wp_post_type_features;

				foreach( $wp_post_types[ $this->_type ] as $key => $val ){
					if( property_exists( $this, $key ) ){
						if( $key == 'label' ){
							$wp_post_types[ $this->_type ]->{$key} = __( $this->{$key} );
						} elseif( $key == 'labels' ) {
							if( is_object( $val ) ) foreach( $val as $label => $name ){
								if( isset( $this->labels[ $label ] ) ){
									$wp_post_types[ $this->_type ]->{$key}->{$label} = $this->labels[ $label ];
								}
							}
						} else {
							$wp_post_types[ $this->_type ]->{$key} = $this->{$key};
						}
					}
				}
				//If PT exist
				if( is_array( $this->supports ) && count( $this->supports ) > 0 ) foreach( $_wp_post_type_features[ $this->_type ] as $support => $value ){
					if( !array_key_exists( $support, array_flip( $this->supports ) ) ) unset( $_wp_post_type_features[ $this->_type ][ $support ] );
				}
			} else {
				//Register PT
				$this->wp_post_type = register_post_type( $this->_type, $this->props() );
			}
			return $this->get();
		}


	}
