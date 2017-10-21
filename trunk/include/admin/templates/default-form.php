<?php

	/**
	 * @var \hiweb\admin\pages\page_abstract $this
	 */

	use hiweb\fields\locations\locations;

	global $new_whitelist_options, $wp_registered_settings;
	\hiweb\dump([$new_whitelist_options, $wp_registered_settings]);

?>
<div class="wrap">
	<h1><?= $this->page_title() ?></h1>
	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php
			settings_fields( $this->menu_slug() );
			do_settings_sections( $this->menu_slug() );
			///
			$fields = locations::get_fields_by_contextObject( $this->menu_slug() );
			\hiweb\fields\forms::the_form_by_contextObject( $this->menu_slug(), 'admin_menu' );
		?>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>"/>
		</p>
	</form>
</div>