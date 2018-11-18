<?php

	namespace {


		if ( ! function_exists( 'include_css' ) ) {
			/**
			 * @param        $filePathOrUrl
			 * @param array  $deeps
			 * @param string $media
			 * @param bool   $in_footer
			 * @return bool
			 */
			function include_css( $filePathOrUrl, $in_footer = false, $deeps = [], $media = 'all' ) {
				return hiweb\css\enqueue::add( $filePathOrUrl, $deeps, $media, $in_footer );
			}
		} else {
			hiweb\console::debug_warn( 'Function [include_css] is exists...' );
		}
	}

	namespace hiweb {


		/**
		 * Поставить в очередь файл CSS
		 * @version  2.0
		 * @param string $filePathOrUrl
		 * @param bool   $in_footer
		 * @param array  $deeps
		 * @param string $media = all|screen|handheld|print
		 * @return bool
		 */
		function css( $filePathOrUrl, $in_footer = false, $deeps = [], $media = 'all' ) {
			return css\enqueue::add( $filePathOrUrl, $deeps, $media, $in_footer );
		}
	}