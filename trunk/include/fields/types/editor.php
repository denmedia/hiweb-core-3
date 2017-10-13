<?php

	use hiweb\fields\field\type;


	\hiweb\fields\field\types::register( 'editor', __NAMESPACE__ . '\editor' );


	class editor extends type{

		public function sanitize( $value, $nl2br = false ){
			return $nl2br ? nl2br( $value ) : apply_filters( 'the_content', $value );
		}


		public function get_input(){
			\hiweb\js( HIWEB_DIR_JS . '/input-editor.js' );
			ob_start();
			add_filter( 'the_editor', [ $this, 'editor_html_filter' ], 10, 2 );
			wp_editor( $this->value(), $this->field->id(), $this->tags );
			remove_filter( 'the_editor', [ $this, 'editor_html_filter' ] );
			return ob_get_clean();
		}


		protected function editor_html_filter( $html ){
			ob_start();
			?>
			<div id="wp-<?= $this->field->id() ?>-editor-container" class="wp-editor-container">
				<div id="qt_<?= $this->field->id() ?>_toolbar" class="quicktags-toolbar"></div>
				<textarea class="wp-editor-area" rows="10" autocomplete="off" cols="40" name="<?= $this->tags['name'] ?>" id="<?= $this->field->id() ?>">%s</textarea>
			</div>
			<?php
			return ob_get_clean();
		}

	}