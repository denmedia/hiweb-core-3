<?php

	use hiweb\fields\field;


	/**
	 * @var field $this
	 * @var mixed $value
	 */

?>
<tr class="form-field form-required term-name-wrap">
	<th scope="row"><label for="name"><?= $this->admin_label() ?></label></th>
	<td>
		<?= $this->admin_get_input( $this->context()->value() ) ?>
		<?php if( trim( $this->admin_description() ) != '' ){
			?><p class="description"><?= $this->admin_description() ?></p><?php
		} ?>
	</td>
</tr>