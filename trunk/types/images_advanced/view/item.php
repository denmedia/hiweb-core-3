<?php
	/**
	 * @var $this \hiweb\fields\types\images_advanced
	 * @var $index int
	 * @var $item array|string|int
	 * @var $img_id int
	 */
?>
<li tabindex="0" <?= is_null( $img_id ) ? 'data-source' : 'data-image-id="' . $img_id . '"' ?> class="attachment" style="width: <?= $this->get_col_width() ?>;">
	<input type="hidden" value="<?= $img_id ?>" <?= is_null( $img_id ) ? 'data-' : '' ?>name="<?= $this->admin_input_get_attribute( 'name' ) ?>[]"/>
	<div class="attachment-preview type-image subtype-png landscape">
		<div class="thumbnail">
			<div data-ctrl-item>
				<span data-click-edit=""><i class="dashicons dashicons-plus-alt"></i></span>
				<span data-click-remove=""><i class="dashicons dashicons-dismiss"></i></span>
			</div>
			<div class="centered">
				<img src="<?= $img_src ?>" alt="">
			</div>
		</div>
	</div>
</li>