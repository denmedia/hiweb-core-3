<?php

	namespace hiweb\fields\options;


	use hiweb\fields;
	use hiweb\fields\field;
	use hiweb\fields\options;
	use hiweb\fields\separator;


	class location{

		public $rules = [];
		public $rulesId = '';
		public $globalId = '';
		/** @var field[]|separator[] */
		private $fields = [];
		/** @var  post_type */
		private $post_type;
		/** @var  taxonomy */
		private $taxonomy;
		/** @var  user */
		private $users;
		/** @var  options_page */
		private $options_page;
		/** @var admin_menu */
		private $admin_menu;


		/**
		 * fields_location constructor.
		 * @internal param field|separator $field
		 */
		public function __construct(){
			$this->globalId = spl_object_hash( $this );
		}


		/**
		 * @return string
		 */
		public function global_id(){
			return $this->globalId;
		}


		public function add_field( $fieldOrFields ){
			if( is_array( $fieldOrFields ) ){
				$R = [];
				foreach( $fieldOrFields as $index => $field ){
					$R[ $index ] = $this->add_field( $field );
				}
				return $R;
			} elseif( fields::is_field( $fieldOrFields ) ) {
				/** @var field|separator $fieldOrFields */
				$this->fields[ $fieldOrFields->global_id() ] = $fieldOrFields;
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
		 * Set post type options
		 * @param $post_type
		 * @return mixed
		 */
		public function post_type( $post_type ){
			if( !isset( $this->rules['post_type'][ $post_type ] ) ) $this->rules['post_type'][ $post_type ] = new post_type( $this );
			return $this->rules['post_type'][ $post_type ];
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
