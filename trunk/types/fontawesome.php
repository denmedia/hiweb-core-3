<?php

	namespace {


		if ( ! function_exists( 'add_field_fontawesome' ) ) {
			/**
			 * @param $id
			 *
			 * @return \hiweb\fields\types\fontawesome\field
			 */
			function add_field_fontawesome( $id ) {
				$new_field = new hiweb\fields\types\fontawesome\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\fontawesome {


		class field extends \hiweb\fields\field{

			protected function get_input_class() {
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class() {
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public function html() {
				\hiweb\css( HIWEB_DIR_VENDORS . '/font-awesome-5-free/svg-with-js/css/fa-svg-with-js.css' );
				$font_awesome = \hiweb\js( HIWEB_DIR_VENDORS . '/font-awesome-5-free/svg-with-js/js/fontawesome-all.min.js' );
				\hiweb\css( HIWEB_URL_VENDORS . '/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css' );
				\hiweb\css( HIWEB_URL_CSS . '/field-fontawesome.css' );
				$js = \hiweb\js( HIWEB_URL_VENDORS . '/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js', [ 'jquery', $font_awesome ] );
				\hiweb\js( HIWEB_URL_ASSETS . '/js/field-fontawesome.js', [ 'jquery', $js ] );
				///
				ob_start();
				$this->attributes['class'] = '';
				$this->attributes['value'] = $this->VALUE()->get();
				?>
				<div class="hiweb-field-fontawesome input-group">
					<input data-placement="top" class="form-control" <?= $this->sanitize_attributes() ?> type="text"/>
					<span class="input-group-addon">
						<i class="<?= $this->VALUE()->get() ?>"></i>
					</span>
				</div>
				<?php
				return ob_get_clean();
			}
		}


		class value extends \hiweb\fields\value{

			public $data = 'fab fa-wordpress';

		}
	}