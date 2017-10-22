<?php

	namespace {


		if( !function_exists( 'add_field_editor' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\editor
			 */
			function add_field_editor( $id ){
				$new_field = new hiweb\fields\types\editor( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class editor extends field{

			private $settings = [];


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\js( HIWEB_DIR_JS . '/field-editor.js' );
				ob_start();
				add_filter( 'the_editor', [ $this, 'editor_html_filter' ], 10, 2 );
				wp_editor( $value, $this->id(), $this->settings );
				remove_filter( 'the_editor', [ $this, 'editor_html_filter' ] );
				return ob_get_clean();
			}


			public function editor_html_filter( $html ){
				ob_start();
				?>
				<div id="wp-<?= $this->id() ?>-editor-container" class="wp-editor-container">
					<div id="qt_<?= $this->id() ?>_toolbar" class="quicktags-toolbar"></div>
					<textarea class="wp-editor-area" rows="10" autocomplete="off" cols="40" name="<?= $this->admin_input_get_attribute( 'name' ) ?>" id="<?= $this->id() ?>">%s</textarea>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}