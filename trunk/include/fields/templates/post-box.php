<?php

	use hiweb\fields\field;


	/**
	 * @var field $this
	 * @var mixed $value
	 */

?>
<div class="<?= $this->admin_fieldset_wrap_class() ?>" data-field-id="<?= $this->id() ?>" data-field-global-id="<?= $this->global_id() ?>">
	<p class="name"><?= $this->admin_label() ?></p>
	<?= $this->admin_get_input( $value ) ?>
	<?php
		if( trim( $this->admin_description() ) != '' ){
			?>
			<p class="description"><?= $this->admin_description() ?></p>
			<?php
		}
	?>
</div>