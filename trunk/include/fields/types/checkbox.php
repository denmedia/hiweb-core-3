<?php

	namespace hiweb\fields\field\types;


	use hiweb\console;
	use hiweb\context;
	use hiweb\fields\field\type;
	use hiweb\fields\field\types;


	types::register( 'bool', 'checkbox' );
	types::register( 'check', 'checkbox' );
	types::register( 'checkbox', 'checkbox' );
	types::register( 'radio', 'checkbox' );
	types::register( 'radiobutton', 'checkbox' );


	class checkbox extends type{

		/**
		 * @param mixed $value
		 * @return bool
		 */
		public function sanitize( $value ){
			return !( is_null( $value ) || trim( $value ) == '' );
		}


		/**
		 * @return string
		 */
		private function get_sub_type(){
			$sub_type = [ 'bool' => 'checkbox', 'check' => 'checkbox', 'checkbox' => 'checkbox', 'radio' => 'radiobutton', 'radiobutton' => 'radiobutton' ];
			return $sub_type[ $this->type ];
		}


		public function get_input(){
			if( !context::is_backend_page() ){
				console::debug_error( __( 'Can not display INPUT [' . $this->type . '], it works only in the back-End' ) );
				return '';
			}
			wp_enqueue_media();
			\hiweb\css( HIWEB_DIR_CSS . '/input-checkbox.css' );
			ob_start();
			$this->tags['type'] = $this->get_sub_type();
			$this->tags['class'] = $this->get_sub_type();
			$this->tags['id'] = \hiweb\string\rand();
			?>
			<div class="hw-input-checkbox">
				<input <?= $this->get_tags() ?> <?= $this->value() ? 'checked="checked"' : '' ?>>
				<label for="<?= $this->tags['name'] ?>"><?= $this->field->backend()->label() ?></label>
			</div>
			<?php
			return ob_get_clean();
		}

	}