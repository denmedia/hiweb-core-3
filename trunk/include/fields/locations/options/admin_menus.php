<?php

	namespace hiweb\fields\locations\options;


	use hiweb\fields\field;
	use hiweb\fields\forms;


	class admin_menus extends options{

		public function menu_slug( $set = '' ){
			$field = $this->get_location()->field;
			if( $field instanceof field ){
				register_setting( $set, forms::get_field_input_option_name( $field ) );
			}
			return $this->set_option( 'menu_slug', $set );
		}

	}