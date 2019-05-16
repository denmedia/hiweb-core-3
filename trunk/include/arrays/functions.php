<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 00:35
	 */

	use hiweb\arrays;
	use hiweb\arrays\array_;


	if( !function_exists( 'get_array' ) ){
		/**
		 * Возвращает объект array для работы с массивом
		 * @param array $array
		 * @param bool  $new_instance
		 * @return array_
		 */
		function get_array( $array = [], $new_instance = true ){
			return arrays::get_temp( $array, $new_instance );
		}
	}