<?php

	namespace hiweb\fields\locations\options;


	use hiweb\fields\field;
	use hiweb\fields\forms;


	class admin_menus extends options{

		/**
		 * @param string $set
		 * @return $this
		 */
		public function menu_slug( $set = '' ) {
			require_once ABSPATH.'/wp-includes/option.php';
			$R = $this->set_option( 'menu_slug', $set );
			$field = $this->_get_parent_location()->_get_parent_field();
			if ( $field instanceof field ) {
				\register_setting( $set, forms::get_field_input_option_name( $field ) );
			}

			return $this;
		}

	}