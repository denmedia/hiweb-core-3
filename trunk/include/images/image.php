<?php

	namespace hiweb\images;


	use hiweb\arrays;
	use hiweb\console;
	use hiweb\files\file;
	use hiweb\images;
	use hiweb\path;
	use hiweb\strings;


	class image{

		public $attached_id = 0;
		/** @var null|\WP_Post */
		public $wp_post;
		/** @var array */
		public $image_meta = [];
		/** @var array */
		public $sizes = [];
		/** @var \WP_Image_Editor */
		private $wp_image_editor;


		public function __construct( $attach_id ){
			if( is_numeric( $attach_id ) ){
			$this->attached_id = $attach_id;
			$this->wp_post = get_post( $attach_id );
				if( $this->wp_post->post_type !== 'attachment' ) $this->wp_post = null;
			$this->image_meta = $this->get_image_meta();
			$this->sizes = $this->get_sizes();
		}
		}


		/**
		 * Get WP attachment meta data
		 *
		 * @param null $meta_key
		 *
		 * @return array|strings|mixed
		 */
		public function get_attachment_meta( $meta_key = null ){
			$R = [
				'width' => 0,
				'height' => 0,
				'file' => 0,
				'sizes' => [],
				'image_meta'
			];
			if( $this->is_attachment_exists() ){
				$meta = wp_get_attachment_metadata( $this->attached_id );
				return !is_string( $meta_key ) ? $meta : arrays::get_value_by_key( $meta, $meta_key );
			}
			return $R;
		}


		/**
		 * Get image meta data, like aperture, camera, created_timestamp, etc...
		 * @return array
		 */
		public function get_image_meta(){
			$R = [];
			if( $this->is_attachment_exists() ){
				$R = wp_get_attachment_metadata( $this->attached_id );
				$R = $R['image_meta'];
			}
			return $R;
		}


		/**
		 * Get original width
		 * @return int
		 */
		public function width(){
			return intval( $this->get_attachment_meta( 'width' ) );
		}


		/**
		 * Get original height
		 * @return int
		 */
		public function height(){
			return intval( $this->get_attachment_meta( 'height' ) );
		}


		/**
		 * @return float|int
		 */
		public function aspect(){
			return $this->width() / $this->height();
		}


		/**
		 * @return bool
		 */
		public function is_attachment_exists(){
			return $this->wp_post instanceof \WP_Post;
		}


		/**
		 * Get register
		 * @param bool $return_only_exists
		 * @return array
		 */
		public function get_sizes( $return_only_exists = true ){
			if( !$this->is_attachment_exists() ) return [];
			$sizes = $this->get_attachment_meta( 'sizes' );
			if( !is_array( $sizes ) ) return [];
			if( !$return_only_exists ) return $sizes;
			$R = [];
			foreach( $sizes as $size_name => $size_data ){
				$file_path = $this->base_dir() . '/' . $size_data['file'];
				if( file_exists( $file_path ) && is_readable( $file_path ) ) $R[ $size_name ] = $size_data;
			}
			return $R;
		}


		/**
		 * @return \string
		 */
		public function base_dir(){
			if( !$this->is_attachment_exists() ) return false;
			$path = explode( '/', $this->get_attachment_meta( 'file' ) );
			array_pop( $path );
			array_unshift( $path, wp_get_upload_dir()['basedir'] );
			return implode( '/', $path );
		}


		/**
		 * @return strings
		 */
		public function base_url(){
			if( !$this->is_attachment_exists() ) return false;
			$path = explode( '/', $this->get_attachment_meta( 'file' ) );
			array_pop( $path );
			array_unshift( $path, wp_get_upload_dir()['baseurl'] );
			return implode( '/', $path );
		}


		/**
		 * @param bool $return_path
		 * @return \string
		 */
		public function get_original_src( $return_path = false ){
			if( $this->is_attachment_exists() && !strings::is_empty( $this->get_attachment_meta( 'file' ) ) ){
				return ( $return_path ? wp_get_upload_dir()['basedir'] : wp_get_upload_dir()['baseurl'] ) . '/' . $this->get_attachment_meta( 'file' );
			}
			return images::get_default_src(true);
		}


		/**
		 * Return similar image url
		 * @param int $width
		 * @param int $height
		 * @param bool $return_path - return URL or PATH
		 * @return \string
		 */
		public function get_similar_src( $width = 100, $height = 100, $crop = false, $return_path = false ){
			$sizes_meta = $this->get_sizes();
			if( !$crop ){
				list( $width, $height ) = $this->get_size_by_limit( $width, $height );
			}
			$R = false;
			if( is_array( $sizes_meta ) && count( $sizes_meta ) > 0 ){
				$sizes = [];
				foreach( $sizes_meta as $size_name => $size_data ){
					$sizes[ intval( $size_data['width'] ) + intval( $size_data['height'] ) ] = $size_data;
				}
				ksort( $sizes );
				foreach( $sizes as $size_name => $size_data ){
					$R = $size_data['file'];
					if( intval( $size_data['width'] ) >= intval( $width ) && intval( $size_data['height'] ) >= intval( $height ) ) break;
				}
			} elseif( $this->is_attachment_exists() ) {
				return $this->get_original_src( $return_path );
			}
			if( $R == false ) return images::get_default_src(true);
			return ( $return_path ? $this->base_dir() : $this->base_url() ) . '/' . $R;
		}


		/**
		 * @param int $width
		 * @param int $height
		 * @return array
		 */
		public function get_size_by_limit( $width = 100, $height = 100 ){
			$width = intval( $width );
			$height = intval( $height );
			$aspect = $width / $height;
			if( $this->aspect() > $aspect ){
				$height = $width / $this->aspect();
			} else {
				$width = $height * $this->aspect();
			}
			return [ round( $width ), round( $height ) ];
		}


		/**
		 * @param int $width
		 * @param int $height
		 * @param bool $crop
		 * @return bool
		 */
		public function get_size_exists( $width = 150, $height = 150, $crop = false ){
			$sizes_meta = $this->get_sizes();
			if( $crop ){
				if( is_array( $sizes_meta ) ) foreach( $sizes_meta as $size_name => $size_data ){
					if( intval( $size_data['width'] ) == intval( $width ) && intval( $size_data['height'] ) == intval( $height ) ) return true;
				}
			} else {
				$limit_sizes = $this->get_size_by_limit( $width, $height );
				if( is_array( $sizes_meta ) ) foreach( $sizes_meta as $size_name => $size_data ){
					if( intval( $size_data['width'] ) == $limit_sizes[0] && intval( $size_data['height'] ) == $limit_sizes[1] ) return true;
				}
			}
			return false;
		}


		/**
		 * Return strong image size url
		 * @param $width
		 * @param $height
		 * @param bool $crop
		 * @param bool $return_path - return URL or PATH
		 * @return \string
		 */
		public function get_src( $width, $height, $crop = false, $return_path = false ){
			///FILTER GREAT WIDTH or HEIGHT
			if( $width > $this->width() || $height > $this->height() ){
				$aspect = $width / $height;
				if( $this->aspect() < $aspect ){
					$width = $this->width();
					$height = round( $width / ( $crop ? $aspect : $this->aspect() ) );
				} else {
					$height = $this->height();
					$width = round( $height * ( $crop ? $aspect : $this->aspect() ) );
				}
			}
			///FILTER SIZE EXISTS
			if( $this->get_size_exists( $width, $height, $crop ) ){
				return $this->get_similar_src( $width, $height, $crop, $return_path );
			}
			///MAKE NEW SIZE IMAGE
			if( $this->is_attachment_exists() ){
				$editor = wp_get_image_editor( $this->get_original_src( true ) );
				if( $editor instanceof \WP_Image_Editor ){
					$editor->resize( $width, $height, $crop );
					$R = $editor->save();
					if( is_array( $R ) ){
						console::debug_info( 'Создан новый файл изображения', $R );
						$meta = $this->get_attachment_meta();
						$path = $R['path'];
						unset( $R['path'] );
						$meta['sizes'][ $width . 'x' . $height . ( $crop ? 'c' : '' ) ] = $R;
						wp_update_attachment_metadata( $this->attached_id, $meta );
						return ( $return_path ? $path : path::path_to_url( $path ) );
					} else {
						console::debug_error( 'Не удалось создать новый файл изображения', $R );
					}
				}
			}
			return images::get_default_src(true);
		}

	}