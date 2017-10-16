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

			private $form_template;


			/**
			 * @return string
			 */
			public function global_id(){
				return $this->global_id;
			}


			/**
			 * Установить/получить имя поля
			 * @param null $set
			 * @return separator|string
			 */
			public function label( $set = null ){
				if( is_null( $set ) ){
					return $this->label;
				}
				$this->label = $set;
				return $this;
			}


			/**
			 * Установить/получить пояснение для поля
			 * @param null $set
			 * @return separator|string
			 */
			public function description( $set = null ){
				if( is_null( $set ) ){
					return $this->description;
				}
				$this->description = $set;
				return $this;
			}


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
			 * @param null $set
			 * @return separator|string
			 */
			public function form_template( $set = null ){
				if( is_string( $set ) && trim( $set ) != '' ){
					$this->form_template = $set;
					return $this;
				} else {
					return $this->form_template;
				}
			}


			/**
			 * @return string
			 */
			public function html(){
				ob_start();
				$this->the();
				return ob_get_clean();
			}


			/**
			 * echo (print) separator html
			 * @param null $value
			 */
			public function the( $value = null ){
				?>
				<div class="hw_field_separator">
				<<?= $this->tag_label() ?> class="hw-field-separator-title<?= $this->description() != '' ? ' has-description' : ' no-description' ?>"><?= $this->label() ?></<?= $this->tag_label() ?>>
				<?php if( $this->description() != '' ){
					?>
					<<?= $this->tag_description() ?>  class="hw-field-separator-description"><?= $this->description() ?></<?= $this->tag_description() ?>>
					<?php
				} ?>
				</div>
				<?php
			}

		}
	}