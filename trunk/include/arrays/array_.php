<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 00:18
	 */

	namespace hiweb\arrays;


	use hiweb\arrays;


	class array_{

		/**
		 * @var array
		 */
		private $array;
		/** @var json */
		private $json;


		public function __construct( $array ){
			$this->array = (array)$array;
		}


		/**
		 * @return array
		 */
		public function get(){
			return $this->array;
		}


		/**
		 * @return json
		 */
		public function get_json(){
			if( !$this->json instanceof json ){
				$this->json = new json( $this );
			}
			return $this->json;
		}


		/**
		 * @return int
		 */
		public function count(){
			return count( $this->array );
		}


		/**
		 * @return bool
		 */
		public function is_empty(){
			return $this->count() < 1;
		}


		/**
		 * @param int|string $key
		 * @return bool
		 */
		public function has_key( $key ){
			return array_key_exists( $key, $this->array );
		}


		/**
		 * @param int  $index
		 * @param bool $return_index - if FALSE then return bool, or return calculate index
		 * @return bool
		 */
		public function has_index( $index, $return_index = false ){
			$index = intval( $index );
			if( $index < 0 ){
				$index = abs( $index );
				$index = (int)( $this->count() - ( $index ) );
			}
			$R = array_key_exists( $index, array_values( $this->array ) );
			if( $return_index && $R ) return $index;
			return $R;
		}


		/**
		 * Поиск значения ключа, включая вложенные массивы
		 * @param int|string|array $key
		 * @param null|mixed       $default
		 * @return mixed|null
		 */
		public function value_by_key( $key, $default = null ){
			if( !is_array( $key ) || count( $key ) == 1 ){
				$key = is_array( $key ) ? reset( $key ) : $key;
				return $this->has_key( $key ) ? $this->array[ $key ] : $default;
			} elseif( is_array( $key ) ) {
				foreach( $key as $subkey ){
					if( $this->has_key( $subkey ) ){
						return arrays::get( $this->array[ $subkey ] )->value_by_key( array_slice( $key, 1 ), $default );
					} else break;
				}
			}
			return $default;
		}


		/**
		 * Возвращает значение ключа по его индексу, включая вложенные массивы
		 * @param int|array  $index   = номер (массив номеров) индекса значения. Напрмиер 0 - первый ключ. Чтобы получить последний ключ, укажите -1, так же -2 вернет предпоследний ключ, если таковые имеются.
		 * @param null|mixed $default - в случае, если значение ключа не найдено
		 * @return mixed
		 */
		public function value_by_index( $index, $default = null ){
			if( !is_array( $index ) || count( $index ) == 1 ){
				$index = ( is_array( $index ) && count( $index ) == 1 ) ? intval( reset( $index ) ) : intval( $index );
				return $this->has_index( $index ) ? array_values( $this->array )[ $index ] : $default;
			} elseif( is_array( $index ) ) {
				foreach( $index as $ind ){
					$ind = intval( $ind );
					if( $this->has_index( $ind ) ){
						return arrays::get( array_values( $this->array )[ (int)$ind ] )->value_by_index( array_slice( $index, 1 ), $default );
					} else break;
				}
			}
			return $default;
		}


		/**
		 * Поместить значение в массив на определенную виртуальную позицию, указав ключ
		 * @param mixed       $value
		 * @param null|int    $index
		 * @param null|string $key
		 * @return array
		 */
		public function push_value( $value, $index = null, $key = null ){
			if( !is_array( $this->array ) ) $this->array = [ $this->array ];
			if( $index === false ){
				$this->array = array_reverse( $this->array );
				if( is_numeric( $key ) || is_string( $key ) ) $this->array[ $key ] = $value; else $this->array[] = $value;
				$this->array = array_reverse( $this->array );
			} elseif( is_numeric( $index ) ) {
				$R = [];
				$n = 0;
				$index = intval( $index );
				if( $index < 0 ){
					$this->array = array_reverse( $this->array );
				}
				$pushed = false;
				foreach( $this->array as $k => $v ){
					if( $n == abs( $index ) ){
						if( is_numeric( $key ) || is_string( $key ) ) $R[ $key ] = $value; else $R[] = $value;
						$pushed = true;
					}
					if( array_key_exists( $k, $R ) ){
						if( is_int( $k ) ) $R[ intval( $k ) + 1 ] = $v;
					} else $R[ $k ] = $v;
					$n ++;
				}
				if( !$pushed ){
					if( is_numeric( $key ) || is_string( $key ) ) $R[ $key ] = $value; else $R[] = $value;
				}
				$this->array = $R;
				if( $index < 0 ){
					$this->array = array_reverse( $this->array );
				}
			} else {
				if( is_numeric( $key ) || is_string( $key ) ) $this->array[ $key ] = $value; else $this->array[] = $value;
			}
			//arrays::_renew( $this );
			return $this->array;
		}


		/**
		 * @param $keyOrValue
		 * @param $value
		 */
		public function push( $keyOrValue, $value = null ){
			if( is_null( $value ) ){
				$this->array[] = $keyOrValue;
			} else {
				$this->array[ $keyOrValue ] = $value;
			}
//			arrays::_renew( $this );
		}


		/**
		 * @return mixed
		 */
		public function pop(){
			$value = array_pop( $this->array );
//			arrays::_renew( $this );
			return $value;
		}


		/**
		 * @param array $array
		 * @return $this
		 */
		public function set( $array = [] ){
			$this->array = (array)$array;
//			arrays::_renew( $this );
			return $this;
		}


		/**
		 * @return mixed
		 */
		public function shift(){
			$value = array_shift( $this->array );
//			arrays::_renew( $this );
			return $value;
		}


		/**
		 * @param      $keyOrValue
		 * @param null $value
		 */
		public function unshift( $keyOrValue, $value = null ){
			$this->array = array_reverse( $this->array, true );
			if( is_null( $value ) ){
				$this->array[] = $keyOrValue;
			} else {
				$this->array[ $keyOrValue ] = $value;
			}
			$this->array = array_reverse( $this->array, true );
//			arrays::_renew( $this );
		}


		/**
		 * Функция двигает (меняет индекс) элемента массива
		 * @param int|string $source_key
		 * @param int        $destination_index
		 * @return array
		 */
		public function move_value( $source_key, $destination_index ){
			if( !$this->has_key( $source_key ) ) return $this->array;
			$item = $this->array[ $source_key ];
			unset( $this->array[ $source_key ] );
			return $this->push_value( $item, $destination_index, $source_key );
		}


		/**
		 * Поиск ключа (ключей) по его начению
		 * @param      $search_value
		 * @param bool $use_regexp
		 * @param null $default
		 * @return array|int|null|string
		 */
		public function key_by_value( $search_value, $use_regexp = false, $default = null ){
			foreach( $this->array as $key => $val ){
				if( !is_array( $val ) && !is_object( $val ) ){
					if( $search_value == $val || ( $use_regexp && preg_match( $search_value, $val ) > 0 ) ){
						return $key;
					}
				} else {
					$sub_find = arrays::get( $val )->key_by_value( $search_value, $use_regexp );
					if( !is_null( $sub_find ) ){
						return array_merge( [ $key ], is_array( $sub_find ) ? $sub_find : [ $sub_find ] );
					}
				}
			}
			return $default;
		}


		/**
		 * @param $value
		 * @return bool
		 */
		public function has_value( $value ){
			$haystack = @array_flip( $this->array );
			if( array_key_exists( $value, $haystack ) ) return true;
			return false;
		}


		/**
		 * @param $needle
		 * @alias self::has_value
		 * @return bool
		 */
		public function in( $needle ){
			return $this->has_value( $needle );
		}


		/**
		 * @param $key
		 * @return array
		 */
		public function unset_key( $key ){
			$new_array = $this->array;
			if( !is_array( $key ) || count( $key ) == 1 ){
				$key = is_array( $key ) ? reset( $key ) : $key;
				unset( $new_array[ $key ] );
			} else {
				$first_key = array_shift( $key );
				$new_array[ $first_key ] = arrays::get( $new_array[ $first_key ] )->unset_key( $key );
			}
			return $new_array;
		}


		/**
		 * @param $value
		 * @return array
		 */
		public function unset_value( $value ){
			$keys = $this->key_by_value( $value );
			return $this->unset_key( $keys );
		}


		/**
		 * @param null $needle_key
		 * @return bool|string
		 */
		public function free_key( $needle_key = null ){
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $needle_key . '_' . $count;
				if( !isset( $this->array[ $input_name_id ] ) ) return $input_name_id;
			}
			return false;
		}


		/**
		 * @param      $mixedOrArray
		 * @param bool $low_priority
		 * @return array_
		 */
		public function merge( $mixedOrArray, $low_priority = false ){
			if( is_array( $mixedOrArray ) ){
				if( $low_priority ){
					$this->array = array_merge( $mixedOrArray, $this->get() );
				} else {
					$this->array = array_merge( $this->get(), $mixedOrArray );
				}
			} else {
				$new_array = $this->get();
				if( $low_priority ) array_unshift( $new_array, $mixedOrArray ); else array_push( $new_array, $mixedOrArray );
			}
//			arrays::_renew( $this );
			return $this;
		}


		/**
		 * @param bool $return_array_pairs
		 * @param bool $value_to_json
		 * @return array|string
		 */
		public function get_param_html_tags( $return_array_pairs = false, $value_to_json = false ){
			$pairs = [];
			foreach( $this->get() as $key => $val ){
				$pairs[] = $key . '="' . ( $value_to_json ? json_encode( $val ) : htmlentities( $val, ENT_QUOTES, 'UTF-8' ) ) . '"';
			}
			return $return_array_pairs ? $pairs : implode( ' ', $pairs );
		}


		/**
		 * @param $key
		 * @return bool
		 */
		public function key_exists( $key ){
			return array_key_exists( $key, $this->array );
		}


		/**
		 * @param $key
		 * @param $new_key
		 * @return bool
		 */
		public function key_rename( $key, $new_key ){
			if( $this->key_exists( $key ) ){
				$value = $this->array[ $key ];
				unset( $this->array[ $key ] );
				$this->array[ $new_key ] = $value;
//				arrays::_renew( $this );
				return true;
			}
			return false;
		}

	}