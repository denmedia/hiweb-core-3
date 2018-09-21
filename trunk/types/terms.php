<?php

	namespace {


		if( !function_exists( 'add_field_terms' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\terms\field
			 */
			function add_field_terms( $id ){
				$new_field = new hiweb\fields\types\terms\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\terms {


		use hiweb\arrays;
		use hiweb\fields\value;


		class field extends \hiweb\fields\field{

			protected $placeholder = '';


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function taxonomy( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
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
			public function hide_empty( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public $taxonomy = [ 'category' ];
			public $hide_empty = false;


			public function __construct( \hiweb\fields\field $field, value $value ){
				parent::__construct( $field, $value );
				$this->attributes['multiple'] = 'multiple';
				$this->attributes['placeholder'] = 'Выберите категорию';
				$this->attributes['no_results_text'] = 'Ничего не найдено';
			}


			/**
			 * @return array
			 */
			private function get_terms_by_taxonomy(){
				$terms_by_taxonomy = [];
				$taxonomies = $this->taxonomy;
				if( is_array( $taxonomies ) ){
					foreach( $taxonomies as $taxonomy ){
						if( !taxonomy_exists( $taxonomy ) ) continue;
						$args = [
							'taxonomy' => $taxonomy,
							'hide_empty' => $this->hide_empty
						];
						$terms = get_terms( $args );
						if( is_array( $terms ) ) $terms_by_taxonomy[ $taxonomy ] = $terms;
					}
				}
				return $terms_by_taxonomy;
			}


			/**
			 * @param \WP_Term[] $wp_terms
			 * @param null       $terms_level
			 */
			private function get_html_options_from_terms( $wp_terms, $terms_level = null ){
				/** @var \WP_Term $wp_term */
				foreach( $wp_terms as $wp_term ){
					$selected = is_array( $this->VALUE()->get() ) ? @in_array( $wp_term->term_id, $this->VALUE()->get() ) : ( $wp_term->term_id == $this->VALUE()->get() );
					$title = $wp_term->name . ( $wp_term->count > 0 ? ' (' . $wp_term->count . ')' : '' );
					if(is_array($terms_level) && array_key_exists($wp_term->term_id, $terms_level)){
						$title = implode('', array_fill(0, intval($terms_level[$wp_term->term_id]),' &nbsp; ')).$title;
					}
					?>
					<option <?= $selected ? 'selected' : '' ?> value="<?= $wp_term->term_taxonomy_id ?>"><?= $title ?></option>
					<?php
				}
			}


			public function html(){
				\hiweb\css( HIWEB_DIR_CSS . '/field-terms.css' );
				\hiweb\js( HIWEB_DIR_JS . '/field-terms.js', [ 'jquery' ] );
				ob_start();
				$terms_by_taxonomy = $this->get_terms_by_taxonomy();
				?>
				<div class="hiweb-field-terms">
					<select class="ui fluid search dropdown" name="<?= $this->name() ?>[]" <?= $this->sanitize_attributes() ?>>
						<?php

							foreach( $terms_by_taxonomy as $taxonomy_name => $terms ){
								if( !is_array( $terms ) ) continue;
								$taxonomy = get_taxonomy( $taxonomy_name );
								if( $taxonomy instanceof \WP_Taxonomy ){
									?>
									<optgroup label="<?= $taxonomy->label ?>">
										<?php
											if( $taxonomy->hierarchical ){
												$terms_level = [];
												/** @var \WP_Term $wp_term */
												foreach( $terms as $wp_term ){
													$terms_level[ $wp_term->term_id ] = $wp_term->parent == 0 ? 0 : ( $terms_level[ $wp_term->parent ] + 1 );
												}
												self::get_html_options_from_terms( $terms, $terms_level );
											} else {
												self::get_html_options_from_terms( $terms );
											}
										?>
									</optgroup>
									<?php
								}
							}
						?>
					</select>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}