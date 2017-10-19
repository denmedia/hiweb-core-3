<?php

	namespace {


		if( !function_exists( 'add_field_separator' ) ){
			function add_field_separator(){
				$separator = new hiweb\fields\types\separator();
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class separator extends field{

			//use hw_hidden_methods_props;

			private $global_id;
			private $label;
			private $description;
			private $tag_label = 'h2';
			private $tag_description = 'p';


			/**
			 * @param null $set
			 * @return separator|string
			 */
			public function tag_label( $set = null ){
				if( is_null( $set ) ){
					return $this->tag_label;
				}
				$this->tag_label = $set;
				return $this;
			}


			/**
			 * @param null $set
			 * @return separator|string
			 */
			public function tag_description( $set = null ){
				if( is_null( $set ) ){
					return $this->tag_description;
				}
				$this->tag_description = $set;
				return $this;
			}


			/**
			 * echo (print) separator html
			 * @param null  $value
			 * @param array $attributes
			 * @return string
			 */
			public function admin_get_input( $value = null, $attributes = [] ){
				ob_start();
				?>
				<div class="hw_field_separator">
				<<?= $this->tag_label() ?> class="hw-field-separator-title<?= $this->admin_description() != '' ? ' has-description' : ' no-description' ?>"><?= $this->admin_label() ?></<?= $this->tag_label() ?>>
				<?php if( $this->admin_description() != '' ){
					?>
					<<?= $this->tag_description() ?>  class="hw-field-separator-description"><?= $this->admin_description() ?></<?= $this->tag_description() ?>>
					<?php
				} ?>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}