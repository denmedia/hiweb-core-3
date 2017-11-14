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

			protected $settings = [];


			public function get_value_content( $value, $arg_1 = null, $arg_2 = null, $arg_3 = null ){
				return apply_filters( 'the_content', $value );
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\js( HIWEB_DIR_JS . '/field-editor.js' );
				ob_start();
				add_filter( 'the_editor', [ $this, 'editor_html_filter' ], 10, 2 );
				wp_editor( $value, array_key_exists( 'id', $attributes ) ? $attributes['id'] : $this->global_id(), $this->settings );
				remove_filter( 'the_editor', [ $this, 'editor_html_filter' ] );
				return ob_get_clean();
			}


			public function editor_html_filter( $html, $attributes = null ){
				$id = $this->global_id();
				if( preg_match( '/<textarea .*id=[\"|\\\']([\w\d-]+)[\"|\\\']/i', $html, $maths ) > 0 ){
					$id = $maths[1];
				}
				ob_start();
				?>
				<div id="wp-<?= $id ?>-editor-container" class="wp-editor-container">
					<div id="qt_<?= $id ?>_toolbar" class="quicktags-toolbar"></div>
					<textarea class="wp-editor-area" rows="10" autocomplete="off" cols="40" name="<?= $this->admin_input_get_attribute( 'name' ) ?>" id="<?= $id ?>">%s</textarea>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}