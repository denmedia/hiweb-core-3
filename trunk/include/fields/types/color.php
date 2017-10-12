<?php

	use hiweb\fields\field\type;


	\hiweb\fields\field\types::register( 'color', __NAMESPACE__ . '\color' );


	class color extends type{

		public function the_input(){
			$this->tags['data-type-color'] = '';
			\hiweb\js( HIWEB_DIR_JS . '/input-color.js' );
			\hiweb\js( HIWEB_DIR_VENDORS . '/tinyColorPicker/jqColorPicker.min.js' );
			parent::the_input();
			return ob_get_clean();
		}

	}