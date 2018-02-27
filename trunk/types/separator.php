<?php

	namespace {


		if( !function_exists( 'add_field_separator' ) ){
			/**
			 * @param string $label
			 * @param string $description
			 * @return \hiweb\fields\types\separator\field
			 */
			function add_field_separator( $label = '', $description = '' ){
				$separator = new hiweb\fields\types\separator\field();
				$separator->label( $label );
				$separator->description( $description );
				hiweb\fields::register_field( $separator );
				return $separator;
			}
		}
	}

	namespace hiweb\fields\types\separator {


		class field extends \hiweb\fields\field{


			public function __construct( $id = null ){
				parent::__construct( $id );
				$this->FORM()->show_labels(false);
			}


			/**
			 * @param null $set
			 * @return field|string
			 */
			public function tag_label( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return field|string
			 */
			public function tag_description( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public $tag_label = 'h2';
			public $tag_description = 'p';


			public function html(){
				\hiweb\css( HIWEB_URL_CSS . '/field-separator.css' );
				ob_start();
				?>
				<div class="hiweb-field-separator">
				<<?= $this->tag_label ?> class="hw-field-separator-title<?= $this->get_parent_field()->description() != '' ? ' has-description' : ' no-description' ?>"><?= $this->get_parent_field()->label() ?></<?= $this->tag_label ?>>
				<?php if( $this->get_parent_field()->description() != '' ){
					?>
					<<?= $this->tag_description ?>  class="hw-field-separator-description"><?= $this->get_parent_field()->description() ?></<?= $this->tag_description ?>>
					<?php
				} ?>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}