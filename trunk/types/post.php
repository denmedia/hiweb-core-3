<?php

	namespace {


		if( !function_exists( 'add_field_post' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\post\field
			 */
			function add_field_post( $id ){
				$new_field = new hiweb\fields\types\post\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\post {


		class field extends \hiweb\fields\field{


			protected $post_type = [ 'post' ];
			protected $meta_key = '';


			/**
			 * @param null $set
			 * @return $this|null|array|string
			 */
			public function post_type( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function meta_key( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\css( HIWEB_DIR_VENDORS . '/fm.selectator.jquery/fm.selectator.jquery.css' );
				$js_id = \hiweb\js( HIWEB_DIR_VENDORS . '/fm.selectator.jquery/fm.selectator.jquery.js', [ 'jquery' ], true );
				\hiweb\js( HIWEB_DIR_JS . '/field-post.js', [ 'jquery', $js_id ], true );
				\hiweb\css( HIWEB_DIR_CSS . '/field-post.css' );

				$post_types = $this->get_parent_field()->post_type();
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
				//$this->admin_input_set_attributes( 'multiple', 'multiple' );
				//$this->admin_input_set_attributes( 'data-selectator-keep-open', 'true' );
				///
				$wp_query = new \WP_Query( [ 'post_type' => $post_types, 'posts_per_page' => 9999 ] );

				///
				?>
				<div class="hiweb-field-post">
					<select <?= $this->sanitize_attributes() ?>>
						<option value="">&nbsp;</option>
						<?php if( $wp_query->have_posts() ){
							foreach( $wp_query->get_posts() as $P ){
								$selected = $this->VALUE()->get() == $P->ID;
								$img_src = get_the_post_thumbnail_url( $P, [ 80, 80 ] );
								if( $img_src == false ) $img_src = HIWEB_URL_ASSETS . '/img/noimg.png';
								$right = $P->ID;
								if( trim( $this->get_parent_field()->meta_key() ) != '' ){
									$right = get_post_meta( $P->ID, $this->get_parent_field()->meta_key(), true );
								}
								?>
								<option <?= $selected ? 'selected="selected"' : '' ?> value="<?= $P->ID ?>" data-subtitle="<?= \hiweb\arrays::get_value_by_key( $post_types_names, $P->post_type, 'неизвестный тип записи' ) ?>" data-left="<?= $img_src ?>" data-right="<?= $right ?>"><?= $P->post_title ?></option><?php
							}
						} ?>
					</select>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}