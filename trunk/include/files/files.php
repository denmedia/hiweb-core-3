<?php

	namespace hiweb;


	class files{

		/** @var array|files\file[] */
		static $files = [];


		/**
		 * Возвращает объект файла
		 * @version 1.0
		 * @param $pathOrUrl
		 * @return files\file
		 */
		static function get( $pathOrUrl ){
			if( !array_key_exists( $pathOrUrl, self::$files ) ){
				$file = new files\file( $pathOrUrl );
				self::$files[ $pathOrUrl ] = $file;
				self::$files[ $file->path ] = $file;
				self::$files[ $file->url ] = $file;
			}
			return self::$files[ $pathOrUrl ];
		}


	}