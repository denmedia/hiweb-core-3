<?php

	/**
	 * @var string|int|array $item
	 */


	$main_image_src = false;
	if( isset($item) ){
		if( !is_array( $item ) ){
			$item = [ 'main' => $item, 'advance' => [] ];
		} else {
			$item = array_merge( [ 'main' => 0, 'advance' => [] ], $item );
		}
		$main_image_src = get_image( $item['main'] )->get_src( 300, 300, 1 );
	} else {
		$index = -1;
	}

?>
	<li tabindex="0" <?= !isset($item) ? 'data-source' : 'data-image-id="' . $item['main'] . '"' ?> class="attachment" style="width: <?= $this->get_col_width() ?>;">
		<input type="hidden" value="<?= $item['main'] ?>" <?= is_null( $item['main'] ) ? 'data-' : '' ?>name="<?= $this->admin_input_get_attribute( 'name', $attributes ) ?>[<?= $index ?>][main]"/>
		<div class="attachment-preview type-image subtype-png landscape">
			<div class="thumbnail">
				<div data-ctrl-item>
					<span data-click-edit=""><i class="dashicons dashicons-plus-alt"></i></span>
					<span data-click-remove=""><i class="dashicons dashicons-dismiss"></i></span>
				</div>
				<div class="centered">
					<img src="<?= $main_image_src ?>" alt="">
				</div>
			</div>
		</div>
		<ul data-advanced-items data-name="<?= $this->admin_input_get_attribute( 'name', $attributes ) ?>[<?= $index ?>][advance][]">
			<?php
				if( is_array( $item['advance'] ) ) foreach( $item['advance'] as $advance_id ){
					$advance_image_src = get_image( $advance_id )->get_src( 300, 300, 1 );
					?>
					<li data-advanced-item>
						<input type="hidden" value="<?= $advance_id ?>" name="<?= $this->admin_input_get_attribute( 'name', $attributes ) ?>[<?= $index ?>][advance][]">
						<span data-advanced-item-click-remove=""><i class="dashicons dashicons-dismiss"></i></span>
						<img src="<?= $advance_image_src ?>">
					</li>
					<?php
				}
			?>
		</ul>
	</li>
<?php
