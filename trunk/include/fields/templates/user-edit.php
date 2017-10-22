<?php

	use hiweb\fields\field;


	/**
	 * @var field $field
	 * @var mixed $value
	 * @var array $attributes
	 */

?>
<tr class="form-field">
	<th scope="row"><label for="user_login"><?= $field->admin_label() ?></label></th>
	<td><?= $field->admin_get_input( $value ) ?></td>
</tr>