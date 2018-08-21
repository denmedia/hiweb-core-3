<?php

	namespace {


		if( !function_exists( 'add_field_checkbox' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\checkbox\field
			 */
			function add_field_checkbox( $id ){
				$new_field = new hiweb\fields\types\checkbox\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\checkbox {


		use function hiweb\css;


		/**
		 * Class checkbox
		 * @package hiweb\fields\types
		 */
		class field extends \hiweb\fields\field{

			protected $label_checkbox = '';


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


			/**
			 * @param null $set
			 * @return $this|null|string
			 */
			public function label_checkbox( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}

		}


		class input extends \hiweb\fields\input{

			public function html(){
				ob_start();
				css( HIWEB_URL_CSS . '/field-checkbox.css' );
				$rand_id = \hiweb\strings::rand( 10 );
				?>
				<div class="hw-input-checkbox">
					<div class="ui toggle checkbox">
						<input class="checkbox" type="checkbox" id="<?= $rand_id ?>" <?= $this->sanitize_attributes() ?> <?= $this->VALUE()->get_sanitized() ? 'checked="checked"' : '' ?>>
						<label for="<?= $rand_id ?>"><?= $this->get_parent_field()->label_checkbox() ?></label>
					</div>
				</div>
				<?php
				return ob_get_clean();
			}

		}


		class  value extends \hiweb\fields\value{

			/**
			 * @param $value
			 * @return bool|mixed
			 */
			public function sanitize( $value ){
				return (bool)$value;
			}


		}
	}