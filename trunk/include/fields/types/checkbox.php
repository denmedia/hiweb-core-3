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
		 * @return string
		 */
		private function get_sub_type(){
			$sub_type = [ 'bool' => 'checkbox', 'check' => 'checkbox', 'checkbox' => 'checkbox', 'radio' => 'radiobutton', 'radiobutton' => 'radiobutton' ];
			return $sub_type[ $this->type ];
		}


		public function get_value(){
			return $this->value != '';
		}


		public function html(){
			if( !context::is_backend_page() ){
				console::debug_error( __( 'Can not display INPUT [' . $this->type . '], it works only in the back-End' ) );
				return '';
			}
			wp_enqueue_media();
			\hiweb\css( HIWEB_DIR_CSS . '/input-checkbox.css' );
			ob_start();
			$id = \hiweb\string\rand();
			?>
			<div class="hw-input-checkbox">
				<input type="<?= $this->get_sub_type() ?>" class="<?= $this->get_sub_type() ?>" id="<?= $this->id() ?>" name="<?= $this->name() ?>" <?= $this->value() ? 'checked="checked"' : '' ?>>
				<label for="<?= $this->tags['name'] ?>"><?= $this->attributes( 'label' ) ?></label>
			</div>
			<?php
			return ob_get_clean();
		}

	}