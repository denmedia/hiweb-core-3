<?php

	use hiweb\fields\field;


	/**
	 * @var field $field
	 * @var mixed $value
	 * @var array $attributes
	 */

?>
<fieldset class="<?= $field->admin_fieldset_wrap_class() ?>">
	<div class="<?= $field->admin_input_wrap_class() ?>"><?= $field->admin_get_input( $value, $attributes ) ?></div>
</fieldset>