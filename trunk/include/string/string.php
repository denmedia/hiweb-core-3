<?php

	namespace hiweb;


	class string{


		static function explode_by_symbol( $string ){ return preg_split( '//u', $string, - 1, PREG_SPLIT_NO_EMPTY ); }


		static function rand( $return_col = 20, $in_use_latin = true, $in_use_number = true, $useReg = false ){
			$symb_arr = [];
			$symb_only_latin_arr = [];
			if( $in_use_latin ){
				for( $list_n = ord( 'a' ); $list_n < ord( 'z' ); $list_n ++ ){
					array_push( $symb_arr, $list_n );
					array_push( $symb_only_latin_arr, $list_n );
				}
			}
			if( $in_use_latin and $useReg ){
				for( $list_n = ord( 'A' ); $list_n < ord( 'Z' ); $list_n ++ ){
					array_push( $symb_arr, $list_n );
				}
			}
			if( $in_use_number ){
				for( $list_n = ord( '0' ); $list_n < ord( '9' ); $list_n ++ ){
					array_push( $symb_arr, $list_n );
				}
			}
			$return_key = '';
			for( $list_n = 0; $list_n < $return_col; $list_n ++ ){
				if( $in_use_latin and $list_n == 0 ){
					$return_key .= chr( $symb_only_latin_arr[ \rand( 0, count( $symb_only_latin_arr ) - 1 ) ] );
				} else {
					$return_key .= chr( $symb_arr[ \rand( 0, count( $symb_arr ) - 1 ) ] );
				}
			}

			return $return_key;
		}


		/**
		 * Convert string utf8 to ansii
		 * @param string $utf8
		 * @return string
		 */
		static function utf8_to_ansii( $utf8 ){
			if( function_exists( 'iconv' ) ){
				$returnStr = @iconv( 'UTF-8', 'windows-1251//IGNORE', $utf8 );
			} else {
				$returnStr = strtr( $utf8, [
					"Р°" => "а",
					"Р±" => "б",
					"РІ" => "в",
					"Рі" => "г",
					"Рґ" => "д",
					"Рµ" => "е",
					"С‘" => "ё",
					"Р¶" => "ж",
					"Р·" => "з",
					"Рё" => "и",
					"Р№" => "й",
					"Рє" => "к",
					"Р»" => "л",
					"Рј" => "м",
					"РЅ" => "н",
					"Рѕ" => "о",
					"Рї" => "п",
					"СЂ" => "р",
					"СЃ" => "с",
					"С‚" => "т",
					"Сѓ" => "у",
					"С„" => "ф",
					"С…" => "х",
					"С†" => "ц",
					"С‡" => "ч",
					"С€" => "ш",
					"С‰" => "щ",
					"СЉ" => "ъ",
					"С‹" => "ы",
					"СЊ" => "ь",
					"СЌ" => "э",
					"СЋ" => "ю",
					"СЏ" => "я",
					"Рђ" => "А",
					"Р‘" => "Б",
					"Р’" => "В",
					"Р“" => "Г",
					"Р”" => "Д",
					"Р•" => "Е",
					"РЃ" => "Ё",
					"Р–" => "Ж",
					"Р—" => "З",
					"Р?" => "И",
					"Р™" => "Й",
					"Рљ" => "К",
					"Р›" => "Л",
					"Рњ" => "М",
					"Рќ" => "Н",
					"Рћ" => "О",
					"Рџ" => "П",
					"Р " => "Р",
					"РЎ" => "С",
					"Рў" => "Т",
					"РЈ" => "У",
					"Р¤" => "Ф",
					"РҐ" => "Х",
					"Р¦" => "Ц",
					"Р§" => "Ч",
					"РЁ" => "Ш",
					"Р©" => "Щ",
					"РЄ" => "Ъ",
					"Р«" => "Ы",
					"Р¬" => "Ь",
					"Р­" => "Э",
					"Р®" => "Ю",
					"С–" => "і",
					"Р†" => "І",
					"С—" => "ї",
					"Р‡" => "Ї",
					"С”" => "є",
					"Р„" => "Є",
					"Т‘" => "ґ",
					"Тђ" => "Ґ",
				] );
			}

			return $returnStr;
		}


		/**
		 * Convert ansii to utf-8
		 * @param string $ansii
		 * @return string
		 */
		static function ansii_to_utf8( $ansii ){
			if( function_exists( 'iconv' ) ){
				return iconv( 'windows-1251//IGNORE', 'UTF-8', $ansii );
			} else {
				return strtr( $ansii, array_flip( [
					"Р°" => "а",
					"Р±" => "б",
					"РІ" => "в",
					"Рі" => "г",
					"Рґ" => "д",
					"Рµ" => "е",
					"С‘" => "ё",
					"Р¶" => "ж",
					"Р·" => "з",
					"Рё" => "и",
					"Р№" => "й",
					"Рє" => "к",
					"Р»" => "л",
					"Рј" => "м",
					"РЅ" => "н",
					"Рѕ" => "о",
					"Рї" => "п",
					"СЂ" => "р",
					"СЃ" => "с",
					"С‚" => "т",
					"Сѓ" => "у",
					"С„" => "ф",
					"С…" => "х",
					"С†" => "ц",
					"С‡" => "ч",
					"С€" => "ш",
					"С‰" => "щ",
					"СЉ" => "ъ",
					"С‹" => "ы",
					"СЊ" => "ь",
					"СЌ" => "э",
					"СЋ" => "ю",
					"СЏ" => "я",
					"Рђ" => "А",
					"Р‘" => "Б",
					"Р’" => "В",
					"Р“" => "Г",
					"Р”" => "Д",
					"Р•" => "Е",
					"РЃ" => "Ё",
					"Р–" => "Ж",
					"Р—" => "З",
					"Р?" => "И",
					"Р™" => "Й",
					"Рљ" => "К",
					"Р›" => "Л",
					"Рњ" => "М",
					"Рќ" => "Н",
					"Рћ" => "О",
					"Рџ" => "П",
					"Р " => "Р",
					"РЎ" => "С",
					"Рў" => "Т",
					"РЈ" => "У",
					"Р¤" => "Ф",
					"РҐ" => "Х",
					"Р¦" => "Ц",
					"Р§" => "Ч",
					"РЁ" => "Ш",
					"Р©" => "Щ",
					"РЄ" => "Ъ",
					"Р«" => "Ы",
					"Р¬" => "Ь",
					"Р­" => "Э",
					"Р®" => "Ю",
					"С–" => "і",
					"Р†" => "І",
					"С—" => "ї",
					"Р‡" => "Ї",
					"С”" => "є",
					"Р„" => "Є",
					"Т‘" => "ґ",
					"Тђ" => "Ґ",
				] ) );
			}
		}


		/**
		 * @param string $parseStr
		 * @return array
		 */
		static function explode_to_string_numeric( $parseStr ){
			$r = [];
			foreach( self::explode_by_symbol( $parseStr ) as $s ){
				end( $r );
				$lastVal = current( $r );
				$lastKey = key( $r );
				if( $lastVal === false ){
					$r[] = $s;
				} else {
					$lastNum = is_numeric( $lastVal );
					if( is_numeric( $s ) && $lastNum ){
						$r[ $lastKey ] .= $s;
					} else {
						$r[] = $s;
					}
				}
			}

			return $r;
		}


		/**
		 * Formatting JSON string
		 * @param string $json
		 * @return string
		 */
		static function json_format( $json ){
			if( !is_string( $json ) ){
				$json = json_encode( $json );
			}
			$result = '';
			$pos = 0;
			$strLen = strlen( $json );
			$indentStr = '  ';
			$newLine = "\n";
			$prevChar = '';
			$outOfQuotes = true;
			for( $i = 0; $i <= $strLen; $i ++ ){
				$char = substr( $json, $i, 1 );
				if( $char == '"' && $prevChar != '\\' ){
					$outOfQuotes = !$outOfQuotes;
				} else if( ( $char == '}' || $char == ']' ) && $outOfQuotes ){
					$result .= $newLine;
					$pos --;
					for( $j = 0; $j < $pos; $j ++ ){
						$result .= $indentStr;
					}
				}
				$result .= $char;
				if( ( $char == ',' || $char == '{' || $char == '[' ) && $outOfQuotes ){
					$result .= $newLine;
					if( $char == '{' || $char == '[' ){
						$pos ++;
					}
					for( $j = 0; $j < $pos; $j ++ ){
						$result .= $indentStr;
					}
				}
				$prevChar = $char;
			}

			return $result;
		}


		/**
		 * Return TRUE, if haystack string is REGEX
		 * @param string $haystackString
		 * @return bool
		 */
		static function is_regex( $haystackString ){ return preg_match( "/^\/[\s\S]+\/$/", $haystackString ) > 0; }


		/**
		 * Return TRUE, if haystack string is JSON
		 * @param string $haystack
		 * @param bool   $returnIfFalse
		 * @param bool   $returnDecodeIfJson
		 * @return bool|mixed
		 */
		static function is_json( $haystack, $returnIfFalse = false, $returnDecodeIfJson = true ){
			if( !is_string( $haystack ) || empty( $haystack ) ){
				return $returnIfFalse;
			}
			$decode = json_decode( $haystack, true );

			return ( json_last_error() == JSON_ERROR_NONE ) ? ( $returnDecodeIfJson ? $decode : true ) : $returnIfFalse;
		}


		/**
		 * Return TRUE, if haystack variable is empty. Functions convertvariable to string
		 * @param      $haystack
		 * @param bool $default
		 * @return bool
		 */
		static function is_empty( $haystack, $default = true ){
			return ( !is_array( $haystack ) && ( is_null( $haystack ) || $haystack === false || trim( (string)$haystack ) == '' ) ) ? $default : false;
		}
	}