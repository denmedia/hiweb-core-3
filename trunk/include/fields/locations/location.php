<?php

	namespace hiweb\fields\locations;


	use hiweb\fields;
	use hiweb\fields\field;
	use hiweb\fields\separator;
	use hiweb\fields\locations\options\post_type;


	class location{

		/** @var options\options[] */
		public $options = [];
		public $rulesId = '';
		/** @var field[]|separator[] */
		private $fields = [];


		/**
		 * @return string
		 */
		public function global_id(){
			return spl_object_hash( $this );
		}


		/**
		 * @param $fieldOrFields
		 * @return array|bool
		 */
		public function add_field( $fieldOrFields ){
			if( is_array( $fieldOrFields ) ){
				$R = [];
				foreach( $fieldOrFields as $index => $field ){
					$R[ $index ] = $this->add_field( $field );
				}
				return $R;
			} elseif( fields::is_field( $fieldOrFields ) ) {
				/** @var field|separator $fieldOrFields */
				$this->fields[ $fieldOrFields->id() ] = $fieldOrFields;
				return true;
			} else {
				return false;
			}
		}


		/**
		 * @return field[]|separator[]
		 */
		public function get_fields(){
			return $this->fields;
		}


		/**
		 * @param string $type
		 * @return array|bool
		 */
		public function get_options_by_type( $type = 'post_types' ){
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
		public function get_options(){
			$R = [];
			if( is_array( $this->options ) ) foreach( $this->options as $rule_type => $rules_of_type ){
				$type_options = $this->get_options_by_type( $rule_type );
				if( is_array( $type_options ) ) $R[ $rule_type ] = $type_options;
			}
			return $R;
		}


		/**
		 * Set post type options
		 * @param $post_type
		 * @return post_type
		 */
		public function post_types( $post_type = null ){
			if( !isset( $this->options['post_types'] ) ) $this->options['post_types'] = new post_type( $this );
			/** @var post_type $post_types */
			$post_types = $this->options['post_types'];
			$post_types->post_type( $post_type );
			return $post_types;
		}


		/**
		 * Update Rules Id, register them
		 */
		/*public function update_rulesId(){
			$sections = [];
			foreach( $this->rules as $rule_group => $rules ){
				$sections[ $rule_group ] = $rule_group . ':' . json_encode( $rules, JSON_UNESCAPED_UNICODE ) . '';
			}
			$this->rulesId = rtrim( implode( '|', $sections ), ':|' );
			locations::$rules[ $this->globalId ] = $this->rules;
			locations::$rulesId[ $this->globalId ] = $this->rulesId;
		}*/

		/**
		 * @param null|string|array $post_type - массив и название типа поста, напрмиер 'page'
		 * @return post_type
		 */
		//		public function post_type( $post_type = null ){
		//			$this->rules['post_type'] = [];
		//			$this->post_type = new post_type( $this );
		//			if( is_array( $post_type ) || is_string( $post_type ) ){
		//				$this->post_type->post_type( $post_type );
		//			}
		//			return $this->post_type;
		//		}
		//
		//
		//		/**
		//		 * @param null $taxonomy
		//		 * @return taxonomy
		//		 */
		//		public function taxonomy( $taxonomy = null ){
		//			$this->rules['taxonomy'] = [];
		//			$this->update_rulesId();
		//			$this->taxonomy = new taxonomy( $this );
		//			if( is_array( $taxonomy ) || is_string( $taxonomy ) ){
		//				$this->taxonomy->name( $taxonomy );
		//			}
		//			return $this->taxonomy;
		//		}
		//
		//
		//		/**
		//		 * @return user
		//		 */
		//		public function user(){
		//			$this->rules['user'] = [];
		//			$this->users = new user( $this );
		//			return $this->users;
		//		}
		//
		//
		//		/**
		//		 * @param string $slug - 'options-general.php' or 'general', 'options-writing.php', 'options-reading.php',
		//		 * @return options_page
		//		 */
		//		public function options_page( $slug = 'options-general.php' ){
		//			$this->rules['options_page'] = [];
		//			$this->options_page = new options_page( $this );
		//			$this->options_page->slug( $slug );
		//			if( $this->get_field() instanceof field ){
		//				register_setting( \hiweb\fields\get_options_group_id( $this->options_page->get_slug() ), \hiweb\fields\get_options_field_id( $this->options_page->get_slug(), $this->get_field()->id() ) );
		//			}
		//			return $this->options_page;
		//		}
		//
		//
		//		public function admin_menu( $slug = 'theme' ){
		//			$this->rules['admin_menu'] = [];
		//			$this->admin_menu = new admin_menu( $this );
		//			$this->admin_menu->slug( $slug );
		//			if( $this->get_field() instanceof field ){
		//				register_setting( \hiweb\fields\get_options_group_id( $slug ), \hiweb\fields\get_options_field_id( $slug, $this->get_field()->id() ) );
		//			}
		//			return $this->admin_menu;
		//		}

	}
