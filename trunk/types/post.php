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


		use function hiweb\css;
		use hiweb\fields\value;
		use hiweb\images;
		use function hiweb\js;


		class field extends \hiweb\fields\field{


			protected $post_type = [ 'page' ];
			protected $placeholder = '';
			protected $meta_key = '';
			protected $multiple = false;


			/**
			 * @param null $set
			 * @return $this|null|array|string
			 */
			public function post_type( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|string
			 */
			public function placeholder( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function meta_key( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			public function multiple( $set = true ){
				return $this->set_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public function __construct( \hiweb\fields\field $field, value $value ){
				parent::__construct( $field, $value );
				add_action( 'wp_ajax_hiweb-type-post', function(){

					add_filter( 'posts_where', function( $where, &$wp_query ){
						global $wpdb;
						if( $wpse18703_title = $_POST['search'] ){
							$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $wpse18703_title ) ) . '%\'';
						}
						return $where;
					}, 10, 2 );

					$post_types = $this->get_parent_field()->post_type();
					$wp_query = new \WP_Query( [
						'post_type' => $post_types,
						'posts_per_page' => 99,
						'post_status' => 'publish',
						's' => $_POST['search'],
						'orderby' => 'title',
						'order' => 'ASC'
					] );
					$R = [];
					$post_types_names = [];
					if( is_array( $post_types ) ) foreach( $post_types as $post_type ){
						if( post_type_exists( $post_type ) ){
							$post_types_names[ $post_type ] = get_post_type_object( $post_type )->label;
						} else {
							$post_types_names[ $post_type ] = 'неизвестный тип записи';
						}
					}
					/** @var \WP_Post $wp_post */
					foreach( $wp_query->get_posts() as $wp_post ){
						$R[] = [
							'value' => $wp_post->ID,
							//'text' => '<img src="' . get_image( get_post_thumbnail_id( $wp_post ) )->get_src( 'thumbnail' ) . '">' . $wp_post->post_title,
							'name' => '<img src="' . get_image( get_post_thumbnail_id( $wp_post ) )->get_src( 'thumbnail' ) . '">' . $wp_post->post_title
						];
					}

					//wp_send_json_success( $R );
					echo json_encode( [
						'success' => true,
						'results' => $R
					] );
					die;
				} );
			}


			public function html(){
				//\hiweb\css( HIWEB_DIR_VENDORS . '/fm.selectator.jquery/fm.selectator.jquery.css' );
				\hiweb\css( HIWEB_DIR_CSS . '/field-post.css' );
				//$js_id = \hiweb\js( HIWEB_DIR_VENDORS . '/fm.selectator.jquery/fm.selectator.jquery.js', [ 'jquery' ], true );
				\hiweb\js( HIWEB_DIR_JS . '/field-post.js', [ 'jquery'], true );

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
				///
				$wp_query = new \WP_Query( [ 'post_type' => $post_types, 'posts_per_page' => 20 ] );

				///
				$this->attributes['data-global-id'] = $this->get_parent_field()->global_id();
				if( $this->get_parent_field()->multiple( null ) ){
					$this->attributes['multiple'] = 'multiple';
					$this->attributes['size'] = 1;
				}
				?>
				<div class="hiweb-field-post">
					<div class="ui fluid search selection dropdown">
						<input type="hidden" name="<?= $this->name() ?>" value="<?= intval( $this->VALUE()->get() ) ?>">
						<i class="dropdown icon"></i>
						<div class="default text"><?= $this->get_parent_field()->placeholder() ?></div>
						<div class="menu">
							<?php
								if( $wp_query->post_count > 0 ){
									/** @var \WP_Post $WP_POST */
									$selected_ids = is_array( $this->VALUE()->get() ) ? $this->VALUE()->get() : [ $this->VALUE()->get() ];
									$prepen_post_ids = $selected_ids;
									foreach( $wp_query->get_posts() as $WP_POST ){
										if( \hiweb\arrays::in_array( $WP_POST->ID, $selected_ids ) ){
											\hiweb\arrays::unset_by_value( $prepen_post_ids, $WP_POST->ID );
										}
									}
									$wp_query->set( 'post__not_in', $prepen_post_ids );
									foreach( $prepen_post_ids as $id ){
										$WP_POST = get_post( $id );
										if( $WP_POST instanceof \WP_Post && \hiweb\arrays::in_array( $WP_POST->post_type, $post_types ) ){
											$thumbnail_src = get_image( get_post_thumbnail_id( $WP_POST ) )->get_src( 'thumbnail' );
											?>
											<div class="item" data-value="<?= $WP_POST->ID ?>"><img src="<?= $thumbnail_src ?>"><?= $WP_POST->post_title ?></div>
											<!--<option selected data-left="<?= $thumbnail_src ?>" data-right="" data-subtitle="<?= $post_types_names[ $WP_POST->post_type ] ?> от <?= get_the_date( '', $WP_POST ) ?>" value="<?= $WP_POST->ID ?>"><?= $WP_POST->post_title ?></option>-->
											<?php
										}
									}
									foreach( $wp_query->get_posts() as $WP_POST ){
										$thumbnail_src = get_image( get_post_thumbnail_id( $WP_POST ) )->get_src( 'thumbnail' );
										$selected = \hiweb\arrays::in_array( $WP_POST->ID, $selected_ids );
										?>
										<div class="item" data-value="<?= $WP_POST->ID ?>"><img src="<?= $thumbnail_src ?>"><?= $WP_POST->post_title ?></div>
										<!--<option <?= $selected ? 'selected' : '' ?> data-left="<?= $thumbnail_src ?>" data-right="" data-subtitle="<?= $post_types_names[ $WP_POST->post_type ] ?> от <?= get_the_date( '', $WP_POST ) ?>" value="<?= $WP_POST->ID ?>"><?= $WP_POST->post_title ?></option>-->
										<?php
									}
								} ?>
						</div>
					</div>
					<!--<select <?= $this->sanitize_attributes() ?>>
						<option value="">--выберите--</option>
						<?php
						if( $wp_query->post_count > 0 ){
							/** @var \WP_Post $WP_POST */
							$selected_ids = is_array( $this->VALUE()->get() ) ? $this->VALUE()->get() : [ $this->VALUE()->get() ];
							$prepen_post_ids = $selected_ids;
							foreach( $wp_query->get_posts() as $WP_POST ){
								if( \hiweb\arrays::in_array( $WP_POST->ID, $selected_ids ) ){
									\hiweb\arrays::unset_by_value( $prepen_post_ids, $WP_POST->ID );
								}
							}
							foreach( $prepen_post_ids as $id ){
								$WP_POST = get_post( $id );
								if( $WP_POST instanceof \WP_Post && \hiweb\arrays::in_array( $WP_POST->post_type, $post_types ) ){
									$thumbnail_src = get_image( get_post_thumbnail_id( $WP_POST ) )->get_similar_src( 20, 20 );
									?>
										<option selected data-left="<?= $thumbnail_src ?>" data-right="" data-subtitle="<?= $post_types_names[ $WP_POST->post_type ] ?> от <?= get_the_date( '', $WP_POST ) ?>" value="<?= $WP_POST->ID ?>"><?= $WP_POST->post_title ?></option>
										<?php
								}
							}
							foreach( $wp_query->get_posts() as $WP_POST ){
								$thumbnail_src = get_image( get_post_thumbnail_id( $WP_POST ) )->get_similar_src( 20, 20 );
								$selected = \hiweb\arrays::in_array( $WP_POST->ID, $selected_ids );
								?>
									<option <?= $selected ? 'selected' : '' ?> data-left="<?= $thumbnail_src ?>" data-right="" data-subtitle="<?= $post_types_names[ $WP_POST->post_type ] ?> от <?= get_the_date( '', $WP_POST ) ?>" value="<?= $WP_POST->ID ?>"><?= $WP_POST->post_title ?></option>
									<?php
							}
						} ?>
					</select>-->
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}