<?php

	use hiweb\fields\field\type;


	\hiweb\fields\field\types::register( 'post', 'post' );


	class post extends type{

		protected $attributes = [ 'post_type' => [], 'meta_key' => '' ];


		public function sanitize( $value, $size = 'thumbnail', $return_image_html = false ){
			return get_post( $value );
		}


		public function get_input(){

			if( !\hiweb\context::is_backend_page() ){
				\hiweb\console::debug_error( __( 'Can not display INPUT [IMAGE], it works only in the back-End' ) );
				return '';
			}
			\hiweb\css( HIWEB_DIR_VENDORS . '/fm.selectator.jquery/fm.selectator.jquery.css' );
			$js_id = \hiweb\js( HIWEB_DIR_VENDORS . '/fm.selectator.jquery/fm.selectator.jquery.js', [ 'jquery' ], true );
			\hiweb\js( HIWEB_DIR_JS . '/input-post.js', [ 'jquery', $js_id ], true );
			\hiweb\css( HIWEB_DIR_CSS . '/input-post.css' );

			$post_types = $this->attributes( 'post_type' );
			if( is_string( $post_types ) ) $post_types = [ $post_types ];
			$post_types_names = [];
			if( is_array( $post_types ) ) foreach( $post_types as $post_type ){
				if( post_type_exists( $post_type ) ){
					$post_types_names[ $post_type ] = get_post_type_object( $post_type )->label;
				} else {
					$post_types_names[ $post_type ] = 'неизвестный тип записи';
				}
			}

			ob_start();
			$this->attributes( 'multiple', 'multiple' );
			$this->attributes( 'data-selectator-keep-open', 'true' );
			///
			$wp_query = new WP_Query( [ 'post_type' => $post_types, 'posts_per_page' => 99 ] );
			///
			?>
			<div class="hw-input-post">
				<select name="<?= $this->field->backend()->label() ?>" <?= $this->get_tags_html() ?>>
					<option value="">&nbsp;</option>
					<?php if( $wp_query->have_posts() ){
						foreach( $wp_query->get_posts() as $P ){
							$selected = $this->value() == $P->ID;
							$img_src = get_the_post_thumbnail_url( $P, [ 80, 80 ] );
							if( $img_src == false ) $img_src = HIWEB_URL_CSS . '/img/noimg.png';
							$right = $P->ID;
							if( trim( $this->attributes( 'meta_key' ) ) != '' ){
								//hiweb()->console( get_ )
								$right = get_post_meta( $P->ID, $this->attributes( 'meta_key' ), true );
							}
							?>
							<option <?= $selected ? 'selected="selected"' : '' ?> value="<?= $P->ID ?>" data-subtitle="<?= \hiweb\arrays\get_byKey( $post_types_names, $P->post_type, 'неизвестный тип записи' ) ?>" data-left="<?= $img_src ?>" data-right="<?= $right ?>"><?= $P->post_title ?></option><?php
						}
					} ?>
				</select>
			</div>
			<?php
			return ob_get_clean();
		}
	}