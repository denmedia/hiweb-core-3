<?php

	namespace hiweb\tools\thumbnail_upload;


	use hiweb\arrays;
	use hiweb\path;


	class post_type{

		protected $post_type = 'post';
		protected $preview_size = 'thumbnail';


		public function __construct( $post_type ){
			$this->post_type = $post_type;
			if( $this->post_type == 'post' ){
				add_action( 'manage_posts_custom_column', [ $this, 'manage_posts_custom_column' ], 10, 2 );
				add_filter( 'manage_posts_columns', [ $this, 'manage_posts_columns' ] );
			} elseif( $this->post_type == 'page' ) {
				add_action( 'manage_pages_custom_column', [ $this, 'manage_posts_custom_column' ], 10, 2 );
				add_filter( 'manage_pages_columns', [ $this, 'manage_posts_columns' ] );
			} else {
				add_action( 'manage_' . $this->post_type . '_posts_custom_column', [ $this, 'manage_posts_custom_column' ], 10, 2 );
				add_filter( 'manage_' . $this->post_type . '_posts_columns', [ $this, 'manage_posts_columns' ] );
			}
			add_action( 'wp_ajax_hiweb-tools-thumbnail-upload-post-type-uploading', [ $this, 'ajax_upload' ] );
			add_action( 'wp_ajax_hiweb-tools-thumbnail-upload-post-type-remove', [ $this, 'ajax_remove' ] );
			add_action( 'wp_ajax_hiweb-tools-thumbnail-upload-post-type-set', [ $this, 'ajax_set' ] );
		}


		public function manage_posts_custom_column( $column_name = '', $post_id = 0 ){
			if( intval( $post_id ) > 0 && trim( $column_name ) == 'hw_tool_thumbnail' ){
				$P = get_post( $post_id );
				if( $this->post_type == $P->post_type ){
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
						?>style="background-image: url(<?= get_the_post_thumbnail_url( $P, $this->preview_size ) ?>)"<?php
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


		public function manage_posts_columns( $posts_columns, $post_type ){
			wp_enqueue_media();
			$posts_columns = arrays::push( $posts_columns, ' ', 0, 'hw_tool_thumbnail' );
			return $posts_columns;
		}


		public function ajax_upload(){
			$post_id = intval( $_SERVER['HTTP_POSTID'] );
			$file = $_FILES['file'];
			if( $post_id > 0 ){
				///Upload File
				$attachment_id = path::upload( $file );
				if( $attachment_id <= 0 ){
					wp_send_json_error( 'Не удалось загрузить файл', $_FILES );
				} else {
					$R = set_post_thumbnail( $post_id, $attachment_id );
					if( $R == false ){
						wp_send_json_error( 'Не удалось установить миниатюру для товара' );
					} else {
						$img_src = wp_get_attachment_image_url( $attachment_id, $this->preview_size );
						wp_send_json_success( $img_src );
					}
				}
			}
			wp_send_json_error( 'Не верный ID поста' );
		}


		public function ajax_set(){
			$post_id = path::request( 'post_id' );
			$P = get_post( $post_id );
			if( !$P instanceof \WP_Post ) return wp_send_json_error( 'Не верно указан post_id [' . $post_id . ']' );
			///
			$attach_id = path::request( 'thumbnail_id' );
			$attached = wp_get_attachment_image_url( $attach_id );
			if( $attached == false ) return wp_send_json_error( 'Не верно указан attach_id [' . $attach_id . ']' );
			///
			return set_post_thumbnail( $P, $attach_id ) ? wp_send_json_success( 'Set post thumbnail is success!' ) : wp_send_json_error( 'Could not set a thumbnail [' . $attach_id . '] for post [' . $post_id . ']' );
		}


		/**
		 * Ajax remove
		 * @return bool
		 */
		public function ajax_remove(){
			$R = delete_post_thumbnail( intval( path::request( 'post_id' ) ) );
			return $R ? wp_send_json_success( 'remove done!' ) : wp_send_json_error( 'remove thumbnail error' );
		}

	}