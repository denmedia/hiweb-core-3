<?php

	namespace hiweb\tools;


	use hiweb\images;


	class complete_remove_post{

		public $post_id = 0;


		public function __construct( $post_id ){
			$this->post_id = $post_id;
		}


		/**
		 * @return array|null|\WP_Post
		 */
		public function get_post(){
			return get_post( $this->post_id );
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return $this->get_post() instanceof \WP_Post;
		}


		/**
		 * @return array
		 */
		public function get_attachment_ids(){
			$R = [];
			if( $this->is_exists() ){
				if( has_post_thumbnail( $this->post_id ) ){
					$R[] = get_post_thumbnail_id( $this->post_id );
				}
				$content = apply_filters( 'the_content', $this->get_post()->post_content );
				$doc = new \DOMDocument();
				@$doc->loadHTML( $content );
				$tags = $doc->getElementsByTagName( 'img' );
				foreach( $tags as $tag ){
					$R[] = images::get( $tag->getAttribute( 'src' ) )->attach_id();
				}
			}
			return $R;
		}


		/**
		 * @return false|null|\WP_Post
		 */
		public function do_remove(){
			foreach( $this->get_attachment_ids() as $attachment_id ){
				if( $attachment_id == $this->post_id ) continue;
				( new complete_remove_post( $attachment_id ) )->do_remove();
			}
			return wp_delete_post( $this->post_id, true );
		}


	}