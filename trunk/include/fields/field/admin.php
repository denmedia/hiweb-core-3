<?php

	namespace hiweb\fields\field;


	use hiweb\fields\field;
	use hiweb\fields\forms;


	trait admin{

		/** @var string */
		private $label = '';
		/** @var string */
		private $label_description = '';
		/** @var string */
		private $description = '';
		/** @var string */
		private $template = '';
		/** @var bool */
		private $admin_compact_view = false;
		/** @var array */
		protected $admin_input_attributes = [];


		//////FORM INPUT


		/**
		 * @param string $attr_name
		 * @param string $attr_value
		 */
		public function admin_input_set_attributes( $attr_name, $attr_value = '' ){
			$this->admin_input_attributes[ $attr_name ] = $attr_value;
		}


		/**
		 * @param string $attr_name
		 * @param array $attributes
		 * @return mixed|null
		 */
		public function admin_input_get_attribute( $attr_name = 'id', $attributes = [] ){
			return array_key_exists( $attr_name, $this->admin_input_attributes ) ? $this->admin_input_attributes[ $attr_name ] : null;
		}


		/**
		 * @param null|array|string $attributes
		 * @return string
		 */
		public function admin_get_input_attributes_html( $attributes = null, $take_attributes = [] ){
			$R = [];
			if( is_string( $attributes ) ) return $attributes;
			if( is_null( $attributes ) ) $attributes = $this->admin_input_attributes;
			if( is_array( $attributes ) ){
				$attributes = array_merge( $this->admin_input_attributes, $attributes );
				foreach( $attributes as $key => $val ){
					if( is_array( $take_attributes ) && count( $take_attributes ) > 0 && !array_key_exists( $key, array_flip( $take_attributes ) ) ) continue;
					if( is_array( $val ) ) $val = json_encode( $val );
					$R[] = $key . '="' . htmlentities( $val, ENT_QUOTES, 'UTF-8' ) . '"';
				}
			}
			return implode( ' ', $R );
		}


		/**
		 * @return string
		 */
		public function admin_input_wrap_class(){
			return 'hiweb-admin-input-wrap' . ( $this->admin_compact_view() ? '-compact' : '' );
		}


		/**
		 * @param null $set
		 * @return bool|$this|null
		 */
		public function admin_compact_view( $set = null ){
			if( !is_null( $set ) ){
				$this->admin_compact_view = $set;
				return $this;
			}
			return $this->admin_compact_view;
		}


		/**
		 * @param string $prepend
		 * @param string $append
		 * @return string
		 */
		public function admin_input_name( $prepend = '', $append = '' ){
			$R = [];
			if( !empty( $prepend ) ) $R[] = $prepend;
			$R[] = $this->id();
			if( !empty( $append ) ) $R[] = $append;
			return implode( '-', $R );
		}


		/**
		 * @param null $value
		 * @param array $attributes
		 * @return string
		 */
		public function admin_get_input( $value = null, $attributes = [] ){
			$attributes_default = [
				'class' => 'hiweb-field',
				'type' => 'text',
				'placeholder' => $this->value_default(),
				'value' => $this->get_value_sanitize( $value )
			];
			$attributes = is_array( $attributes ) ? $attributes : [ $attributes ];
			$attributes_final = array_merge( $attributes_default, $this->admin_input_attributes, $attributes );
			$R = '<input ' . $this->admin_get_input_attributes_html( $attributes_final ) . '>';
			return $R;
		}


		/**
		 * Echo the admin input
		 * @param null $value
		 */
		final public function admin_the_input( $value = null ){
			echo $this->admin_get_input( $value );
		}


		//////FORM FILED


		/**
		 * @return string
		 */
		public function admin_fieldset_wrap_class(){
			return 'hiweb-admin-field-wrap' . ( $this->admin_compact_view() ? '-compact' : '' ) . ' hiweb-admin-field-wrap-' . $this->get_type();
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
		 * @param null|string $label
		 * @return $this|string
		 */
		public function admin_label_description( $label = null ){
			if( !is_null( $label ) ){
				$this->label_description = $label;
				return $this;
			} else {
				return $this->label_description;
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


		/**
		 * @param null $value
		 * @param array $attributes
		 * @return string
		 */
		public function admin_get_fieldset( $value = null, $attributes = [] ){
			/** @var field $this */
			\hiweb\css( HIWEB_URL_CSS . '/fields.css' );
			return forms::get_fieldset( $this, $value, $attributes );
		}


		final public function admin_the_field( $value = null, $attributes = [] ){
			echo $this->admin_get_fieldset( $value, $attributes );
		}

	}