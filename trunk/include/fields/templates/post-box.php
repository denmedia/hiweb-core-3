<?php

	use hiweb\fields\field;


	/**
	 * @var field $field
	 * @var mixed $value
	 * @var array $attributes
	 */

?>
<div class="<?= $field->admin_fieldset_wrap_class() ?>" data-field-id="<?= $field->id() ?>" data-field-global-id="<?= $field->global_id() ?>">
	<p class="name"><strong><?= $field->admin_label() ?></strong></p>
	<?= $field->admin_get_input( $value ) ?>
	<?php
		if( trim( $field->admin_description() ) != '' ){
			?>
			<p class="description"><?= $field->admin_description() ?></p>
			<?php
		}
	?>
</div>