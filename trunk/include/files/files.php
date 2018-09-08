<?php

	namespace hiweb;


	class files{

		/** @var array|files\file[] */
		static $files = [];


		/**
		 * Возвращает объект файла
		 * @version 1.0
		 * @param $pathOrUrlOrAttachID
		 * @return files\file
		 */
		static function get( $pathOrUrlOrAttachID ){
			if(is_numeric($pathOrUrlOrAttachID)){
				$pathOrUrlOrAttachID = get_attached_file($pathOrUrlOrAttachID);
			}
			if( !array_key_exists( $pathOrUrlOrAttachID, self::$files ) ){
				$file = new files\file( $pathOrUrlOrAttachID );
				self::$files[ $pathOrUrlOrAttachID ] = $file;
				self::$files[ $file->path ] = $file;
				self::$files[ $file->url ] = $file;
			}
			return self::$files[ $pathOrUrlOrAttachID ];
		}


	}