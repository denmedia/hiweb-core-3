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
				ob_start();
				$rnd_id = $this->global_id() . '_' . \hiweb\strings::rand( 5 );
				wp_enqueue_code_editor( [
					'mode' => "text/html",
					'lineWrapping' => true,
					'lineNumbers' => true,
					'inputStyle' => 'textarea',
					'extraKeys' => [ "Ctrl-Space" => "autocomplete" ],
					'theme' => $this->script_theme
				] );
				?>
				<textarea name="<?= $this->name() ?>" id="<?= $rnd_id ?>"><?= $this->VALUE()->get() ?></textarea>
				<script type="text/javascript">
                    window.onload = function () {
                        wp.codeEditor.initialize("<?= $rnd_id ?>", {
                            mode: "text/html",
                            lineWrapping: true,
                            lineNumbers: true,
                            inputStyle: 'textarea',
                            extraKeys: {"Ctrl-Space": "autocomplete"},
                            theme: '<?=$this->script_theme?>'
                        });
                        //var editor = CodeMirror.fromTextArea(document.getElementById("<?//= $this->global_id() ?>//"), {
                        //    mode: "text/html",
                        //    lineWrapping: true,
                        //    lineNumbers: true,
                        //    inputStyle: 'textarea',
                        //    extraKeys: {"Ctrl-Space": "autocomplete"},
                        //    theme: '<?//=$this->script_theme?>//'
                        //});
                    };
				</script>
				<?php
				return ob_get_clean();
			}


		}
	}