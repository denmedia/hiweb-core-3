<?php

	namespace hiweb\tools;


	use hiweb\arrays;
	use function hiweb\css;
	use hiweb\hidden_methods;
	use function hiweb\js;


	class thumbnail_upload{

		use hidden_methods;

		static private $post_types = [];
		static private $taxonomies = [];

		static $default_preview_size = [ 150, 150 ];

		static private $instance;


		public function __construct(){
			///Print Thumbnails to Columns
			add_action( 'admin_init', function(){
				if( is_array( $this->post_types() ) ) foreach( $this->post_types() as $post_type ){

				}
			} );
			add_action( 'current_screen', function(){
				if( is_array( $this->post_types() ) ) foreach( $this->post_types() as $post_type ){
					if( get_current_screen()->post_type == $post_type ){

						///Thumbnail Upload
						css( HIWEB_URL_CSS . '/tools-thumbnail-upload.css' );
						$script_id = js( HIWEB_URL_VENDORS . '/dropzone.js', [ 'jquery' ] );
						js( HIWEB_URL_JS . '/tools-thumbnail-upload.js', [ 'jquery', $script_id ], true );

						if( $post_type == 'post' ){
							add_action( 'manage_posts_custom_column', [ $this, 'manage_posts_custom_column' ], 10, 2 );
							add_filter( 'manage_posts_columns', [ $this, 'manage_posts_columns' ] );
						} elseif( $post_type == 'page' ) {
							add_action( 'manage_pages_custom_column', [ $this, 'manage_posts_custom_column' ], 10, 2 );
							add_filter( 'manage_pages_columns', [ $this, 'manage_posts_columns' ] );
						} else {
							add_action( 'manage_' . $post_type . '_posts_custom_column', [ $this, 'manage_posts_custom_column' ], 10, 2 );
							add_filter( 'manage_' . $post_type . '_posts_columns', [ $this, 'manage_posts_columns' ] );
						}
					}
				}
			} );
			///TOOLS THUMBNAIL UPLOAD
			add_action( 'wp_ajax_hiweb-tools-thumbnail-upload-post-type-uploading', function(){
				$post_id = intval( $_SERVER['HTTP_POSTID'] );
				$file = $_FILES['file'];
				if( $post_id > 0 ){
					///Upload File
					$attachment_id = $this->upload( $file );
					if( $attachment_id <= 0 ){
						wp_send_json_error( 'не удалось загрузит  файл' );
					} else {
						if( $_SERVER['HTTP_POSTTYPE'] == 'taxonomy' ){
							$R = update_term_meta( $post_id, 'thumbnail_id', $attachment_id );
						} else {
							$R = set_post_thumbnail( $post_id, $attachment_id );
						}
						if( $R == false ){
							wp_send_json_error( 'не удалось установить миниатюру для записи' );
						} else {
							$img_src = wp_get_attachment_image_url( $attachment_id, self::$default_preview_size );
							wp_send_json_success( $img_src );
						}
					}
				}
				wp_send_json_error( 'Не верный ID записи' );
			} );
			add_action( 'wp_ajax_hiweb-tools-thumbnail-upload-post-type-remove', function(){
				$post_id = intval( $_POST['post_id'] );
				if( $post_id > 0 ){
					$R = delete_post_thumbnail( $post_id );
					if( $R ) wp_send_json_success( 'Миниатюра удалена' ); else wp_send_json_error( 'Не удалось удалить миниатюру' );
				} else {
					wp_send_json_error( 'Не верный ID записи' );
				}
			} );
			add_action( 'wp_ajax_hiweb-tools-thumbnail-upload-post-type-set', function(){
				$post_id = intval( $_POST['post_id'] );
				$attachment_id = intval( $_POST['thumbnail_id'] );
				if( $attachment_id == 0 ){
					wp_send_json_error( 'Не указан индификатор миниатюры post:[thumbnail_id]' );
				} elseif( $post_id > 0 ) {
					if( $_POST['type'] == 'taxonomy' ){
						$R = update_term_meta( $post_id, 'thumbnail_id', $attachment_id );
					} else {
						$R = set_post_thumbnail( $post_id, $attachment_id );
					}
					if( $R == false ){
						wp_send_json_error( 'не удалось установить миниатюру для товара' );
					} else {
						$img_src = wp_get_attachment_image_url( $attachment_id, self::$default_preview_size );
						wp_send_json_success( $img_src );
					}
				}
				wp_send_json_error( 'Не верный ID товара' );
			} );
		}


		public function manage_posts_custom_column( $column_name = '', $post_id = 0 ){
			if( intval( $post_id ) > 0 && trim( $column_name ) == 'hw_tool_thumbnail' ){
				$P = get_post( $post_id );
				if( $this->post_type_exists( $P->post_type ) ){
					?>
					<div id="thumb_hw_upload_zone-<?= $P->ID ?>" class="thumb_hw_upload_zone" data-is-process="0" data-has-thumbnail="<?= has_post_thumbnail( $P ) ? '1' : '0' ?>">
						<div data-ctrl="noimg">
							<div class="left" data-ctrl-btn="upload" title="Загрузить миниатюру"><i class="dashicons dashicons-upload"></i></div>
							<div class="right" data-ctrl-btn="media" title="Выбрать из библиотеки"><i class="dashicons dashicons-admin-media"></i></div>
						</div>
						<div data-ctrl="img">
							<div class="left" data-ctrl-btn="upload" title="Загрузить миниатюру"><i class="dashicons dashicons-upload"></i></div>
							<div class="right" data-ctrl-btn="remove" title="Удалить миниатюру"><i class="dashicons dashicons-no"></i></div>
						</div>
						<div class="img noimg"></div>
						<div class="img" data-img <?php
							if( has_post_thumbnail( $P ) ){
						?>style="background-image: url(<?= get_image( get_post_thumbnail_id( $P ) )->get_src('thumbnail', 0) ?>)"<?php
							}
						?>></div>
						<div data-drop-logo>
							<i class="dashicons dashicons-upload"></i>
						</div>
						<div data-loader>
							<i class="dashicons dashicons-update"></i>
						</div>
					</div>
					<?php
				}
			}
		}


		public function manage_posts_columns( $posts_columns ){
			wp_enqueue_media();
			$posts_columns = arrays::push( $posts_columns, ' ', 0, 'hw_tool_thumbnail' );
			return $posts_columns;
		}


		/**
		 * @param string $post_type
		 * @return array
		 */
		public function add_post_type( $post_type = 'post' ){
			if( is_string( $post_type ) ) $post_type = [ $post_type ];
			foreach( $post_type as $pt_name ){
				self::$post_types[] = $pt_name;
			}
			self::$post_types = array_unique( self::$post_types );
			return self::$post_types;
		}


		/**
		 * @return array
		 */
		public function post_types(){
			return self::$post_types;
		}


		/**
		 * @param string $post_type
		 * @return array
		 */
		public function remove_post_type( $post_type = 'post' ){
			$flip = array_flip( self::$post_types );
			if( array_key_exists( $post_type, $flip ) ){
				unset( $flip[ $post_type ] );
				self::$post_types = array_keys( $flip );
			}
			return self::$post_types;
		}


		/**
		 * @param $post_type
		 * @return bool
		 */
		public function post_type_exists( $post_type ){
			$flip = array_flip( self::$post_types );
			return array_key_exists( $post_type, $flip );
		}


		/**
		 * @param string $tax_name
		 * @return array
		 */
		public function taxonomy( $tax_name = 'category' ){
			//TODO
			return self::$taxonomies;
		}


		/**
		 * @return array
		 */
		public function taxonomies(){
			//TODO
			return self::$taxonomies;
		}


		/**
		 * @param string $tax_name
		 * @return array
		 */
		public function remove_taxonomy( $tax_name = 'category' ){
			//TODO
			return self::$taxonomies;
		}


		/**
		 * @param $post_type
		 * @return bool
		 */
		public function taxonomy_exists( $post_type ){
			$flip = array_flip( self::$post_types );
			return array_key_exists( $post_type, $flip );
		}


		/**
		 * Передайте массив одного файла для загрузки
		 * @param $_file
		 * @return int|\WP_Error
		 */
		public function upload( $_file ){
			if( !isset( $_file['tmp_name'] ) ){
				return 0;
			}
			///
			ini_set( 'upload_max_filesize', '128M' );
			ini_set( 'post_max_size', '128M' );
			ini_set( 'max_input_time', 300 );
			ini_set( 'max_execution_time', 300 );
			///
			$tmp_name = $_file['tmp_name'];
			$fileName = $_file['name'];
			if( !is_readable( $tmp_name ) ){
				return - 1;
			}
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


		static function post_type($post_type){
			if(!self::$instance instanceof thumbnail_upload){
				self::$instance = new thumbnail_upload();
			}
			self::$instance->add_post_type($post_type);
		}

	}