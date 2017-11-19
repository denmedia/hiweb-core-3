<?php

	namespace {


		if( !function_exists( 'add_field_checkbox' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\checkbox
			 */
			function add_field_checkbox( $id ){
				$new_field = new hiweb\fields\types\checkbox( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class checkbox extends field{

			/**
			 * @return string
			 */
			private function get_sub_type(){
				$sub_type = [ 'bool' => 'checkbox', 'check' => 'chekbox', 'checkbox' => 'checkbox', 'radio' => 'radiobutton', 'radiobutton' => 'radiobutton' ];
				return $sub_type[ $this->get_type() ];
			}


			/**
			 * @param $value
			 * @return bool
			 */
			public function value_sanitize( $value ){
				return $value != '';
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				wp_enqueue_media();
				\hiweb\css( HIWEB_DIR_CSS . '/field-checkbox.css' );
				ob_start();
				$rand_id = \hiweb\string::rand();
				?>
				<div class="hw-input-checkbox">
					<input type="<?= $this->get_sub_type() ?>" class="<?= $this->get_sub_type() ?>" id="<?= $rand_id ?>" <?= $this->admin_get_input_attributes_html( $attributes, [ 'name' ] ) ?> <?= $value ? 'checked="checked"' : '' ?>>
					<label for="<?= $rand_id ?>"><?= $this->admin_description() ?></label>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}