<?php

	namespace {


		if( !function_exists( 'add_field_textarea' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\textarea\field
			 */
			function add_field_textarea( $id ){
				$new_field = new hiweb\fields\types\textarea\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\textarea {


		class field extends \hiweb\fields\field{

			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


			/**
			 * @param string $placeholder
			 * @return $this
			 */
			public function placeholder( $placeholder ){
				$this->INPUT()->attributes['placeholder'] = $placeholder;
				return $this;
			}


		}


		class  input extends \hiweb\fields\input{

			public function html(){
				\hiweb\css( HIWEB_URL_CSS . '/field-textarea.css' );
				return '<textarea class="hiweb-field-textarea" ' . $this->sanitize_attributes() . '>' . $this->VALUE()->get() . '</textarea>';
			}

		}


		class value extends \hiweb\fields\value{

			public function get_content(){
				return nl2br( $this->get() );
			}


		}
	}