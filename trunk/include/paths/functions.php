<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:50
	 */

	use hiweb\paths;


	if(!function_exists('get_path')){
		/**
		 * Возвращает класс path для работы с путями и URL
		 * @param $path
		 * @return \hiweb\paths\path
		 */
		function get_path($path = '') {
			return paths::get($path);
		}
	}