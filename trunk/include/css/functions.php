<?php

	namespace hiweb;


	/**
	 * Поставить в очередь файл CSS
	 * @version  2.0
	 *
	 * @param string $filePathOrUrl
	 * @param array  $deeps
	 * @param string $media = all|screen|handheld|print
	 *
	 * @return bool
	 */
	function css( $filePathOrUrl, $deeps = [], $media = 'all' ){
		return css\enqueue::add( $filePathOrUrl, $deeps, $media );
	}