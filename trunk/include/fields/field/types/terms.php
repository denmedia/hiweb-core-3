<?php

	namespace {


		if( !function_exists( 'add_field_terms' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\terms
			 */
			function add_field_terms( $id ){
				$new_field = new hiweb\fields\types\terms( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use function hiweb\dump;
		use hiweb\fields\field;


		class terms extends field{

			protected $taxonomy = [ 'category' ];
			protected $hide_empty = false;


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function taxonomy( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function hide_empty( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			public function __construct( $id = null ){
				parent::__construct( $id );
				$this->admin_input_set_attributes( 'multiple', 'multiple' );
				$this->admin_input_set_attributes( 'placeholder', 'Выберите категорию' );
				$this->admin_input_set_attributes( 'no_results_text', 'Ничего не найдено' );
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


			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_DIR_CSS . '/field-terms.css' );
				\hiweb\js( HIWEB_DIR_JS . '/field-terms.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_VENDORS . '/chosen/chosen.min.css' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/chosen/chosen.jquery.min.js', [ 'jquery' ] );
				ob_start();
				$terms_by_taxonomy = $this->get_terms_by_taxonomy();
				?>
				<div class="hw-input-terms">
					<select class="hw-input-terms-select" name="<?= $this->admin_input_name() ?>[]" <?= $this->admin_get_input_attributes_html() ?>>
						<?php

							foreach( $terms_by_taxonomy as $taxonomy_name => $terms ){
								if( !is_array( $terms ) ) continue;
								$taxonomy = get_taxonomy( $taxonomy_name );
								if( $taxonomy instanceof \WP_Taxonomy ){
									?>
									<optgroup label="<?= $taxonomy->label ?>">
										<?php
											/** @var \WP_Term $wp_term */
											foreach( $terms as $wp_term ){
												$selected = is_array( $value ) ? in_array( $wp_term->term_id, $value ) : ( $wp_term->term_id == $value );
												?>
												<option <?= $selected ? 'selected' : '' ?> value="<?= $wp_term->term_taxonomy_id ?>"><?= $wp_term->name ?></option>
												<?php
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