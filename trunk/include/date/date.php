<?php

	namespace hiweb;


	class date{

		/**
		 * Возвращает форматированное дату и время
		 *
		 * @param int    $time   - необходимое время в секундах, если не указывать, будет взято текущее время
		 * @param strings $format - форматирование времени
		 *
		 * @return bool|strings
		 */
		static function format( $time = null, $format = 'Y-m-d H:i:s' ){
			if( intval( $time ) < 100 ){
				$time = time();
			}

			return date( $format, intval( $time ) );
		}


		/**
		 * Возвращает наименование дня недели
		 * @param int  $weekNum
		 * @param bool $fullName
		 * @return bool
		 */
		static function week( $weekNum = 0, $fullName = true ){
			$weekNum = intval( $weekNum );
			$a = [
				[ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
				[ 'восресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота' ]
			];

			return isset( $a[ $fullName ? 1 : 0 ][ $weekNum ] ) ? $a[ $fullName ? 1 : 0 ][ $weekNum ] : false;
		}



	}