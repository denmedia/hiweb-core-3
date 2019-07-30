<?php

	namespace {


		use hiweb\fields\types\post\field;


		if( !function_exists( 'add_field_post' ) ){
			/**
			 * @param $id
			 * @return field
			 */
			function add_field_post( $id ){
				$new_field = new hiweb\fields\types\post\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\post {


		use hiweb\fields;
		use hiweb\fields\value;
		use WP_Post;
		use WP_Query;


		class field extends \hiweb\fields\field{


			protected $post_type = [ 'page' ];
			protected $placeholder = '';
			protected $meta_key = '';
			protected $multiple = false;
			protected $max_items = 99;


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

					//					add_filter( 'posts_where', function( $where, &$wp_query ){
					//						global $wpdb;
					//						if( $wpse18703_title = $_POST['search'] ){
					//							$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $wpse18703_title ) ) . '%\'';
					//						}
					//						return $where;
					//					}, 10, 2 );
					$field = fields::$field_by_globalId[ $_POST['global_id'] ][0];
					$post_types = $field->post_type();
					$query = [
						'post_type' => $post_types,
						//'wpse18703_title' => $_POST['search'],
						'posts_per_page' => 99,
						'post_status' => 'any',
						's' => $_POST['search'],
						'orderby' => 'title',
						'order' => 'ASC'
					];
					$wp_query = new WP_Query( $query );
					$R = [];
					//					$post_types_names = [];
					//					if( is_array( $post_types ) ) foreach( $post_types as $post_type ){
					//						if( post_type_exists( $post_type ) ){
					//							$post_types_names[ $post_type ] = get_post_type_object( $post_type )->label;
					//						} else {
					//							$post_types_names[ $post_type ] = 'неизвестный тип записи';
					//						}
					//					}
					/** @var WP_Post $wp_post */
					foreach( $wp_query->get_posts() as $wp_post ){
						$R[] = [
							'value' => $wp_post->ID,
							'title' => $wp_post->post_title,
							//'name' => '<img src="' . get_image( get_post_thumbnail_id( $wp_post ) )->get_src( 'thumbnail' ) . '">' . $wp_post->post_title
						];
					}

					//wp_send_json_success( $R );
					echo json_encode( [
						'success' => true,
						'items' => $R
					] );
					die;
				} );
			}


			public function html(){
				include_css( HIWEB_DIR_VENDORS . '/selectize.js/css/selectize.css' );
				include_css( HIWEB_DIR_ASSETS . '/css/field-post.css' );
				$handler = include_js( HIWEB_DIR_VENDORS . '/selectize.js/js/standalone/selectize.min.js', [ 'jquery' ] );
				include_js( HIWEB_DIR_ASSETS . '/js/field-post.min.js', [ $handler ] );

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

				$selected = [];
				if(is_array($this->VALUE()->get()) && count($this->VALUE()->get()) > 0) {
					///
					$wp_query = new \WP_Query( [
						'post_type' => $post_types,
						'posts_per_page' => 20,
						'post_status' => 'any',
						'post__in' => $this->VALUE()->get()
					] );
					foreach( $wp_query->get_posts() as $post ){
						$selected[$post->ID] = $post->post_title;
					}
				}
				///
				$this->attributes['data-global-id'] = $this->get_parent_field()->global_id();
				if( $this->get_parent_field()->multiple( null ) ){
					$this->attributes['multiple'] = 'multiple';
					$this->attributes['size'] = 1;
					if( $this->attributes['name'] != '' ) $this->attributes['name'] .= '[]';
				}
				ob_start();
				?>
				<div class="hiweb-field-post">
					<select <?= get_array( $this->attributes )->get_param_html_tags() ?> data-oprions="<?= htmlentities( json_encode( [ 'post_type' => $this->get_parent_field()->post_type() ] ) ) ?>" data-value="<?= htmlentities( json_encode( $this->VALUE()->get() ) ) ?>">
						<?php
							foreach($selected as $val => $title) {
								?>
								<option value="<?=$val?>" selected><?=$title?></option>
								<?php
							}
						?>
					</select>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}