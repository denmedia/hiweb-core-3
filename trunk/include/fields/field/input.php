<?php

	namespace hiweb\fields;


	use hiweb\console;
	use hiweb\fields;


	class input{

		/** @var field */
		private $field;
		/** @var value */
		private $value;
		/** @var array */
		public $attributes = [];
		/** @var */
		private $callback;

		private $global_id = false;
		/** @var fields\field\input\customize_control */
		private $customize_control;


		/**
		 * input constructor.
		 * @param field $field
		 * @param value $value
		 */
		public function __construct( field $field, value $value ){
			$this->field = $field;
			$this->value = $value;
			$this->global_id = fields::register_input( $this );
		}


		public function __clone(){
			$this->value = clone $this->value;
			$this->global_id = fields::register_input( $this );
		}


		/**
		 * @return field
		 */
		public function get_parent_field(){
			return $this->field;
		}


		/**
		 * @return bool|string
		 */
		public function global_id(){
			return $this->global_id;
		}


		/**
		 * @param array $attributes
		 * @return string
		 */
		protected function sanitize_attributes( $attributes = null ){
			$R = [];
			if( !is_array( $attributes ) ){
				$attributes = $this->attributes;
			}
			foreach( $attributes as $key => $val ){
				if( is_array( $val ) ) $val = json_encode( $val );
				$R[] = $key . '="' . htmlentities( $val, ENT_QUOTES, 'UTF-8' ) . '"';
			}
			return implode( ' ', $R );
		}


		/**
		 * @param null $name
		 * @return $this|mixed|null
		 */
		public function name( $name = null ){
			if( is_null( $name ) ){
				return isset( $this->attributes['name'] ) ? $this->attributes['name'] : null;
			} else {
				$this->attributes['name'] = $name;
				return $this;
			}
		}


		/**
		 * @return string
		 */
		public function html(){
			$this->attributes['value'] = $this->VALUE()->get_sanitized();
			return '<input ' . $this->sanitize_attributes( $this->attributes ) . '>';
		}


		/**
		 * @return mixed
		 */
		public function _get_callback(){
			return $this->callback;
		}


		/**
		 * Set|get callable user function
		 * @param null $callable
		 * @return $this|mixed
		 */
		final public function callback( $callable = null ){
			if( is_null( $callable ) ){
				if( is_callable( $this->callback ) ){
					return call_user_func( $this->callback, $this );
				} else {
					console::debug_warn( 'Попытка вызова функции вывода поля, но ее нет', $this->callback );
					return null;
				}
			} else $this->callback = $callable;
			return $this;
		}


		/**
		 * Set VALUE OBJECT
		 * @param value $value
		 * @return $this
		 */
		final public function _set_value( value $value ){
			$this->value = $value;
			return $this;
		}


		/**
		 * Get VALUE OBJECT
		 * @return value
		 */
		final public function VALUE(){
			return $this->value;
		}


		/**
		 * @param null $wp_customize_manager
		 * @return field\input\customize_control
		 */
		final public function THEME_CONTROL( $wp_customize_manager = null, $section_id = '' ){
			if( $wp_customize_manager instanceof \WP_Customize_Manager ){
				$this->customize_control = new fields\field\input\customize_control( $wp_customize_manager, $this->get_parent_field()->id(), [
					'section' => $section_id,
					'settings' => $this->get_parent_field()->id()
				] );
				$this->customize_control->parent_field( $this->get_parent_field() );
			}
			return $this->customize_control;
		}

	}