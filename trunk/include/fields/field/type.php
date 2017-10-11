<?php

	namespace hiweb\fields\field;


	class type{

		public $type = 'text';
		public $tags = [];
		public $value;


		/**
		 * input constructor.
		 * @param string $type
		 */
		public function __construct( $type = 'text' ){
			$this->type = $type;
		}


		/**
		 * @return string
		 */
		public function get_tags(){
			$R = [];
			if( is_array( $this->tags ) ) foreach( $this->tags as $key => $value ){
				$R[] = $key . '="' . htmlentities( is_array( $value ) ? json_encode( $value ) : $value, ENT_QUOTES, 'UTF-8' ) . '"';
			}
			return implode( ' ', $R );
		}


		/**
		 * Echo html of the input
		 */
		public function the_input(){
			$this->tags['type'] = $this->type;
			$this->tags['value'] = $this->value;
			?><input <?= $this->get_tags() ?>/><?php
		}


		/**
		 * Get html string of the input
		 * @return string
		 */
		final public function html_input(){
			ob_start();
			$this->the_input();
			return ob_get_clean();
		}

	}