<?php

	namespace {


		if( !function_exists( 'add_field_fontawesome' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\fontawesome\field
			 */
			function add_field_fontawesome( $id ){
				$new_field = new hiweb\fields\types\fontawesome\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\fontawesome {


		use hiweb\strings;


		class field extends \hiweb\fields\field{

			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\css( HIWEB_DIR_VENDORS . '/font-awesome-5-free/css/svg-with-js.min.css' );
				$font_awesome = \hiweb\js( HIWEB_DIR_VENDORS . '/font-awesome-5-free/js/all.min.js' );
				\hiweb\css( HIWEB_URL_CSS . '/field-fontawesome.css' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
				\hiweb\js( HIWEB_URL_ASSETS . '/js/field-fontawesome.js', [ 'jquery' ] );
				///
				ob_start();
				$this->attributes['class'] = '';
				$this->attributes['value'] = $this->VALUE()->get();
				$rnd_id = strings::rand( 10 );
				?>
				<div class="hiweb-field-fontawesome ui action input" data-dialog-title="Выбор иконки" data-rand-id="<?= $rnd_id ?>">
					<input data-placement="top" class="form-control" <?= $this->sanitize_attributes() ?> type="text" data-rand-id="<?= $rnd_id ?>"/>
					<span class="input-group-addon">
						<i class="<?= $this->VALUE()->get() ?>" aria-hidden="true"></i>
					</span>
					<!--<a href="#" data-click class="button" title="Выбрать иконку" data-rand-id="<?= $rnd_id ?>">...</a>-->
					<button class="ui icon button" data-click data-rand-id="<?= $rnd_id ?>">
						<i class="ellipsis horizontal icon"></i>
					</button>
				</div>
				<?php
				return ob_get_clean();
			}
		}


		class value extends \hiweb\fields\value{

			public $data = 'fab fa-wordpress';

		}
	}