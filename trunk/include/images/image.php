<?php

	namespace hiweb\images;


	use hiweb\arrays;
	use hiweb\console;
	use hiweb\images;
	use hiweb\images\image\size;
	use hiweb\strings;


	class image{


		protected $attach_id = 0;
		/** @var null|\WP_Post */
		protected $wp_post;
		/** @var array */
		protected $image_meta = [];
		/** @var array */
		protected $sizes = [];

		/** @var size[] */
		protected $sizes_by_name;
		/** @var size[] */
		protected $sizes_by_pixels = [];
		/** @var size[] */
		protected $sizes_by_size = [];
		/** @var size[] */
		protected $sizes_by_crop = [];


		public function __construct( $attach_id ){
			if( is_numeric( $attach_id ) ){
				$this->attach_id = $attach_id;
				$this->wp_post = get_post( $attach_id );
				if( $this->wp_post instanceof \WP_Post ){
					if( $this->wp_post->post_type !== 'attachment' ){
						$this->wp_post = null;
					}
				}
				$this->image_meta = $this->get_image_meta();
				$this->sizes = $this->get_sizes_meta();
			}
		}


		/**
		 * @return int|string
		 */
		public function attach_id(){
			return intval( $this->attach_id );
		}


		/**
		 * @return mixed
		 */
		public function alt(){
			return get_post_meta( $this->attach_id, '_wp_attachment_image_alt', true );
		}


		/**
		 * @return mixed
		 */
		public function title(){
			if( !$this->is_attachment_exists() ){
				return '';
			}

			return $this->wp_post->post_title;
		}


		/**
		 * @return string
		 */
		public function description(){
			if( !$this->is_attachment_exists() ){
				return '';
			}

			return $this->wp_post->post_content;
		}


		/**
		 * @return string
		 */
		public function caption(){
			if( !$this->is_attachment_exists() ){
				return '';
			}

			return $this->wp_post->post_excerpt;
		}


		/**
		 * Get WP attachment meta data
		 * @param null $meta_key
		 * @return array|string|mixed
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
				$meta = wp_get_attachment_metadata( $this->attach_id );

				return !is_string( $meta_key ) ? $meta : arrays::get_value_by_key( $meta, $meta_key );
			}

			return $R;
		}


		/**
		 * Get image meta data, like aperture, camera, created_timestamp, etc...
		 * @return array
		 */
		protected function get_image_meta(){
			$R = [];
			if( $this->is_attachment_exists() ){
				$R = wp_get_attachment_metadata( $this->attach_id );
				$R = $R['image_meta'];
			}

			return $R;
		}


		/**
		 * @return bool
		 */
		public function is_attachment_exists(){
			return $this->wp_post instanceof \WP_Post;
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
		 * Return Attachment Title
		 * @return string
		 */
		public function filename(){
			if($this->wp_post instanceof \WP_Post) {
				return $this->wp_post->post_title;
			}
			return '';
		}


		/**
		 * @return string
		 */
		public function base_dir(){
			if( !$this->is_attachment_exists() ){
				return false;
			}
			$path = explode( '/', $this->get_attachment_meta( 'file' ) );
			array_pop( $path );
			array_unshift( $path, wp_get_upload_dir()['basedir'] );

			return implode( '/', $path );
		}


		/**
		 * @return string
		 */
		public function base_url(){
			if( !$this->is_attachment_exists() ){
				return false;
			}
			$path = explode( '/', $this->get_attachment_meta( 'file' ) );
			array_pop( $path );
			array_unshift( $path, wp_get_upload_dir()['baseurl'] );

			return implode( '/', $path );
		}


		/**
		 * Get register
		 * @param bool $return_only_exists
		 * @return array
		 */
		public function get_sizes_meta( $return_only_exists = true ){
			if( !$this->is_attachment_exists() ){
				return [];
			}
			$sizes = $this->get_attachment_meta( 'sizes' );
			if( !is_array( $sizes ) ){
				return [];
			}
			if( !$return_only_exists ){
				return $sizes;
			}
			$R = [];
			foreach( $sizes as $size_name => $size_data ){
				$file_path = $this->base_dir() . '/' . $size_data['file'];
				if( file_exists( $file_path ) && is_readable( $file_path ) ){
					$R[ $size_name ] = $size_data;
				}
			}

			return $R;
		}


		/**
		 * @param bool $return_path
		 * @return string
		 */
		public function get_original_src( $return_path = false ){
			if( $this->is_attachment_exists() && !strings::is_empty( $this->get_attachment_meta( 'file' ) ) ){
				return ( $return_path ? wp_get_upload_dir()['basedir'] : wp_get_upload_dir()['baseurl'] ) . '/' . $this->get_attachment_meta( 'file' );
			}

			return images::get_default_src( true );
		}


		/**
		 * Return similar image url
		 * @deprecated
		 * @param int  $width
		 * @param int  $height
		 * @param bool $crop        - return crop and full images
		 * @param bool $return_path - return URL or PATH
		 * @return \string
		 */
		public function get_similar_src( $width = 100, $height = 100, $crop = false, $return_path = false ){
			return $this->get_src_larger( [ $width, $height ], $crop, $return_path );
		}


		/**
		 * @return array|size[]
		 */
		public function get_sizes(){
			if( !is_array( $this->sizes_by_name ) ){
				if( $this->is_attachment_exists() ){
					$this->sizes_by_name = [];
					$meta = wp_get_attachment_metadata( $this->attach_id );
					if( array_key_exists( 'file', $meta ) && array_key_exists( 'width', $meta ) && array_key_exists( 'height', $meta ) ){
						$SIZE = new size( $this );
						$SIZE->init( $meta['width'], $meta['height'], 0, basename( $meta['file'] ) );
						$this->sizes_by_name['full'] = $SIZE;
						$this->sizes_by_name['full']->name = 'full';
						if( array_key_exists( 'sizes', $meta ) && is_array( $meta['sizes'] ) ){

							foreach( $meta['sizes'] as $name => $size_meta ){
								$SIZE = new size( $this );
								$SIZE->name = $name;
								$SIZE->mime_type = $size_meta['mime-type'];
								$SIZE->init( $size_meta['width'], $size_meta['height'], 0, $size_meta['file'] );
								$this->sizes_by_name[ $name ] = $SIZE;
							}
						}
					}
					//Fill
					foreach( $this->sizes_by_name as $name => $size ){
						$this->sizes_by_pixels[ $size->pixels ] = $size;
						$this->sizes_by_size[ $this->size_to_string( $size->width, $size->height ) ] = $size;
						$this->sizes_by_crop[ $size->is_cropped ? 1 : 0 ][ $name ] = $size;
					}
					ksort( $this->sizes_by_pixels );
					ksort( $this->sizes_by_size );
					ksort( $this->sizes_by_crop );
				}
			}

			return $this->sizes_by_name;
		}


		public function get_sizes_by_pixels(){
			$this->get_sizes();

			return $this->sizes_by_pixels;
		}


		public function get_sizes_by_size(){
			$this->get_sizes();

			return $this->sizes_by_size;
		}


		public function get_sizes_by_crop(){
			$this->get_sizes();

			return $this->sizes_by_crop;
		}


		/**
		 * @param $width
		 * @param $height
		 * @return string
		 */
		public function size_to_string( $width, $height ){
			$width = intval( $width );
			$height = intval( $height );

			return $width . 'x' . $height . ( $this->is_cropped( $width, $height ) ? 'c' : '' );
		}


		/**
		 * @param string $sizeName - thumbnail, medium ...etc
		 * @return array
		 */
		public function name_to_size( $sizeName ){
			$R = [ 150, 150 ];
			if( array_key_exists( $sizeName, array_flip( get_intermediate_image_sizes() ) ) ){
				switch( $sizeName ){
					case 'thumbnail':
						$w = intval( get_option( 'thumbnail_size_w', 150 ) );
						$h = intval( get_option( 'thumbnail_size_h', 150 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = [ $w, $h ];
						break;
					case 'medium':
						$w = intval( get_option( 'medium_size_w', 150 ) );
						$h = intval( get_option( 'medium_size_h', 150 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = [ $w, $h ];
						break;
					case 'medium_large':
						$w = intval( get_option( 'medium_large_size_w', 150 ) );
						$h = intval( get_option( 'medium_large_size_h', 150 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = [ $w, $h ];
						break;
					case 'large':
						$w = intval( get_option( 'large_size_w', 150 ) );
						$h = intval( get_option( 'large_size_h', 150 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = [ $w, $h ];
						break;
					default:
						$size_data = wp_get_additional_image_sizes();
						$size_data = $size_data[ $sizeName ];
						$R = $this->desire_to_size( $size_data['width'], $size_data['height'], $size_data['crop'] );
						break;
				}
			} elseif( $sizeName == 'full' ) {
				$R = [ $this->width(), $this->height() ];
			} else {
				console::debug_warn( 'Intermediate image size not found. The maximum size of the image is established.', $sizeName );
				$R = [ $this->width(), $this->height() ];
			}

			return $R;
		}


		/**
		 * Return size array from desire sizes and crop
		 * @param     $width
		 * @param     $height
		 * @param int $crop
		 * @return array[int,int]
		 */
		public function desire_to_size( $width, $height, $crop = - 1 ){
			$width = intval( $width );
			$height = intval( $height );
			if( $width > $this->width() ){
				$width = $this->width();
			}
			if( $height > $this->height() ){
				$height = $this->height();
			}
			if( $this->is_cropped( $width, $height ) ){
				$aspect = $width / $height;
				if( $aspect > $this->aspect() || $crop === - 1 || $crop === false ){
					$height = round( $width / $this->aspect() );
				} else {
					$width = round( $height * $this->aspect() );
				}
			}

			return [ $width, $height ];
		}


		/**
		 * Get \hiweb\images\image\size by sizeName or sizeArray
		 * @param string $sizeOrName
		 * @param int    $crop
		 * @return size|null
		 */
		public function get_size_by( $sizeOrName = 'thumbnail', $crop = 0 ){
			$this->get_sizes();
			$SIZE = null;
			if( $this->is_attachment_exists() ){
				if( is_int( $sizeOrName ) && is_int( $crop ) && $crop > 1 ){
					$sizeOrName = [ $sizeOrName, $crop ];
					$crop = false;
				}
				if( is_string( $sizeOrName ) && array_key_exists( $sizeOrName, $this->sizes_by_name ) ){
					$sizeOrName = $this->name_to_size( $sizeOrName );
					//$SIZE = $this->sizes_by_name[ $sizeOrName ];
				} else {
					//deprecated
				}
				$desireSize = $this->desire_to_size( $sizeOrName[0], $sizeOrName[1], $crop );
				$sizeString = $this->size_to_string( $desireSize[0], $desireSize[1] );
				if( is_array( $sizeOrName ) && array_key_exists( $sizeString, $this->sizes_by_size ) ){
					$SIZE = $this->sizes_by_size[ $sizeString ];
				} elseif( is_array( $desireSize ) ) {
					$new_file_name = $this->sizes_by_name['full']->file()->filename . '-' . $desireSize[0] . 'x' . $desireSize[1] . '.' . $this->sizes_by_name['full']->file()->extension;
					$SIZE = new size( $this );
					$SIZE->name = $this->size_to_string( $desireSize[0], $desireSize[1] );
					$SIZE->init( $desireSize[0], $desireSize[1], $crop, $new_file_name );
				} else {
					$SIZE = new size( $this );
					$SIZE->init( 0, 0, - 1, $this->sizes_by_name['full']->file()->filename . '-0x0' );
				}
			}

			return $SIZE;
		}


		/**
		 * Returns true if the transmitted dimensions of the image are cropped
		 * @param $width
		 * @param $height
		 * @return bool
		 */
		public function is_cropped( $width, $height ){
			$width = intval( $width );
			$height = intval( $height );
			if( $width === 0 || $height === 0 ){
				return true;
			}

			return abs( ( $width / $height ) - $this->aspect() ) > 0.01;
		}


		/**
		 * Return SIZE object of IMAGE
		 * @param string $sizeOrName
		 * @param int    $crop
		 * @param bool   $make_file
		 * @return size|null
		 */
		public function get_size( $sizeOrName = 'thumbnail', $crop = 0, $make_file = true ){
			$SIZE = $this->get_size_by( $sizeOrName, $crop );
			if( $SIZE instanceof size && !$SIZE->exists && $make_file ){
				$SIZE->make();
			}

			return $SIZE;
		}


		/**
		 * Return src of image by size
		 * @param string $sizeOrName
		 * @param int    $crop
		 * @param bool   $make_file
		 * @return string
		 */
		public function get_src( $sizeOrName = 'thumbnail', $crop = 0, $make_file = true ){
			$SIZE = $this->get_size( $sizeOrName, $crop, $make_file );
			return $SIZE instanceof size ? $SIZE->src : images::get_default_src();
		}


		/**
		 * @param string $sizeOrName
		 * @param bool   $return_crop
		 * @param bool   $return_path
		 * @return string
		 */
		public function get_src_limit( $sizeOrName = 'thumbnail', $return_crop = false, $return_path = false ){
			$sizeArray = is_array( $sizeOrName ) ? $sizeOrName : $this->name_to_size( $sizeOrName );
			/** @var size[] $sizes_by_pixels */
			$sizes_by_pixels = array_reverse( $this->get_sizes_by_pixels() );
			foreach( $sizes_by_pixels as $pixels => $size ){
				if( ( $size->width <= $sizeArray[0] && $size->height <= $sizeArray[1] ) && ( $return_crop || !$size->is_cropped ) ){
					return $return_path ? $size->path : $size->src;
				}
			}

			return $this->get_original_src( $return_path );
		}


		public function get_src_larger( $sizeOrName = 'thumbnail', $return_crop = false, $return_path = false ){
			$sizeArray = is_array( $sizeOrName ) ? $sizeOrName : $this->name_to_size( $sizeOrName );
			/** @var size[] $sizes_by_pixels */
			$sizes_by_pixels = $this->get_sizes_by_pixels();
			foreach( $sizes_by_pixels as $pixels => $size ){
				if( ( $size->width >= $sizeArray[0] && $size->height >= $sizeArray[1] ) && ( $return_crop || !$size->is_cropped ) ){
					return $return_path ? $size->path : $size->src;
				}
			}

			return $this->get_original_src( $return_path );
		}


		/**
		 * Get <img src="..."/> html string
		 * @param string $size
		 * @param bool   $crop
		 * @param array  $attr
		 * @param bool   $make_file
		 * @return string
		 */
		public function html( $size = 'thumbnail', $crop = false, $attr = [], $make_file = true ){
			if( !$this->is_attachment_exists() ){
				$size = is_array( $size ) ? ' width="' . $size[0] . '" height="' . $size[1] . '"' : '';

				return '<img src="' . images::get_default_src() . '" ' . $size . '/>';
			} else {
				$SIZE = $this->get_size( $size, $crop, $make_file );
				$other_sizes = $this->get_sizes();
				if( isset( $other_sizes[ $SIZE->name ] ) ){
					unset( $other_sizes[ $SIZE->name ] );
				}

				$tags = [
					'src' => $SIZE->src,
					'width' => $SIZE->width,
					'height' => $SIZE->height
				];
				$tags['srcset'] = [];
				if( is_array( $other_sizes ) && count( $other_sizes ) > 0 ){
					foreach( $other_sizes as $other_size ){
						if( ( $crop !== false && $crop !== 0 ) || !$other_size->is_cropped ){
							$tags['srcset'][] = $other_size->src . ' ' . $other_size->width . 'w';
						}
					}
				}
				$tags['srcset'] = implode( ', ', $tags['srcset'] );
				if( $this->alt() != '' ){
					$tags['alt'] = htmlentities( $this->alt(), ENT_QUOTES, 'UTF-8' );
				}
				//Attr merge
				$tags = array_merge( $tags, $attr );
				//
				$tags_string = [];
				foreach( $tags as $key => $val ){
					$tags_string[] = $key . '="' . addslashes( $val ) . '"';
				}
				$tags_string = implode( ' ', $tags_string );

				$R = '<img ' . $tags_string . '/>';

				return $R;
			}
			//return wp_get_attachment_image( $this->attach_id, $size, false, $attr );
		}


	}