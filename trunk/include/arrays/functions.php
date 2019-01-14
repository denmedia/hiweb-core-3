<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 00:35
	 */

	use hiweb\arrays\array_;


	if( !function_exists( 'get_array' ) ){
		/**
		 * Возвращает объект array для работы с массивом
		 * @param $array
		 * @return array_
		 */
		function get_array( $array ){
			return \hiweb\arrays::get_temp( $array );
		}
	}