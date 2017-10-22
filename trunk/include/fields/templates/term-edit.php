<?php

	use hiweb\fields\field;


	/**
	 * @var field $field
	 * @var mixed $value
	 * @var array $attributes
	 */

?>
<tr class="form-field form-required term-name-wrap">
	<th scope="row"><label for="<?= $field->admin_input_name() ?>"><?= $field->admin_label() ?></label></th>
	<td>
		<?= $field->admin_get_input( $field->context()->value() ) ?>
		<?php if( trim( $field->admin_description() ) != '' ){
			?><p class="description"><?= $field->admin_description() ?></p><?php
		} ?>
	</td>
</tr>