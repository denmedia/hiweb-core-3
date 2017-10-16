<?php

	use hiweb\fields\field;


	/**
	 * @var field $this
	 * @var mixed $value
	 */

?>
<div class="<?= $this->admin_field_wrap_class() ?>">
	<p><?= $this->admin_label() ?></p>
	<div class="<?= $this->admin_input_wrap_class() ?>"><?= $this->admin_get_input( $value ) ?></div>
	<p class="description"><?= $this->admin_description() ?></p>
</div>