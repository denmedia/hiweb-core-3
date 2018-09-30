<?php

	namespace {


		if ( ! function_exists( 'add_field_editor' ) ) {
			/**
			 * @param $id
			 *
			 * @return \hiweb\fields\types\editor\field
			 */
			function add_field_editor( $id ) {
				$new_field = new hiweb\fields\types\editor\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\editor {


		use function hiweb\css;
		use function hiweb\js;


		class field extends \hiweb\fields\field{

			protected $settings = [];


			/**
			 * @param null $set
			 *
			 * @return $this|null
			 */
			public function settings( $set = null ) {
				return $this->set_property( $set );
			}


			protected function get_input_class() {
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class() {
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public function html() {
				css( HIWEB_DIR_VENDORS . '/froala_editor/css/froala_editor.min.css' );
				css( HIWEB_DIR_VENDORS . '/froala_editor/css/froala_style.min.css' );
				css( HIWEB_DIR_VENDORS . '/froala_editor/css/themes/gray.min.css' );
				js( HIWEB_DIR_VENDORS . '/font-awesome-5/js/all.min.js' );
				$froala_editor = js( HIWEB_DIR_VENDORS . '/froala_editor/js/froala_editor.min.js', [ 'jquery' ] );
				//Plugins
				$plugins_js_path  = HIWEB_DIR_VENDORS . '/froala_editor/js/plugins/';
				$plugins_css_path = HIWEB_DIR_VENDORS . '/froala_editor/css/plugins/';
				js( $plugins_js_path . 'paragraph_format.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'align.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'char_counter.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'char_counter.min.css' );
				js( $plugins_js_path . 'code_beautifier.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'code_view.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'code_view.min.css' );
				js( $plugins_js_path . 'colors.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'colors.min.css' );
				js( HIWEB_DIR_VENDORS . '/froala_editor/js/third_party/embedly.min.js', [ 'jquery', $froala_editor ] );
				css( HIWEB_DIR_VENDORS . '/froala_editor/css/third_party/embedly.css' );
				js( $plugins_js_path . 'entities.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'font_size.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'fullscreen.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'fullscreen.min.css' );
				js( $plugins_js_path . 'help.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'help.min.css' );
				js( $plugins_js_path . 'link.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'lists.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'paragraph_format.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'quote.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'special_characters.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'special_characters.min.css' );
				js( $plugins_js_path . 'table.min.js', [ 'jquery', $froala_editor ] );
				css( $plugins_css_path . 'table.min.css' );
				js( $plugins_js_path . 'url.min.js', [ 'jquery', $froala_editor ] );
				js( $plugins_js_path . 'word_paste.min.js', [ 'jquery', $froala_editor ] );
				//Lang
				js( HIWEB_DIR_VENDORS . '/froala_editor/js/languages/ru.min.js', [ 'jquery', $froala_editor ] );
				js( HIWEB_URL_JS . '/field-editor.js', [ 'jquery', $froala_editor ], true );
				css( HIWEB_URL_CSS . '/field-editor.css' );
				///
				ob_start();
				?>
				<div class="hiweb-field-editor">
					<textarea <?= $this->sanitize_attributes() ?>><?= $this->VALUE()->get() ?></textarea>
				</div>
				<?php
				return ob_get_clean();
			}

		}


		class value extends \hiweb\fields\value{

			public function get_content() {
				return apply_filters( 'the_content', $this->get_sanitized() );
			}


		}
	}