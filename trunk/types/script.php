<?php

	namespace {


		if( !function_exists( 'add_field_script' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\script\field
			 */
			function add_field_script( $id ){
				$new_field = new hiweb\fields\types\script\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\script {


		use hiweb\strings;


		class field extends \hiweb\fields\field{


			public function script_theme( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public $script_theme = 'mdn-like';


			public function html(){
				//				\hiweb\css( HIWEB_DIR_VENDORS . '/codemirror/lib/codemirror.css' );
				//				\hiweb\css( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/show-hint.css' );
				//				\hiweb\css( HIWEB_DIR_VENDORS . '/codemirror/theme/' . $this->script_theme . '.css' );
				//
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/lib/codemirror.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/anyword-hint.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/show-hint.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/xml-hint.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/html-hint.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/css-hint.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/javascript-hint.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/xml/xml.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/javascript/javascript.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/css/css.js' );
				//				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/htmlmixed/htmlmixed.js' );
				\hiweb\css( HIWEB_DIR_ASSETS . '/css/field-script.css' );
				\hiweb\js( HIWEB_DIR_ASSETS . '/js/field-script.min.js' );
				ob_start();
				$rand_id = $this->global_id() . '_' . strings::rand( 5 );
				?>
				<div class="hiweb-field-script ui raised segment" data-rand-id="<?= $rand_id ?>" data-script-theme="<?= $this->script_theme ?>"><?php
						wp_enqueue_code_editor( [
							//'type' => "text/html",
							'file' => 'file.php',
							'lineWrapping' => true,
							'lineNumbers' => true,
							'inputStyle' => 'textarea',
							'extraKeys' => [ "Ctrl-Space" => "autocomplete" ],
							'theme' => $this->script_theme
						] );
					?>
					<textarea name="<?= $this->name() ?>" id="<?= $rand_id ?>"><?= $this->VALUE()->get() ?></textarea>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}