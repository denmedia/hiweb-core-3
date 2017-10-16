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


		//////FORM INPUT

		public function admin_input_wrap_class(){
			return 'hiweb-admin-input-wrap';
		}


		/**
		 * @return string
		 */
		public function admin_input_name(){
			return 'hiweb-input-' . $this->id();
		}


		/**
		 * Echo the admin input
		 * @param null $value
		 */
		final public function admin_the_input( $value = null ){
			echo $this->admin_get_input( $value );
		}


		public function admin_get_input( $value = null ){
			$R = '<input placeholder="' . htmlentities( $this->value_default(), ENT_QUOTES, 'UTF-8' ) . '" name="' . $this->admin_input_name() . '" value="' . htmlentities( $this->value_sanitize( $value ), ENT_QUOTES, 'UTF-8' ) . '">';
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
			$template_path = __DIR__ . '/templates/' . $this->template . '.php';
			if( !is_file( $template_path ) || !\is_readable( $template_path ) ){
				console::debug_warn( 'Не удалось найти шаблон для поля', $this->template );
				$template_path = __DIR__ . '/templates/default.php';
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