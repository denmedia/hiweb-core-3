<?php

	namespace hiweb;


	class arrays{

		/**
		 * Возвращает массив, соединенный из двух массивов
		 * @param      $array1
		 * @param      $array2
		 * @param bool $createEmptyArr - если один их параметров не массив, то не соединять массив, иначе создавать <b>array(параметр)</b>
		 * @return array
		 */
		static function merge( $array1, $array2, $createEmptyArr = true ){
			if( !is_array( $array1 ) ){
				$array1 = $createEmptyArr ? [] : [ $array1 ];
			}
			if( !is_array( $array2 ) ){
				$array2 = $createEmptyArr ? [] : [ $array2 ];
			}

			return array_merge( $array1, $array2 );
		}


		/**
		 * Возвращает массив слитый из двух, исключая несовпадающие ключи второго массива
		 * @param      $array1
		 * @param      $array2
		 * @param bool $createEmptyArr
		 */
		//TODO написать функцию
		static function mergeExclude( $array1, $array2, $createEmptyArr = true ){
		}


		/**
		 * Поместить значение в массив на определенную виртуальную позицию, указав ключ
		 * @param      $array
		 * @param      $value
		 * @param null $index -
		 * @param null $key
		 * @return array
		 */
		static function push( $array, $value, $index = null, $key = null ){
			if( !is_array( $array ) ) $array = [ $array ];
			if( $index === false ){
				$array = array_reverse( $array );
				if( is_numeric( $key ) || is_string( $key ) ) $array[ $key ] = $value; else $array[] = $value;
				$array = array_reverse( $array );
			} elseif( is_numeric( $index ) ) {
				$R = [];
				$n = 0;
				$index = intval( $index );
				if( $index < 0 ){
					$array = array_reverse( $array );
				}
				$pushed = false;
				foreach( $array as $k => $v ){
					if( $n == abs( $index ) ){
						if( is_numeric( $key ) || is_string( $key ) ) $R[ $key ] = $value; else $R[] = $value;
						$pushed = true;
					}
					if( array_key_exists( $k, $R ) ){
						if( is_int( $k ) ) $R[ intval( $k ) + 1 ] = $v;
					} else $R[ $k ] = $v;
					$n ++;
				}
				if(!$pushed) {
					if( is_numeric( $key ) || is_string( $key ) ) $R[ $key ] = $value; else $R[] = $value;
				}
				$array = $R;
				if( $index < 0 ){
					$array = array_reverse( $array );
				}
			} else {
				if( is_numeric( $key ) || is_string( $key ) ) $array[ $key ] = $value; else $array[] = $value;
			}
			return $array;
		}


		/**
		 * Move array item to desctination index
		 * @param $array
		 * @param $source_key
		 * @param $destination_index
		 * @return array
		 */
		static function move( $array, $source_key, $destination_index ){
			if( !array_key_exists( $source_key, $array ) ) return $array;
			$item = $array[ $source_key ];
			unset( $array[ $source_key ] );
			return self::push( $array, $item, $destination_index, $source_key );
		}


		/**
		 * Возвращает значение ключа по его индексу
		 * @param array     $array
		 * @param int|array $index = номер (массив номеров) индекса значения. Напрмиер 0 - первый ключ. Чтобы получить последний ключ, укажите -1, так же - 2 вернет предпоследний ключ. Если индекс ключа превысит
		 * @return mixed
		 */
		static function get_value_by_index( $array, $index = 0 ){
			if( !is_array( $array ) ){
				return null;
			}
			$index = self::get_array( $index );
			$indexThis = intval( array_shift( $index ) );
			$count = count( $array );
			if( $indexThis >= $count ){
				$indexThis = $count - 1;
			} else if( $indexThis < 0 && abs( $indexThis ) <= $count ){
				$indexThis = $count - abs( $indexThis );
			} elseif( $indexThis < 0 ) {
				$indexThis = 0;
			}
			if( count( $index ) == 0 ){
				return self::get_value_by_key( array_values( $array ), $indexThis );
			} else {
				return self::get_value_by_key( array_values( self::get_value_by_index( $array, $indexThis ) ), $index );
			}
		}


		/**
		 * Осуществляет поиск значения в массиве и возвращает ключ
		 * @param      $array
		 * @param      $search_value
		 * @param bool $use_regexp
		 * @return int|null|strings|array
		 */
		static function find_key_by_value( $array, $search_value, $use_regexp = false ){
			if( is_array( $array ) ){
				foreach( $array as $key => $val ){
					if( !is_array( $val ) && !is_object( $val ) ){
						if( $search_value == $val || ( $use_regexp && preg_match( $search_value, $val ) > 0 ) ){
							return $key;
						}
					} else {
						$sub_find = self::find_key_by_value( $val, $search_value, $use_regexp );
						if( !is_null( $sub_find ) ){
							return array_merge( [ $key ], is_array( $sub_find ) ? $sub_find : [ $sub_find ] );
						}
					}
				}
			}
			return null;
		}


		/**
		 * Возвращает массив, соединенный из двух массивов, с учетом их ключей и значений
		 * @param            $array1                - начальный массив
		 * @param            $array2                - приоритетный массив
		 * @param bool|false $ifSameKey_doArr       - значения с одинаковыми строковыми ключами превращать в массив: array(a => 1) + array(a => 2) = array(a => array(1,2))
		 * @param bool|true  $ifOneArr_doMergeArr   - если началное значение является массивом, приоритетное значение будет добавлено к первому массиву, если он сам не массив
		 * @param bool|true  $ifSameNumKey_doNewKey - если ключи нумерованные, то приоритетное значние добавлять к результативному массиву
		 * @return array
		 */
		static function mergeRecursive( $array1, $array2, $ifSameKey_doArr = false, $ifOneArr_doMergeArr = true, $ifSameNumKey_doNewKey = true ){
			$r = [];
			if( !is_array( $array1 ) ){
				$array1 = [ $array1 ];
			}
			if( !is_array( $array2 ) ){
				$array2 = [ $array2 ];
			}
			///
			foreach( array_unique( array_merge( array_keys( $array1 ), array_keys( $array2 ) ) ) as $k ){
				$v = null;
				if( isset( $array1[ $k ] ) && isset( $array2[ $k ] ) ){
					if( $ifSameKey_doArr ){
						if( $ifOneArr_doMergeArr ){
							$v = self::merge( $array1[ $k ], $array2[ $k ] );
						} else {
							$v = [
								$array1[ $k ],
								$array2[ $k ]
							];
						}
					} elseif( $ifOneArr_doMergeArr ) {
						if( is_array( $array1[ $k ] ) ){
							$v = self::merge( $array1[ $k ], $array2[ $k ] );
						} else {
							$v = $array2[ $k ];
						}
					} else {
						$v = $array2[ $k ];
					}
				} else {
					$v = isset( $array2[ $k ] ) ? $array2[ $k ] : $array1[ $k ];
				}
				///
				if( !is_string( $k ) && $ifSameNumKey_doNewKey ){
					$r[] = $v;
				} else {
					$r[ $k ] = $v;
				}
			}

			return $r;
		}


		/**
		 * Возвращает TRUE, если $mix не массив, либо пустой
		 * @param      $mix     - массив
		 * @param bool $nullVal - если единственное значение null - считать массив пустым
		 * @return bool
		 */
		static function is_empty( $mix, $nullVal = true ){
			return !is_array( $mix ) || count( $mix ) == 0 || ( count( $mix ) == 1 && is_null( array_shift( $mix ) ) && $nullVal );
		}


		/**
		 * Ищет и возвращает массив ключей, чьи значения совпадают с $needle или FALSE - если ничего не найдено. Возвращаемый массив имеет вид: array(ключ => номер найденного).
		 * @param array   $haystack - массив, в котором искать совпадения со значениями
		 * @param strings $needle   - необходимый фрагмент для поиска
		 * @return array|bool
		 */
		static function strPos_keys( $haystack = [], $needle = '' ){
			$r = [];
			foreach( $haystack as $k => $v ){
				$strpos = strpos( $v, $needle );
				if( $strpos !== false ){
					$r[ $k ] = $strpos;
				}
			}

			return count( $r ) == 0 ? false : $r;
		}


		/**
		 * Возвращает массив array(ключ => номер найденного) или FALSE - если ничего не найдено
		 * @param array   $needle   - массив фрагментов для поиска
		 * @param strings $haystack - строка, в которой произвести поиск
		 * @return array|bool
		 */
		static function strPos( $needle = [], $haystack = '' ){
			$r = [];
			foreach( $needle as $k => $v ){
				$strpos = strpos( $haystack, $v );
				if( $strpos !== false ){
					$r[ $k ] = $strpos;
				}
			}

			return count( $r ) == 0 ? false : $r;
		}


		/**
		 * Возвращает массив найденных совпадений массива фрагментов в массиве
		 * @param array $haystack
		 * @param array $needle
		 * @return array|bool
		 */
		static function strPos_arrays( $haystack = [], $needle = [] ){
			$r = [];
			if( !is_array( $haystack ) || !is_array( $needle ) ){
				return false;
			}
			foreach( $haystack as $k => $v ){
				foreach( $needle as $k2 => $v2 ){
					$strpos = strpos( $v, $v2 );
					if( $strpos !== false ){
						$r[ $k ][ $v ] = $strpos;
					}
				}
			}

			return count( $r ) == 0 ? false : $r;
		}


		/**
		 * Возвращает значение ключа из массива, а так же вложенные значения, например array1(key1 => array2(key2 => value))
		 * @param array|object          $haystack - целевой массив
		 * @param strings|integer|array $keyMix   - ключ (массив вложенных ключей) в целевом массиве
		 * @param mixed                 $def      - вернуть значение, если значение не найдено
		 * @return mixed
		 * @version 1.2
		 */
		static function get_value_by_key( $haystack = [], $keyMix = '', $def = null ){
			if( is_object( $haystack ) ){
				$haystack = (array)$haystack;
			}
			if( is_array( $keyMix ) && count( $keyMix ) > 1 ){
				$key = array_shift( $keyMix );
				return self::get_value_by_key( self::get_value_by_key( $haystack, $key, $def ), $keyMix, $def );
			} elseif( is_array( $keyMix ) && count( $keyMix ) == 1 ) {
				$keyMix = array_shift( $keyMix );
			}

			return isset( $haystack[ $keyMix ] ) ? $haystack[ $keyMix ] : $def;
		}


		/**
		 * Возвращает массив, сконвертированный из $mix
		 * @param      $mix
		 * @param null $subKey - если $mix - массив, то из него можно извлеч значение ключа и сконвертировать его в массив
		 * @return array
		 */
		static function get_array( $mix, $subKey = null ){
			if( !is_null( $subKey ) && !is_bool( $subKey ) ){
				$mix = self::get_value_by_key( $mix, $subKey );
			}

			return ( is_array( $mix ) || is_null( $mix ) ) ? $mix : [ $mix ];
		}


		/**
		 * @param strings $needle
		 * @param array   $haystack
		 * @return bool
		 */
		static function in_array( $needle = '', $haystack = [] ){
			if( !is_array( $haystack ) ) $haystack = [ $haystack ];
			$haystack = @array_flip( $haystack );
			if( array_key_exists( $needle, $haystack ) ) return true;
			return false;
		}


		/**
		 * @param array   $haystack
		 * @param strings $value
		 * @return array
		 */
		static function unset_by_value( $haystack = [], $value = '' ){
			if( !is_array( $haystack ) ) $haystack = [ $haystack ];
			$haystack_flip = array_flip( $haystack );
			if( array_key_exists( $value, $haystack_flip ) ) unset( $haystack[ $haystack_flip[ $value ] ] );
			return $haystack;
		}


		/**
		 * Возвращает значение, найденное по ключу в массиве, исключая вложенные массивы
		 * @param array   $haystack - целевой массив
		 * @param strings $keyMix   - список проверяемых ключей
		 * @param null    $def      - значение, которое будлет вернуто в случае неудачи
		 * @return null
		 * @version 1.0
		 */
		static function get_next_value( $haystack = [], $keyMix = '', $def = null ){
			if( is_object( $haystack ) ){
				$haystack = (array)$haystack;
			}
			if( is_array( $keyMix ) ){
				foreach( $keyMix as $key ){
					if( isset( $haystack[ $key ] ) ){
						return $haystack[ $key ];
					}
				}
			}

			return $def;
		}


		/**
		 * Возвращает разбитую строку на массив через делимитер, сократив пустоты в краях разбитых частях возвращаемого массива
		 * @param      $delimiter        - делимитер
		 * @param      $haystack         - целевая строка
		 * @param bool $returnEmptyParts - возвращать пустые части
		 * @param bool $returnEmptyArray - возвращать пустой массив, либо FALSE
		 * @return array|bool
		 */
		static function explodeTrim( $delimiter, $haystack, $returnEmptyParts = true, $returnEmptyArray = true ){
			if( !is_string( $haystack ) ){
				return $returnEmptyArray ? [] : false;
			}
			$r = [];
			foreach( explode( $delimiter, $haystack ) as $part ){
				if( $returnEmptyParts || trim( $part ) != '' ){
					$r[] = $part;
				}
			}

			return ( $returnEmptyArray || count( $r ) > 0 ) ? $r : false;
		}


		/**
		 * Возвращает количество ключей, включая количество вложенных ключей
		 * @param      $haystack - массив
		 * @param null $keyMix   - укажите вложенные массивы в текущий массив (не обязательно, если массив простой)
		 * @return int
		 */
		static function count( $haystack, $keyMix = null ){
			if( !is_null( $keyMix ) ){
				$haystack = self::get_value_by_key( $haystack, $keyMix );
			}
			if( !is_array( $haystack ) ){
				return 0;
			}

			return count( $haystack );
		}


		/**
		 * Группирует массивы в массиве по значению ключа
		 * @param $array
		 * @param $groupKey
		 * @return array
		 */
		static function group_by_value( $array, $groupKey ){
			$R = [];
			if( is_array( $array ) ){
				foreach( $array as $key => $haystack ){
					$haystackArray = (array)$haystack;
					if( array_key_exists( $groupKey, $haystackArray ) ){
						$R[ (string)$haystackArray[ $groupKey ] ][ $key ] = $haystack;
					} else {
						$R[''][ $key ] = $haystack;
					}
				}
			}

			return $R;
		}


		static function ungroup( $array, $level = 0 ){
			$R = [];
			//$object = $array;
			if( is_object( $array ) ){
				$array = (array)$array;
			}
			if( is_array( $array ) ){
				foreach( $array as $key => $value ){
					if( $level < 1 ){
						if( is_array( $value ) || is_object( $value ) ){
							foreach( (array)$value as $subKey => $subValue ){
								$R[ $subKey ] = $subValue;
							}
						} else {
							$R[] = $value;
						}
					} else {
						$R[ $key ] = self::ungroup( $value, $level - 1 );
					}
				}
			} else {
				$R = '!';
			}

			return $R;
		}


		/**
		 * Return free key in array
		 * @param      $haystack
		 * @param null $must_key
		 * @return bool|strings
		 */
		static function get_free_key( $haystack, $must_key = null ){
			$haystack = self::get_array( $haystack );
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $must_key . '_' . $count;
				if( !isset( $haystack[ $input_name_id ] ) ) return $input_name_id;
			}
			return false;
		}
	}