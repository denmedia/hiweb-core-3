<?php

	namespace {


		if( !function_exists( 'add_field_editor' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\editor\field
			 */
			function add_field_editor( $id ){
				$new_field = new hiweb\fields\types\editor\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\editor {


		use hiweb\strings;


		class field extends \hiweb\fields\field{

			protected $settings = [];


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function settings( $set = null ){
				return $this->set_property( $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\js( WPINC . '/js/tinymce/tinymce.min.js' );
				\hiweb\js( HIWEB_URL_JS . '/field-editor.js' );
				ob_start();
				$this->attributes['id'] = $this->global_id().'-'.\hiweb\strings::rand(5);
				$this->attributes['name'] = $this->name();
				$this->attributes['rows'] = 10;
				?>
				<div class="hiweb-field-editor">
					<textarea <?= $this->sanitize_attributes() ?>><?= $this->VALUE()->get() ?></textarea>
				</div>
				<?php
				return ob_get_clean();
			}

		}


		class value extends \hiweb\fields\value{

			public function get_content(){
				return apply_filters( 'the_content', $this->get_sanitized() );
			}


		}
	}