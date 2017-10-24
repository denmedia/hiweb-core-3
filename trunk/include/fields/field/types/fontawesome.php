<?php

	namespace {


		if( !function_exists( 'add_field_fontawesome' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\fontawesome
			 */
			function add_field_fontawesome( $id ){
				$new_field = new hiweb\fields\types\fontawesome( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class fontawesome extends field{

			protected $value_default = 'fa-wordpress';


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_URL_VENDORS . '/bootstrap/css/bootstrap.min.css' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/bootstrap/js/bootstrap.min.js', [ 'jquery' ] );
				\hiweb\css( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
				\hiweb\css( HIWEB_URL_VENDORS . '/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css' );
				$js = \hiweb\js( HIWEB_URL_VENDORS . '/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js', [ 'jquery' ] );
				\hiweb\js( HIWEB_URL_ASSETS . '/js/field-fontawesome.js', [ 'jquery', $js ] );
				ob_start();
				$this->admin_input_set_attributes( 'class', '' );
				?>
				<div class="hiweb-field-fontawesome input-group">
					<input data-placement="top" class="form-control" <?= $this->admin_get_input_attributes_html( $attributes ) ?> value="<?=$value?>" type="text"/>
					<span class="input-group-addon"><i class="fa <?= $value ?>"></i></span>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}