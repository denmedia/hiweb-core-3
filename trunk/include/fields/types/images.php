<?php

	use hiweb\fields\field\type;


	\hiweb\fields\field\types::register( 'images', __NAMESPACE__ . '\images' );


	class images extends type{


		protected $dimension = 1;

		protected $attributes = [
			'width' => 150,
			'height' => 80,
			'cols' => 10
		];


		/**
		 * @return array
		 */
		public function sanitize( $value ){
			if( !is_array( $value ) ){
				return [];
			}
			return $value;
		}


		/**
		 * @return string
		 */
		private function get_col_width( $koof = 1 ){
			$cols = intval( $this->attributes( 'cols' ) );
			$cols = $cols < 1 ? 10 : $cols;
			return ceil( 100 / $cols / $koof ) . '%';
		}


		private function html_row( $img_id = null ){
			$img_src = false;
			$img_path = '';
			if( !is_null( $img_id ) ){
				$img_url = wp_get_attachment_image_src( $img_id, [
					$this->attributes( 'width' ),
					$this->attributes( 'height' )
				] );
				if( $img_url !== false ){
					$img_path = \hiweb\path\url_to_path( $img_url[0] );
				}
				if( trim( $img_path ) != '' && file_exists( $img_path ) && is_file( $img_path ) && is_readable( $img_path ) ){
					$img_src = $img_url[0];
				}
			}
			if( is_null( $img_id ) || ( is_string( $img_src ) && trim( $img_src ) != '' ) ){
				?>
				<li tabindex="0" <?= is_null( $img_id ) ? 'data-source' : 'data-image-id="' . $img_id . '"' ?> class="attachment" style="width: <?= $this->get_col_width() ?>;">
					<input type="hidden" value="<?= $img_id ?>" <?= is_null( $img_id ) ? 'data-' : '' ?>name="<?= $this->field->backend()->label() ?>[]"/>
					<div class="attachment-preview type-image subtype-png landscape">
						<div class="thumbnail">
							<div data-click-remove=""><i class="dashicons dashicons-dismiss"></i></div>
							<div class="centered">
								<img src="<?= $img_src ?>" alt="">
							</div>

						</div>

					</div>
				</li>
				<?php
			}
		}


		public function get_input(){
			if( !\hiweb\context::is_backend_page() ){
				\hiweb\console::debug_error( __( 'Input [IMAGES] can not be displayed, it only works in the back end' ) );
				return '';
			}
			wp_enqueue_media();
			\hiweb\js( HIWEB_DIR_JS . '/input_images.js', [ 'jquery-ui-sortable' ] );
			\hiweb\css( HIWEB_DIR_CSS . '/input_images.css' );
			////
			ob_start();
			?>
			<div class="postbox hw-input-images" <?= $this->get_tags_html() ?>>

				<div data-ctrl>
					<button><i class="dashicons dashicons-update" data-click-reverse></i></button>
					<button><i class="dashicons dashicons-marker" data-click-clear></i></button>
					<button><i class="dashicons dashicons-plus-alt" data-click-add></i></button>
				</div>

				<ul tabindex="-1" class="attachments">
					<li class="attachment" data-ctrl-sub="left" style="width: <?= $this->get_col_width() ?>;">
						<div class="attachment-preview">
							<div class="thumbnail">
								<i class="dashicons dashicons-plus-alt"></i>
							</div>
						</div>
					</li>
					<?php $this->html_row() ?>
					<div class="items">
						<?php
							foreach( $this->value() as $item ){
								$this->html_row( $item );
							}
						?>
					</div>
					<li class="attachment" data-ctrl-sub="right" style="width: <?= $this->get_col_width() ?>;">
						<div class="attachment-preview">
							<div class="thumbnail">
								<i class="dashicons dashicons-plus-alt"></i>
							</div>
						</div>
					</li>
				</ul>
				<div class="clear"></div>

			</div>
			<?php
			////
			return ob_get_clean();
		}

	}