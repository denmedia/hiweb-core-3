<?php

	namespace {


		if( !function_exists( 'add_field_script' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\script
			 */
			function add_field_script( $id ){
				$new_field = new hiweb\fields\types\script( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class script extends field{

			protected $script_theme = 'mdn-like';


			public function script_theme( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_DIR_VENDORS . '/codemirror/lib/codemirror.css' );
				\hiweb\css( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/show-hint.css' );
				\hiweb\css( HIWEB_DIR_VENDORS . '/codemirror/theme/' . $this->script_theme . '.css' );

				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/lib/codemirror.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/anyword-hint.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/show-hint.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/xml-hint.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/html-hint.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/css-hint.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/javascript-hint.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/xml/xml.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/javascript/javascript.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/css/css.js' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/codemirror/mode/htmlmixed/htmlmixed.js' );
				ob_start();
				?>
				<textarea name="<?= $this->admin_input_get_attribute( 'name' ) ?>" id="<?= $this->id() ?>"><?= $value ?></textarea>
				<script type="text/javascript">
                    window.onload = function () {
                        var editor = CodeMirror.fromTextArea(document.getElementById("<?= $this->id() ?>"), {
                            mode: "text/html",
                            lineWrapping: true,
                            lineNumbers: true,
                            inputStyle: 'textarea',
                            extraKeys: {"Ctrl-Space": "autocomplete"},
                            theme: '<?=$this->script_theme?>'
                        });
                    };
				</script>
				<?php
				return ob_get_clean();
			}

		}
	}