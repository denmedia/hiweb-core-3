<?php
	/**
	 * @var $this \hiweb\fields\types\images_advanced
	 */
?>
<div id="<?= $this->id() ?>-advanced-form" class="hiweb-fields-images-advanced-form hidden" data-image-index="" data-images="0">
	<ul data-images>
		<!--images-->
		<li data-advanced-source data-advanced-item>
			<input type="hidden"/>
			<span data-advanced-item-click-remove=""><i class="dashicons dashicons-dismiss"></i></span>
			<img src="<?= HIWEB_URL_ASSETS . '/img/noimg.png' ?>">
		</li>
		<li data-image-empty>
			<h3>Нет прикрепленных изображений.</h3>
			<p></p>
		</li>
	</ul>

	<div class="clear"></div>
	<div>
		<button class="button button-primary" data-click-advance-add>Добавить изображение</button>
		<button class="button" data-click-advance-cancel>Отмена</button>
		<button class="button button-primary" data-click-advance-update>Сохранить</button>
	</div>
</div>