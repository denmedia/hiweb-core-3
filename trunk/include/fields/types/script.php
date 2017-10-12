<?php

	hiweb()->inputs()->register_type( 'script', 'hw_input_script' );


	class hw_input_script extends hw_input{

		public function html(){
			hiweb()->css( HIWEB_DIR_VENDORS . '/codemirror/lib/codemirror.css' );
			hiweb()->css( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/show-hint.css' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/lib/codemirror.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/show-hint.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/xml-hint.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/addon/hint/html-hint.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/mode/xml/xml.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/mode/javascript/javascript.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/mode/css/css.js' );
			hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/mode/htmlmixed/htmlmixed.js' );
			//hiweb()->js( HIWEB_DIR_VENDORS . '/codemirror/mode/htmlmixed/htmlmixed.js' );
			ob_start();
			?>
			<textarea name="<?= $this->name() ?>" id="<?= $this->id() ?>"><?= $this->value() ?></textarea>
			<script type="text/javascript">
                window.onload = function () {
                    var editor = CodeMirror.fromTextArea(document.getElementById("<?= $this->id() ?>"), {
                        mode: "text/html",
                        lineWrapping: true,
                        lineNumbers: true,
                        inputStyle: 'textarea',
                        extraKeys: {"Ctrl-Space": "autocomplete"}
                    });
                };
			</script>
			<?php
			return ob_get_clean();
		}

	}