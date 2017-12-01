<?php

	/**
	 * @var int|string|array $value
	 */

?>
<ul tabindex="-1" class="attachments">
	<li class="attachment" data-ctrl-sub="left" style="width: <?= $this->get_col_width() ?>;">
		<div class="attachment-preview">
			<div class="thumbnail">
				<div data-ctrl-item>
					<i class="dashicons dashicons-plus-alt"></i>
				</div>
			</div>
		</div>
	</li>
	<?php include __DIR__.'/main-items-item.php'; ?>
	<div class="items">
		<?php
			if( $this->have_images( $value ) ){
				foreach( $this->value_sanitize( $value ) as $index => $item ){
					include __DIR__.'/main-items-item.php';
				}
			}
		?>
	</div>
	<li class="attachment" data-ctrl-sub="right" style="width: <?= $this->get_col_width() ?>;">
		<div class="attachment-preview">
			<div class="thumbnail">
				<div data-ctrl-item>
					<i class="dashicons dashicons-plus-alt"></i>
				</div>
			</div>
		</div>
	</li>
</ul>