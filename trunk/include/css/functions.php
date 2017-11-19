<?php

	namespace {


		if( !function_exists( 'include_css' ) ){

			/**
			 * @param $filePathOrUrl
			 * @param array $deeps
			 * @param string $media
			 * @return bool
			 */
			function include_css( $filePathOrUrl, $deeps = [], $media = 'all' ){
				return \hiweb\css( $filePathOrUrl, $deeps, $media );
			}
		} else {
			hiweb\console::debug_error( 'Не возможно определить функция [include_css], она уже существует!' );
		}
	}

	namespace hiweb {


		/**
		 * Поставить в очередь файл CSS
		 * @version  2.0
		 * @param string $filePathOrUrl
		 * @param array $deeps
		 * @param string $media = all|screen|handheld|print
		 * @return bool
		 */
		function css( $filePathOrUrl, $deeps = [], $media = 'all' ){
			return css\enqueue::add( $filePathOrUrl, $deeps, $media );
		}
	}