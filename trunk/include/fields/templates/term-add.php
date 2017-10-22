<?php

	use hiweb\fields\field;


	/**
	 * @var field $field
	 * @var mixed $value
	 * @var array $attributes
	 */

?>
<div class="form-field term-<?= $field->id() ?>-wrap">
	<label for="<?= $field->admin_input_name() ?>"><?= $field->admin_label() ?></label>
	<?= $field->admin_get_input() ?>
	<?php if( trim( $field->admin_description() ) != '' ){
		?><p><?= $field->admin_description() ?></p><?php
	} ?>
</div>