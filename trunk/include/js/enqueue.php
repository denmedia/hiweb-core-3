<?php

	namespace hiweb\js;


	use hiweb\console;
	use hiweb\files\file;


	class enqueue{

		/**
		 * @var array
		 */
		static $files = [];


		/**
		 * Поставить в очередь файл JS
		 * @version  2.0
		 * @param string $filePathOrUrl
		 * @param array  $deeps
		 * @param bool   $inFooter
		 * @return bool
		 */
		static function add( $filePathOrUrl, $deeps = [], $inFooter = false ){
			$file = \hiweb\file( $filePathOrUrl );
			if( !$file->is_readable ){
				console::debug_error( 'Файл [' . $filePathOrUrl . '] не найден!' );
				return false;
			} else {
				$id = md5( $file->url );
				self::$files[ $id ] = [ $file, $deeps, $inFooter ];
				return $id;
			}
		}


		static function wp_register_script(){
			foreach( self::$files as $slug => $fileData ){
				unset( self::$files[ $slug ] );
				if( !is_array( $fileData ) ){
					console::debug_warn( 'В массиве ' . __NAMESPACE__ . '\\enqueue::$files затисался не ведомый тип данных!', [ $slug, $fileData ] );
					continue;
				}
				if( count( $fileData ) != 3 ){
					console::debug_warn( 'В массиве ' . __NAMESPACE__ . '\\enqueue::$files затисался не верный массив данных!', [ $slug, $fileData ] );
					continue;
				}
				list( $file, $deeps, $inFooter ) = [ $fileData[0], $fileData[1], $fileData[2] ];
				if( !$file instanceof file ){
					console::debug_warn( 'В массиве ' . __NAMESPACE__ . '\\enqueue::$files затисался не файл!', [ $slug, $fileData ] );
					continue;
				}
				wp_register_script( $slug, $file->url, $deeps, $file->filemtime, $inFooter );
				wp_enqueue_script( $slug );
			}
		}


	}