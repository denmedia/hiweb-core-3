<?php

	namespace hiweb;


	/**
	 * Поставить в очередь файл CSS
	 * @version  2.0
	 *
	 * @param strings $filePathOrUrl
	 * @param array  $deeps
	 * @param strings $media = all|screen|handheld|print
	 *
	 * @return bool
	 */
	function css( $filePathOrUrl, $deeps = [], $media = 'all' ){
		return css\enqueue::add( $filePathOrUrl, $deeps, $media );
	}