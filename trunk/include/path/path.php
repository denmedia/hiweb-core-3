<?php

	namespace hiweb\path;


	/**
	 * Возвращает текущий адрес URL
	 * @version 1.0.2
	 * @param bool $trimSlashes
	 * @return string
	 */
	function url_full( $trimSlashes = true ){
		$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
		return rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], '/' ) . ( $trimSlashes ? rtrim( $_SERVER['REQUEST_URI'], '/\\' ) : $_SERVER['REQUEST_URI'] );
	}

	/**
	 * Возвращает запрошенный GET или POST параметр
	 * @param       $key
	 * @param mixed $default
	 * @return mixed
	 */
	function request( $key, $default = null ){
		$R = $default;
		if( array_key_exists( $key, $_GET ) ) $R = $_GET[ $key ];
		if( array_key_exists( $key, $_POST ) ) $R = is_string( $_POST[ $key ] ) ? stripslashes( $_POST[ $key ] ) : $_POST[ $key ];
		return $R;
	}

	/**
	 * Возвращает корневой URL
	 * @param null|string $url
	 * @return string
	 * @version 1.3
	 */
	function base_url( $url = null ){
		if( is_string( $url ) ){
			$url = self::prepare_url( $url, null, true );
			return $url['base'];
		} else {
			//if(hiweb()->cacheExists()) return hiweb()->cache();
			$root = ltrim( self::base_dir(), '/' );
			$query = ltrim( str_replace( '\\', '/', dirname( $_SERVER['PHP_SELF'] ) ), '/' );
			$rootArr = [];
			$queryArr = [];
			foreach( array_reverse( explode( '/', $root ) ) as $dir ){
				$rootArr[] = rtrim( $dir . '/' . end( $rootArr ), '/' );
			}
			foreach( explode( '/', $query ) as $dir ){
				$queryArr[] = ltrim( end( $queryArr ) . '/' . $dir, '/' );
			}
			$rootArr = array_reverse( $rootArr );
			$queryArr = array_reverse( $queryArr );
			$r = '';
			foreach( $queryArr as $dir ){
				foreach( $rootArr as $rootDir ){
					if( $dir == $rootDir ){
						$r = $dir;
						break 2;
					}
				}
			}
			$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
			return rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'] . '/' . $r, '/' );
		}
	}

	/**
	 * Возвращает корневую папку сайта. Данная функция автоматически определяет корневую папку сайта, отталкиваясь на поиске папок с файлом index.php
	 * @return string
	 * @version 1.4
	 */
	function base_dir(){
		$full_path = getcwd();
		$ar = explode( "wp-", $full_path );
		return rtrim( $ar[0], '\\/' );
	}

	/**
	 * Возвращает URL с измененным QUERY фрагмнтом
	 * @param null  $url
	 * @param array $addData
	 * @param array $removeKeys
	 * @return string
	 * @version 1.4
	 */
	function query( $url = null, $addData = [], $removeKeys = [] ){
		if( is_null( $url ) || trim( $url ) == '' ){
			$url = self::url_full();
		}
		$url = explode( '?', $url );
		$urlPath = array_shift( $url );
		$query = implode( '?', $url );
		///
		$params = explode( '&', $query );
		$paramsPair = [];
		foreach( $params as $param ){
			if( trim( $param ) == '' ){
				continue;
			}
			list( $key, $val ) = explode( '=', $param );
			$paramsPair[ $key ] = $val;
		}
		///Add
		if( is_array( $addData ) ){
			foreach( $addData as $key => $value ){
				$paramsPair[ $key ] = $value;
			}
		} elseif( is_string( $addData ) && trim( $addData ) != '' ) {
			$paramsPair[] = $addData;
		}
		///Remove
		if( is_array( $removeKeys ) ){
			foreach( $removeKeys as $key => $value ){
				if( is_string( $key ) && isset( $paramsPair[ $key ] ) ){
					unset( $paramsPair[ $key ] );
				} elseif( isset( $paramsPair[ $value ] ) ) {
					unset( $paramsPair[ $value ] );
				}
			}
		} else if( is_string( $removeKeys ) && trim( $removeKeys ) != '' && isset( $paramsPair[ $removeKeys ] ) ){
			unset( $paramsPair[ $removeKeys ] );
		}
		///
		$params = [];
		foreach( $paramsPair as $key => $value ){
			$params[] = ( is_string( $key ) ? $key . '=' : '' ) . htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		}
		///
		return count( $paramsPair ) > 0 ? $urlPath . '?' . implode( '&', $params ) : $urlPath;
	}

	/**
	 * Возвращает расширение файла, уть которого указан в аргументе $path
	 * @param $path
	 * @return string
	 */
	function extension( $path ){
		$pathInfo = pathinfo( $path );
		return isset( $pathInfo['extension'] ) ? $pathInfo['extension'] : '';
	}

	/**
	 * Конвертирует путь в URL до файла
	 * @version 2.1
	 * @param $path
	 * @return mixed
	 */
	function path_to_url( $path ){
		if( strpos( $path, 'http' ) === 0 ) return $path;
		$path = str_replace( '\\', '/', self::realpath( $path ) );
		return str_replace( self::base_dir(), self::base_url(), $path );
	}

	/**
	 * Конвертирует URL в путь
	 * @param $url
	 * @return mixed
	 */
	function url_to_path( $url ){
		$url = str_replace( '\\', '/', $url );
		return str_replace( self::base_url(), self::base_dir(), $url );
	}

	/**
	 * @param null $url
	 * @return array
	 */
	function url_info( $url = null ){
		if( is_null( $url ) || trim( $url ) == '' ){
			$url = self::url_full();
		}
		$urlExplode = explode( '?', $url );
		$urlPath = array_shift( $urlExplode );
		$query = implode( '?', $urlExplode );
		///params
		$paramsPair = [];
		if( trim( $query ) != '' ){
			$params = explode( '&', $query );
			foreach( $params as $param ){
				@list( $key, $val ) = explode( '=', $param );
				$paramsPair[ $key ] = $val;
			}
		}
		///
		$baseUrl = self::base_url();
		$baseUrl = strpos( $url, $baseUrl ) === 0 ? $baseUrl : false;
		$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
		$shema = $https ? 'https://' : 'http://';
		$dirs = [];
		$domain = '';
		if( $baseUrl != false ){
			$dirs = explode( '/', trim( str_replace( $shema, '', $urlPath ), '/' ) );
			$domain = array_shift( $dirs );
		}
		return [ 'url' => $url, 'base_url' => $baseUrl, 'shema' => $shema, 'domain' => $domain, 'dirs' => implode( '/', $dirs ), 'dirs_arr' => $dirs, 'params' => $query, 'params_arr' => $paramsPair ];
	}

	/**
	 * Возвращает папки или папку(если указать индекс) из URL
	 * @version 2.1
	 * @param null $url
	 * @param int  $index
	 * @return bool|array|string
	 */
	function get_dirs_from_url( $url = null, $index = null ){
		$urlArr = self::url_info( self::prepare_url( $url, self::base_url() ) );
		$R = is_int( $index ) ? ( isset( $urlArr['dirs_arr'][ $index ] ) ? $urlArr['dirs_arr'][ $index ] : false ) : $urlArr['dirs_arr'];
		return $R;
	}

	/**
	 * Возвращает массив параметров из URL или значение определенного параметра
	 * @param null $url        - если не указывать, будет взят текущий URL
	 * @param null $indexOrKey - если не указывать, будет вернут массив параметров, иначе значение параметра
	 * @return string|array|null
	 */
	function get_paramas_from_url( $url = null, $indexOrKey = null ){
		$urlArr = self::url_info( self::prepare_url( $url, self::base_url() ) );
		$paramsArr = $urlArr['params_arr'];
		return is_null( $indexOrKey ) ? $paramsArr : ( isset( $paramsArr[ $indexOrKey ] ) ? $paramsArr[ $indexOrKey ] : null );
	}

	/**
	 * Нормализация URL, так же возвращает парсированный URL
	 * @version 1.2
	 * @param      $url
	 * @param null $startUrl
	 * @param bool $returnParseArray
	 * @return bool|array|string
	 */
	function prepare_url( $url, $startUrl = null, $returnParseArray = false ){
		if( !is_string( $url ) ){
			return false;
		}
		$urlParse = parse_url( trim( $url ) );
		if( !isset( $urlParse['scheme'] ) ){
			if( is_string( $startUrl ) && trim( $startUrl ) != '' ){
				$startUrlParse = parse_url( $startUrl );
				$urlParse['scheme'] = $startUrlParse['scheme'];
				$urlParse['host'] = $startUrlParse['host'];
			} else {
				$urlParse['scheme'] = 'http';
				$urlParse['path'] = explode( '/', $urlParse['path'] );
				$urlParse['host'] = array_shift( $urlParse['path'] );
				$urlParse['path'] = '/' . implode( '/', $urlParse['path'] );
			}
		}
		//if(function_exists('idn_to_utf8')) { $urlParse['host'] = idn_to_utf8($urlParse['host']); }
		if( !isset( $urlParse['path'] ) ){
			$urlParse['path'] = '';
		}
		if( !isset( $urlParse['query'] ) ){
			$urlParse['query'] = '';
		} else {
			$urlParse['query'] = '?' . $urlParse['query'];
		}
		$urlParse['base'] = $urlParse['scheme'] . '://' . $urlParse['host'];
		return $returnParseArray ? $urlParse : $urlParse['scheme'] . '://' . $urlParse['host'] . $urlParse['path'] . $urlParse['query'];
	}

	/**
	 * Возвращает TRUE, если текущая страница являеться домашней
	 * @return bool
	 */
	function is_home(){
		return self::base_url() == self::url_full();
	}

	/**
	 * Возвращает TRUE, если текущая страница соответствует указанному SLUG
	 * @param string $pageSlug
	 * @return bool
	 */
	function is( $pageSlug = '' ){
		$currentUrl = ltrim( str_replace( self::base_url(), '', self::url_full() ), '/\\' );
		$pageSlug = ltrim( $pageSlug, '/\\' );
		return ( strpos( $currentUrl, $pageSlug ) === 0 );
	}

	/**
	 * Возвращает TRUE, если файл по заданному пути или URL существует и читабелен
	 * @param $pathOrUrl
	 * @return bool
	 */
	function is_readable( $pathOrUrl ){
		if( trim( $pathOrUrl ) == '' ) return false;
		$path = self::url_to_path( $pathOrUrl );
		return file_exists( $path ) && is_readable( $path );
	}

	/**
	 * Возвращает DIRECTORY SEPARATOR, отталкиваясь от данных
	 */
	function separator(){
		$left = substr_count( $_SERVER['DOCUMENT_ROOT'], '\\' );
		$right = substr_count( $_SERVER['DOCUMENT_ROOT'], '//' );
		return $left > $right ? '\\' : '/';
	}

	/**
	 * Возвращает путь с правильными разделителями
	 * @param      $path                 - исходный путь
	 * @param bool $removeLastSeparators - удалить самый хвостовой сепаратор
	 * @return string | bool
	 * @version 1.1
	 */
	function prepare_separator( $path, $removeLastSeparators = false ){
		if( !is_string( $path ) ){
			hiweb()->console()->warn( 'Путь должен быть строкой', 1 );
			return false;
		}
		$r = strtr( $path, [ '\\' => self::separator(), '/' => self::separator() ] );
		return $removeLastSeparators ? rtrim( $r, self::separator() ) : $r;
	}

	/**
	 * Конвертация относитльного пути к коневой папке в реальный
	 * @version 1.0.0.0
	 * @param $fileOrDirPath - путь до файла или папки
	 * @return string
	 */
	function realpath( $fileOrDirPath ){
		$fileOrDirPath = self::prepare_separator( $fileOrDirPath );
		return ( strpos( $fileOrDirPath, self::base_dir() ) !== 0 ) ? self::base_dir() . self::separator() . $fileOrDirPath : $fileOrDirPath;
	}

	/**
	 * @param $fileOrDirPath
	 * @return bool|string
	 */
	function simplepath( $fileOrDirPath ){
		return strpos( $fileOrDirPath, self::base_dir() ) === 0 ? substr( $fileOrDirPath, strlen( self::base_dir() ) ) : $fileOrDirPath;
	}

	/**
	 * Функция атоматически создает папки
	 * @param $dirPath - путь до папи, которую необходимо создать
	 * @return string
	 */
	function mkdir( $dirPath ){
		$dirPath = self::realpath( $dirPath );
		if( @file_exists( $dirPath ) ){
			return is_dir( $dirPath ) ? $dirPath : false;
		}
		$dirPathArr = explode( '/', str_replace( '/', '/', $dirPath ) );
		$newDirArr = [];
		$newDirDoneArr = [];
		foreach( $dirPathArr as $name ){
			$newDirArr[] = $name;
			$newDirStr = implode( '/', $newDirArr );
			@chmod( $newDirStr, 0755 );
			//$stat = @stat( $newDirStr );
			if( !@file_exists( $newDirStr ) || @is_file( $newDirStr ) ){
				$newDirDoneArr[ $name ] = @mkdir( $newDirStr, 0755 );
			} else {
				$newDirDoneArr[ $newDirStr ] = 0;
			}
		}
		return $newDirDoneArr;
	}

	/**
	 * Удалить папку вместе с вложенными папками и файлами
	 * @param $dirPath
	 * @return bool
	 */
	function rmdir( $dirPath ){
		if( !is_dir( $dirPath ) ){
			return false;
		}
		if( substr( $dirPath, strlen( $dirPath ) - 1, 1 ) != '/' ){
			$dirPath .= '/';
		}
		$files = glob( $dirPath . '*', GLOB_MARK );
		foreach( $files as $file ){
			if( is_dir( $file ) ){
				self::rmdir( $file );
			} else {
				unlink( $file );
			}
		}
		return rmdir( $dirPath );
	}

	/**
	 * Копирует папку целиком вместе с вложенными файлами и папками
	 * @param $sourcePath     - исходная папка
	 * @param $destinationDir - папка назначения
	 * @return bool
	 */
	function copy_dir( $sourcePath, $destinationDir ){
		$dir = opendir( $sourcePath );
		self::mkdir( $destinationDir );
		$r = true;
		while( false !== ( $file = readdir( $dir ) ) ){
			if( ( $file != '.' ) && ( $file != '..' ) ){
				if( is_dir( $sourcePath . '/' . $file ) ){
					$r = $r && self::copy_dir( $sourcePath . '/' . $file, $destinationDir . '/' . $file );
				} else {
					$r = $r && copy( $sourcePath . '/' . $file, $destinationDir . '/' . $file );
				}
			}
		}
		closedir( $dir );
		return $r;
	}

	/**
	 * Возвращает форматированный вид размера файла из байтов
	 * @param $size - INT килобайты
	 * @return string
	 */
	function size_format( $size ){
		$size = intval( $size );
		if( $size < 1024 ){
			return $size . ' B';
		} elseif( $size < 1048576 ) {
			return round( $size / 1024, 2 ) . ' KiB';
		} elseif( $size < 1073741824 ) {
			return round( $size / 1048576, 2 ) . ' MiB';
		} elseif( $size < 1099511627776 ) {
			return round( $size / 1073741824, 2 ) . ' GiB';
		} elseif( $size < 1125899906842624 ) {
			return round( $size / 1099511627776, 2 ) . ' TiB';
		} elseif( $size < 1152921504606846976 ) {
			return round( $size / 1125899906842624, 2 ) . ' PiB';
		} elseif( $size < 1180591620717411303424 ) {
			return round( $size / 1152921504606846976, 2 ) . ' EiB';
		} elseif( $size < 1208925819614629174706176 ) {
			return round( $size / 1180591620717411303424, 2 ) . ' ZiB';
		} else {
			return round( $size / 1208925819614629174706176, 2 ) . ' YiB';
		}
	}

	/**
	 * Возвращает содержимое папки в массиве
	 * @param      $path
	 * @param bool $returnDirs
	 * @param bool $returnFiles
	 * @param bool $getSubDirs
	 * @return array
	 */
	function scan_directory( $path, $returnDirs = true, $returnFiles = true, $getSubDirs = true ){
		$path = self::realpath( $path );
		if( !file_exists( $path ) ){
			return [];
		}
		$R = [];
		if( $handle = opendir( $path ) ){
			while( false !== ( $file = readdir( $handle ) ) ){
				$nextpath = $path . '/' . $file;
				if( $file != '.' && $file != '..' && !is_link( $nextpath ) ){
					///
					if( is_dir( $nextpath ) && $returnDirs ){
						$R[ $nextpath ] = pathinfo( $nextpath );
					} elseif( is_file( $nextpath ) && $returnFiles ) {
						$R[ $nextpath ] = pathinfo( $nextpath );
					}
					///
					if( $getSubDirs && is_dir( $nextpath ) ){
						$R = $R + self::scan_directory( $nextpath, $returnDirs, $returnFiles, $getSubDirs );
					}
				}
			}
		}
		closedir( $handle );
		return $R;
	}

	/**
	 * Выполняет архивацию папки в ZIP архив
	 * @param             $pathInput
	 * @param string      $pathOut
	 * @param string      $arhiveName
	 * @param string|bool $baseDirInArhive - базовая папка / путь внутри архива для всех запакованных файлов и папок. Если установить TRUE - в архиве будет корневая папка, которая была указана в качестве исходной.
	 * @param bool        $appendToArchive
	 * @return bool|string
	 */
	function archive( $pathInput, $pathOut = '', $arhiveName = 'arhive.zip', $baseDirInArhive = true, $appendToArchive = false ){
		$pathInput = self::realpath( $pathInput );
		if( !is_file( $pathOut ) ){
			self::mkdir( $pathOut );
		}
		$pathOut = $pathOut == '' ? $pathInput : self::realpath( $pathOut );
		if( !file_exists( $pathInput ) ){
			return false;
		}
		if( $baseDirInArhive === true ){
			$baseDirInArhive = basename( $pathInput ) . '/';
		}
		if( !$appendToArchive && file_exists( $pathOut . '/' . $arhiveName ) ){
			@unlink( $pathOut . '/' . $arhiveName );
		}
		$zip = new ZipArchive; // класс для работы с архивами
		if( $zip->open( $pathOut . '/' . $arhiveName, ZipArchive::CREATE ) === true ){ // создаем архив, если все прошло удачно продолжаем
			$files = self::scan_directory( $pathInput, false );
			foreach( $files as $path => $fileArr ){
				$zip->addFile( $path, $baseDirInArhive . str_replace( rtrim( $pathInput, '/' ) . '/', '', $path ) );
			}
			$zip->close(); // закрываем архив.
			return $pathOut;
		} else {
			return false;
		}
	}

	/**
	 * Распаковывает ZIP архив
	 * @param        $archivePath
	 * @param string $destinationDir
	 * @return bool
	 */
	function unpack( $archivePath, $destinationDir = '' ){
		$archivePath = self::realpath( $archivePath );
		if( !file_exists( $archivePath ) ){
			return false;
		}
		if( $destinationDir == '' ){
			$destinationDir = dirname( $archivePath );
		}
		$zip = new ZipArchive();
		if( $zip->open( $archivePath ) === true ){
			if( !$zip->extractTo( $destinationDir ) ){
				return false;
			}
			$zip->close();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Возвращает расширение файла, уть которого указан в аргументе $path
	 * @param $path
	 * @return string
	 */
	function file_extension( $path ){
		$pathInfo = pathinfo( $path );
		return isset( $pathInfo['extension'] ) ? $pathInfo['extension'] : '';
	}

	/**
	 * Возвращает содержимое файла PHP, подключая его через INCLUDE
	 * @param $path
	 * @version 1.1
	 * @return bool|string
	 */
	function get_content( $path, $vars = [] ){
		$path = self::realpath( $path );
		if( is_array( $vars ) ) extract( $vars, EXTR_OVERWRITE );
		if( file_exists( $path ) && is_readable( $path ) ){
			if( function_exists( 'ob_start' ) ){
				ob_start();
				include $path;
				return ob_get_clean();
			} else {
				hiweb()->console()->error( 'Функции [ob_start] не установлено на сервере', true );
				return false;
			}
		} else {
			hiweb()->console()->error( 'Файла [' . $path . '] нет', true );
			return false;
		}
	}

	/**
	 * Возвращает TRUE, если передан URL
	 * @param string $url - тестовый URL
	 * @return mixed
	 */
	function is_url( $url ){
		if( !is_string( $url ) ) return false;
		return filter_var( $url, FILTER_VALIDATE_URL );
	}

	/**
	 * Подключить файла PHP, CSS и JS из папки
	 * @param       $path
	 * @param array $fileExtension - массив типов подключаемых файлов, доступны типы: php, js, css
	 * @return array
	 */
	function include_dir( $path, $fileExtension = [ 'php', 'css', 'js' ] ){
		$subFiles = self::file( $path )->get_sub_files( $fileExtension );
		$R = [];
		foreach( $subFiles as $file ){
			if( !$file->is_readable ) continue;
			switch( $file->extension ){
				case 'php':
					include_once $file->path;
					$R[ $file->path ] = $file;
					break;
				case 'css':
					hiweb()->css( $file->url );
					$R[ $file->path ] = $file;
					break;
				case 'js':
					hiweb()->js( $file->url );
					$R[ $file->path ] = $file;
					break;
			}
		}
		return $R;
	}

	/**
	 * Возвращает объект файла
	 * @version 1.0
	 * @param $pathOrUrl
	 * @return hw_path_file
	 */
	function file( $pathOrUrl ){
		if( !array_key_exists( $pathOrUrl, self::files ) ){
			$file = new \hiweb\path\file( $pathOrUrl );
			self::$files[ $pathOrUrl ] = $file;
			self::$files[ $file->path ] = $file;
			self::$files[ $file->url ] = $file;
		}
		return self::files[ $pathOrUrl ];
	}

	/**
	 * Upload file or files
	 * @param $_fileOrUrl - $_FILES[file_id]
	 * @return int|WP_Error
	 */
	function upload( $_fileOrUrl ){
		if( is_array( $_fileOrUrl ) ){
			if( !isset( $_fileOrUrl['tmp_name'] ) ){
				return 0;
			}
			///
			ini_set( 'upload_max_filesize', '128M' );
			ini_set( 'post_max_size', '128M' );
			ini_set( 'max_input_time', 300 );
			ini_set( 'max_execution_time', 300 );
			///
			$tmp_name = $_fileOrUrl['tmp_name'];
			$fileName = $_fileOrUrl['name'];
			if( !is_readable( $tmp_name ) ){
				return - 1;
			}
		} elseif( is_string( $_fileOrUrl ) && self::is_url( $_fileOrUrl ) ) {
			$fileName = self::file( $_fileOrUrl )->basename;
			$tmp_name = $_fileOrUrl;
		} else return - 2;

		///File Upload
		$wp_filetype = wp_check_filetype( $fileName, null );
		$wp_upload_dir = wp_upload_dir();
		$newPath = $wp_upload_dir['path'] . '/' . sanitize_file_name( $fileName );
		if( !copy( $tmp_name, $newPath ) ){
			return - 2;
		}
		$attachment = [ 'guid' => $wp_upload_dir['url'] . '/' . $fileName, 'post_mime_type' => $wp_filetype['type'], 'post_title' => preg_replace( '/\.[^.]+$/', '', $fileName ), 'post_content' => '', 'post_status' => 'inherit' ];
		$attachment_id = wp_insert_attachment( $attachment, $newPath );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $newPath );
		wp_update_attachment_metadata( $attachment_id, $attachment_data );
		return $attachment_id;
	}

	/**
	 * Get an attachment ID given a URL.
	 * @param string $url
	 * @return int Attachment ID on success, 0 on failure
	 */
	function get_attachment_id( $url ){
		$attachment_id = 0;
		$dir = wp_upload_dir();
		if( false !== strpos( $url, $dir['baseurl'] . '/' ) ){ // Is URL in uploads directory?
			$file = basename( $url );
			$query_args = [
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'fields' => 'ids',
				'meta_query' => [
					[
						'value' => $file,
						'compare' => 'LIKE',
						'key' => '_wp_attachment_metadata',
					],
				]
			];
			$query = new WP_Query( $query_args );
			if( $query->have_posts() ){
				foreach( $query->posts as $post_id ){
					$meta = wp_get_attachment_metadata( $post_id );
					$original_file = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
					if( $original_file === $file || in_array( $file, $cropped_image_files ) ){
						$attachment_id = $post_id;
						break;
					}
				}
			}
		}
		return $attachment_id;
	}


	class path{

		/** @var array|hw_path_file[] */
		static $files = [];


	}