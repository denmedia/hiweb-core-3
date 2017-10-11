<?php

	use hiweb\fields\field;
	use hiweb\fields\separator;


	/** @var $fields field[] */

?>
<div class="hw-form-template-postbox"><?php
		foreach( $fields as $field ){
			if( $field instanceof field ){
				?>
				<span class="hw-form-field hw-field-<?= $field->type_name() ?>" data-field-id="<?= $field->id() ?>" data-field-global-id="<?= $field->global_id() ?>">
				<p><strong><?php echo $field->backend()->label() ?></strong></p>
					<?php $field->type()->the_input(); ?>
					<?php echo $field->backend()->description() != '' ? '<p class="description">' . $field->backend()->description() . '</p>' : ''; ?>
				</span>
				<?php
			} elseif( $field instanceof separator ) {
				?>
				<div class="hw-form-field-separator"><?php $field->the() ?></div>
				<?php
			} else {
				\hiweb\console::debug_warn( 'В шаблон передан не известный класс, вместо класса field', $field );
			}
		}

	?></div>