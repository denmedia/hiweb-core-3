<?php

	/**
	 * @var $this       \hiweb\fields\types\images_advanced
	 * @var $value      mixed
	 * @var $attributes array
	 */
?>
<div class="postbox hiweb-field-images-advanced" <?= $this->admin_get_input_attributes_html( $attributes ) ?> data-advanced-form-id="<?= $this->id() ?>-advanced-form">

	<?php include __DIR__ . '/form.php' ?>

	<?php include __DIR__ . '/main-ctrl.php' ?>

	<?php include __DIR__ . '/main-items.php' ?>


	<div class="clear"></div>

</div>