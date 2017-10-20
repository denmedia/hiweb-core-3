<?php

	namespace hiweb\files;


	use hiweb\files;
	use hiweb\path;


	class file{

		public $path = '';
		public $url = '';
		///
		public $basename = '';
		public $filename = '';
		public $extension = '';
		public $dirname = '';
		///
		public $is_dir = false;
		public $is_executable = false;
		public $is_file = false;
		public $is_link = false;
		public $is_readable = false;
		public $is_uploaded_file = false;
		public $is_writable = false;
		public $is_exists = false;
		///
		public $filetype;
		///
		private $size;
		private $subFiles = [];
		///
		public $fileatime = 0;
		public $filectime = 0;
		public $filemtime = 0;
		public $filegroup;
		public $fileinode;
		public $fileowner;
		public $fileperms;


		public function __construct( $path ){
			if( path::is_url( $path ) ){
				$this->path = path::url_to_path( $path );
				$this->url = path::prepare_url( $path );
			} else {
				$this->path = path::realpath( $path );
				$this->url = path::path_to_url( $this->path );
			}
			////
			$this->basename = basename( $this->path );
			$this->extension = path::file_extension( $this->path );
			$this->filename = basename( $this->path, '.' . $this->extension );
			$this->dirname = dirname( $this->path );
			////
			$this->is_exists = file_exists( $this->path );
			if( $this->is_exists ){
				$this->is_readable = is_readable( $this->path );
				$this->is_dir = is_dir( $this->path );
				$this->is_file = is_file( $this->path );
				$this->is_link = is_link( $this->path );
				$this->is_writable = is_writable( $this->path );
				$this->is_uploaded_file = is_uploaded_file( $this->path );
				$this->is_executable = is_executable( $this->path );
				///
				$this->filemtime = filemtime( $this->path );
				$this->filectime = filectime( $this->path );
				$this->fileatime = fileatime( $this->path );
				///
				$this->filegroup = filegroup( $this->path );
				$this->fileinode = fileinode( $this->path );
				$this->fileowner = fileowner( $this->path );
				$this->fileperms = fileperms( $this->path );
				$this->filetype = filetype( $this->path );
			}
		}


		public function get_size(){
			$R = false;
			if( $this->is_file ){
				return filesize( $this->path );
			} elseif( $this->is_dir ) {
				//todo!
			}
			return $R;
		}


		/**
		 * Возвращает массив вложенных файлов
		 * @param array $mask - маска файлов
		 * @return array|file[]
		 */
		public function get_sub_files( $mask = [] ){
			$maskKey = json_encode( $mask );
			if( !array_key_exists( $maskKey, $this->subFiles ) ){
				$this->subFiles[ $maskKey ] = [];
				if( $this->is_dir ) foreach( scandir( $this->path ) as $subFileName ){
					if( $subFileName == '.' || $subFileName == '..' ) continue;
					$subFilePath = $this->path . '/' . $subFileName;
					$subFile = files::get( $subFilePath );
					$this->subFiles[ $maskKey ][ $subFile->path ] = $subFile;
					$this->subFiles[ $maskKey ] = array_merge( $this->subFiles[ $maskKey ], $subFile->get_sub_files( $mask ) );
				}
			}
			return $this->subFiles[ $maskKey ];
		}


		public function get_content(){
			//todo
		}

	}