<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	trait admin{

		/** @var string */
		private $label = '';
		/** @var string */
		private $description = '';
		/** @var string */
		private $template = 'default';
		/** @var array */
		private $admin_input_attributes = [];


		//////FORM INPUT


		/**
		 * @param string $attr_name
		 * @param string $attr_value
		 */
		public function admin_input_attributes_set( $attr_name, $attr_value = '' ){
			$this->admin_input_attributes[ $attr_name ] = $attr_value;
		}


		/**
		 * @param null|array|string $attributes
		 * @return string
		 */
		public function admin_get_input_attributes_html( $attributes = null ){
			$R = [];
			if( is_string( $attributes ) ) return $attributes;
			if( is_null( $attributes ) ) $attributes = $this->admin_input_attributes;
			if( is_array( $attributes ) ){
				foreach( $attributes as $key => $val ){
					$R[] = $key . '="' . htmlentities( $val, ENT_QUOTES, 'UTF-8' ) . '"';
				}
			}
			return implode( ' ', $R );
		}


		public function admin_input_wrap_class(){
			return 'hiweb-admin-input-wrap';
		}


		/**
		 * @param string $prepend
		 * @param string $append
		 * @return string
		 */
		public function admin_input_name( $prepend = '', $append = '' ){
			$R = [ 'hiweb-input' ];
			if( !empty( $prepend ) ) $R[] = $prepend;
			$R[] = $this->id();
			if( !empty( $append ) ) $R[] = $append;
			return implode( '-', $R );
		}


		/**
		 * Echo the admin input
		 * @param null $value
		 */
		final public function admin_the_input( $value = null ){
			echo $this->admin_get_input( $value );
		}


		/**
		 * @param null  $value
		 * @param array $attributes
		 * @return string
		 */
		public function admin_get_input( $value = null, $attributes = [] ){
			$attributes_default = [
				'type' => 'text',
				'placeholder' => $this->value_default(),
				'id' => $this->admin_input_name(),
				'name' => $this->admin_input_name(),
				'value' => $this->value_sanitize( $value )
			];
			$attributes = is_array( $attributes ) ? $attributes : [ $attributes ];
			$attributes_final = array_merge( $attributes_default, $attributes, $this->admin_input_attributes );
			$R = '<input ' . $this->admin_get_input_attributes_html( $attributes_final ) . '>';
			return $R;
		}


		//////FORM FILED

		public function admin_field_wrap_class(){
			return 'hiweb-admin-field-wrap hiweb-admin-field-wrap-' . $this->get_type();
		}


		/**
		 * @param null|string $label
		 * @return $this|string
		 */
		public function admin_label( $label = null ){
			if( !is_null( $label ) ){
				$this->label = $label;
				return $this;
			} else {
				return $this->label;
			}
		}


		/**
		 * @param null|string $description
		 * @return $this|string
		 */
		public function admin_description( $description = null ){
			if( !is_null( $description ) ){
				$this->description = $description;
				return $this;
			} else {
				return $this->description;
			}
		}


		/**
		 * @param null $template
		 * @return $this|string
		 */
		public function admin_template( $template = null ){
			if( !is_null( $template ) ){
				$this->template = $template;
				return $this;
			} else {
				return $this->template;
			}
		}


		protected function admin_get_field_class(){
			return 'hiweb-admin-the-field';
		}


		/**
		 * @param null $value
		 * @return string
		 */
		public function admin_get_field( $value = null ){
			$R = '';
			$template_path = dirname( __DIR__ ) . '/templates/' . $this->template . '.php';
			if( !is_file( $template_path ) || !\is_readable( $template_path ) ){
				console::debug_warn( 'Не удалось найти шаблон для поля', $this->template );
				$template_path = dirname( __DIR__ ) . '/templates/default.php';
			}
			if( !is_file( $template_path ) || !\is_readable( $template_path ) ){
				console::debug_error( 'Не удалось найти шаблон для поля DEFAULT', $this->template );
			} else {
				ob_start();
				include $template_path;
				$R = ob_get_clean();
			}
			return $R;
		}


		final public function admin_the_field( $value = null ){
			echo $this->admin_get_field( $value );
		}

	}