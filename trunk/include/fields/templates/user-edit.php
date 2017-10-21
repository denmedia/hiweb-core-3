<?php

	use hiweb\fields\field;


	/**
	 * @var field $this
	 * @var mixed $value
	 */

?>
<tr class="form-field">
	<th scope="row"><label for="user_login"><?= $this->admin_label() ?></label></th>
	<td><?= $this->admin_get_input( $value ) ?></td>
</tr>
