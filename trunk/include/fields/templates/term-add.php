<?php

	use hiweb\fields\field;


	/**
	 * @var field $this
	 * @var mixed $value
	 */

?>
<div class="form-field term-<?= $this->id() ?>-wrap">
	<label for="<?= $this->admin_input_name() ?>"><?= $this->admin_label() ?></label>
	<?= $this->admin_get_input() ?>
	<?php if( trim( $this->admin_description() ) != '' ){
		?><p><?= $this->admin_description() ?></p><?php
	} ?>
</div>