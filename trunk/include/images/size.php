<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 13.05.2018
	 * Time: 11:24
	 */

	namespace hiweb\images\image;


	use hiweb\console;
	use hiweb\images;
	use hiweb\images\image;
	use hiweb\path;


	class size{

		public $src;
		public $path;
		public $width = 0;
		public $height = 0;
		public $aspect = 0;
		public $pixels = 0;
		public $crop = false;
		public $is_cropped;
		public $name;
		public $mime_type = '';
		public $exists = false;
		protected $parent_image = null;

		/**
		 * size constructor.
		 *
		 * @param null $parent_image - parent \hiweb\image
		 */
		public function __construct( $parent_image = null ) {
			$this->parent_image = $parent_image;
		}

		/**
		 * @param $width - width in pixel
		 * @param $height - height - in pixel
		 * @param bool|int $crop - use crop, [true | 0 - force crop by size] [false | -1 - not crop, but inscribe] [+1 - not crop, but limit the minimum size]
		 * @param null $file_name - file name
		 */
		public function init( $width, $height, $crop = - 1, $file_name = null ) {
			$this->width  = $width;
			$this->height = $height;
			$this->pixels = $width * $height;
			$this->aspect = $width / $height;
			$this->crop   = $crop;
			if ( $this->parent_image instanceof image ) {
				$this->src        = $this->parent_image->base_url() . '/' . $file_name;
				$this->path       = $this->parent_image->base_dir() . '/' . $file_name;
				$this->exists     = file_exists( $this->path );
				$this->is_cropped = $this->parent_image->is_cropped( $this->width, $this->height );
			}
			if ( ! $this->exists ) {
				$this->src  = images::get_default_src( true );
				$this->path = path::url_to_path( images::get_default_src( true ) );
			}
		}


		public function make( $return_path = false ) {
			if ( ! $this->parent_image instanceof image ) {
				console::debug_error( 'Error while make new image size file, parent_image not instance of \hiweb\image...', $this );

				return false;
			}
			if ( $this->name == 'full' ) {
				console::debug_error( 'Error while make new image size file, this is original image size file...', $this );

				return false;
			}

			///MAKE NEW SIZE IMAGE
			if ( $this->parent_image->is_attachment_exists() ) {
				$editor = wp_get_image_editor( $this->parent_image->get_original_src( true ) );
				if ( $editor instanceof \WP_Image_Editor ) {
					$editor->resize( $this->width, $this->height, $this->crop );
					$R = $editor->save();
					if ( is_array( $R ) ) {
						$meta       = $this->parent_image->get_attachment_meta();
						$this->path = $R['path'];
						$this->src  = path::path_to_url( $this->path );
						unset( $R['path'] );
						$meta['sizes'][ $this->parent_image->size_to_string( $this->width, $this->height ) ] = $R;
						if ( wp_update_attachment_metadata( $this->parent_image->attach_id(), $meta ) ) {
							console::debug_info( 'Создан новый файл изображения', $R );
						} else {
							console::debug_error( 'Ошибка создания нового файла изображения', $R );
						}

						return ( $return_path ? $this->path : $this->src );
					} else {
						console::debug_error( 'Не удалось создать новый файл изображения', $R );
					}
				}
			}

			return false;
		}

		/**
		 * @return \hiweb\files\file
		 */
		public function file() {
			return \hiweb\file( $this->path );
		}
	}