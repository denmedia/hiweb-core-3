<?php

	namespace {


		if( !function_exists( 'add_field_separator' ) ){
			/**
			 * @param string $label
			 * @param string $description
			 * @return \hiweb\fields\types\separator
			 */
			function add_field_separator( $label = '', $description = '' ){
				$separator = new hiweb\fields\types\separator();
				$separator->admin_label($label);
				$separator->admin_description($description);
				hiweb\fields::register_field( $separator );
				return $separator;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\fields\field;


		class separator extends field{

			private $tag_label = 'h2';
			private $tag_description = 'p';


			public function __construct( $id = null ){
				parent::__construct( $id );
				$this->admin_template( 'separator' );
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
			 * echo (print) separator html
			 * @param null $value
			 * @param array $attributes
			 * @return string
			 */
			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_URL_CSS . '/field-separator.css' );
				ob_start();
				?>
				<div class="hiweb-field-separator">
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