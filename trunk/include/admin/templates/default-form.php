<?php

	/**
	 * @var \hiweb\admin\pages\page_abstract $this
	 */

	use hiweb\fields\locations\locations;

?>
<div class="wrap">
	<h1><?= $this->page_title() ?></h1>
	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php
			settings_fields( $this->menu_slug() );
			$fields = locations::get_fields_by_contextObject( $this->menu_slug() );
			\hiweb\fields\forms::the_form_by_contextObject( $this->menu_slug(), 'admin_menu' );
		?>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>"/>
		</p>
	</form>
</div>