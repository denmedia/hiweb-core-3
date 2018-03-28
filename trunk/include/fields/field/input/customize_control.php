<?php

	namespace hiweb\fields\field\input;


	use hiweb\console;
	use hiweb\fields\field;
	use hiweb\fields\input;


	class customize_control extends \WP_Customize_Control{


		public $type = '';

		private $parent_field;
		private $input;


		/**
		 * theme constructor.
		 * @param $manager
		 * @param $id
		 * @param array $args
		 */
		public function __construct( $manager, $id, $args = [] ){
			parent::__construct( $manager, $id, $args );
		}


		/**
		 * @param null $set
		 * @return $this
		 */
		public function parent_field( $set = null ){
			if( is_null( $set ) ){
				return $this->parent_field;
			} elseif( $set instanceof field ) {
				$this->parent_field = $set;
				$this->type = $set->get_type();
			}
			return $this;
		}


		public function render_content(){
			if( !$this->parent_field instanceof field ){
				console::debug_warn( 'Ошибка рендера инпута для секции настроек темы' );
			} else {
				if( !$this->input instanceof input ){
					$this->input = clone $this->parent_field->INPUT();
					$this->input->VALUE()->set( $this->value() );
				}
				$this->input->attributes['id'] = '_customize-input-' . $this->parent_field->id();
				$this->input->attributes['data-customize-setting-link'] = $this->parent_field->id();
				unset( $this->input->attributes['name'] );
				if( $this->parent_field->FORM()->show_labels() ){
					?>
					<label for="<?= $this->parent_field->id() ?>">
					<span class="customize-control-title"><?= $this->parent_field->label() ?></span>
					<span class="description customize-control-description"><?= $this->parent_field->description() ?></span>
					<?php
				}
				///
				echo $this->input->html();
				///
				if( $this->parent_field->FORM()->show_labels() ){
					?>
					</label>
					<?php
				}
			}
		}
	}