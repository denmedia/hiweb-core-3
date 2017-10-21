<?php

	use hiweb\fields\field;


	/**
	 * @var field $field
	 * @var mixed $value
	 * @var array $attributes
	 */

?>
<tr class="<?=$field->admin_fieldset_wrap_class()?>">
	<th scope="row"><label for="<?= hiweb\fields\forms::get_field_input_option_name( $field ) ?>"><?= $field->admin_label() ?></label></th>
	<td class="<?=$field->admin_input_wrap_class()?>">
		<?= $field->admin_get_input( $value, $attributes ) ?>
	</td>
</tr>