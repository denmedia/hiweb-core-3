<?php

	namespace hiweb\css;


	use hiweb\console;
	use hiweb\files\file;


	class enqueue{

		/**
		 * @var array
		 */
		static $files = [];


		/**
		 * Поставить в очередь файл CSS
		 * @version  2.1
		 * @param string $filePathOrUrl
		 * @param array $deeps
		 * @param string $media = all|screen|handheld|print
		 * @return bool
		 */
		static function add( $filePathOrUrl, $deeps = [], $media = 'all' ){
			$file = \hiweb\file( $filePathOrUrl );
			if( !$file->is_url && !$file->is_readable ){
				console::debug_error( 'Файл [' . $filePathOrUrl . '] не найден!' );
				return false;
			} else {
				$id = md5( $file->url );
				self::$files[ $id ] = [ $file, $deeps, $media ];
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
				list( $file, $deeps, $media ) = [ $fileData[0], $fileData[1], $fileData[2] ];
				if( !$file instanceof file ){
					console::debug_warn( 'В массиве ' . __NAMESPACE__ . '\\enqueue::$files затисался не файл!', [ $slug, $fileData ] );
					continue;
				}
				wp_register_style( $slug, $file->url, $deeps, $file->filemtime, $media );
				wp_enqueue_style( $slug );
			}
		}
	}