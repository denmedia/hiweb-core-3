<?php

	namespace {


		if( !function_exists( 'add_field_editor' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\editor\field
			 */
			function add_field_editor( $id ){
				$new_field = new hiweb\fields\types\editor\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\editor {


		class field extends \hiweb\fields\field{

			protected $settings = [];


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function settings( $set = null ){
				return $this->set_property( $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\js( HIWEB_DIR_JS . '/field-editor.js' );
				ob_start();
				add_filter( 'the_editor', [ $this, 'editor_html_filter' ], 10, 2 );
				wp_editor( $this->VALUE()->get(), array_key_exists( 'id', $this->attributes ) ? $this->attributes['id'] : $this->global_id(), $this->get_parent_field()->settings() );
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
					<textarea class="wp-editor-area" rows="10" autocomplete="off" cols="40" name="<?= $this->name() ?>" id="<?= $id ?>">%s</textarea>
				</div>
				<?php
				return ob_get_clean();
			}

		}


		class value extends \hiweb\fields\value{

			public function get_content(){
				return apply_filters( 'the_content', $this->get_sanitized() );
			}


		}
	}