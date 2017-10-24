<?php

	namespace {


		use hiweb\fields;
		use hiweb\path;


		add_action( 'wp_ajax_hiweb-field-repeat-get-row', function(){
			$field_global_id = path::request( 'id' );
			///
			$R = [ 'result' => false ];
			//
			if( !is_string( $field_global_id ) || trim( $field_global_id ) == '' ){
				$R['error'] = 'Не передан параметр id инпута. Необходимо указать $_POST[id] или $_GET[id].';
			} else {
				if( !fields::is_register( $field_global_id ) ){
					$R['error'] = 'Поле с id[' . $field_global_id . '] не найден!';
				} else {
					$R['result'] = true;
					/** @var fields\types\repeat $field */
					$field = fields::$fields[ $field_global_id ];
					$R['data'] = $field->ajax_html_row( path::request( 'input_name' ) );
				}
			}
			//
			echo json_encode( $R, JSON_UNESCAPED_UNICODE );
			die;
		} );

		if( !function_exists( 'add_field_repeat' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\repeat
			 */
			function add_field_repeat( $id ){
				$new_field = new hiweb\fields\types\repeat( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types {


		use hiweb\arrays;
		use hiweb\fields\field;


		class repeat extends field{

			private $cols = [];


			/**
			 * @param field $field
			 * @return col
			 */
			public function add_col_field( field $field ){
				$this->cols[ $field->id() ] = new col( $this, $field );
				return $this->cols[ $field->id() ];
			}


			/**
			 * @param null $value
			 * @return bool
			 */
			public function have_rows( $value = null ){
				return is_array( $value ) && count( $value ) > 0 && is_array( reset( $value ) ) && count( reset( $value ) ) > 0;
			}


			/**
			 * @return bool
			 */
			public function have_cols(){
				return ( is_array( $this->cols ) && count( $this->cols ) > 0 );
			}


			/**
			 * @return col[]
			 */
			public function get_cols(){
				return is_array( $this->cols ) ? $this->cols : [];
			}


			/**
			 * @param $value
			 * @return array
			 */
			public function value_sanitize( $value ){
				$R = [];
				if( $this->have_cols() && $this->have_rows( $value ) ){
					foreach( $value as $row_index => $row ){
						foreach( $this->get_cols() as $col_id => $col ){
							$R[ $row_index ][ $col_id ] = isset( $value[ $row_index ][ $col_id ] ) ? $value[ $row_index ][ $col_id ] : null;
						}
					}
				}
				return $R;
			}


			private function the_head_html( $thead = true ){
				force_ssl_admin()
				?>
				<?= $thead ? '<thead>' : '<tfoot>' ?>
				<tr>
					<th></th>
					<?php if( $this->have_cols() ){
						$width_full = 0;
						foreach( $this->get_cols() as $col ){
							$width_full += $col->width();
						}
						foreach( $this->get_cols() as $col ){
							$width = ceil( $col->width() / $width_full * 100 ) . '%';
							?>
							<th data-col="<?= $col->id() ?>" style="width:<?= $width ?>">
								<?= $col->label() . ( $col->description() != '' ? '<p class="description">' . $col->description() . '</p>' : '' ) ?>
							</th>
							<?php
						}
					} ?>
					<th class="nowrap" data-ctrl>
						<button class="dashicons dashicons-plus-alt" data-action-add="<?= $thead ?>" title="<?= $thead ? 'Add row to top' : 'Add row to bottom' ?>"></button>
						<button title="Clear all table rows..." class="dashicons dashicons-marker" data-action-clear=""></button>
					</th>
				</tr>
				<?= $thead ? '</thead>' : '</tfoot>' ?>
				<?php
			}


			private function the_row_html( $row_index = null, $row = [] ){
				?>
				<tr data-row="<?= $row_index ?>">
					<td data-drag>
						<i class="dashicons dashicons-sort"></i>
					</td>
					<?php if( $this->have_cols() ){
						foreach( $this->get_cols() as $col ){
							?>
							<td data-col="<?= $col->id() ?>">
								<?php echo $col->get_input_by_row( $row_index, $row ); ?>
							</td>
							<?php
						}
					} ?>
					<td data-ctrl>
						<button title="Duplicate row" class="dashicons dashicons-admin-page" data-action-duplicate="<?= $row_index ?>"></button>
						<button title="Remove row..." class="dashicons dashicons-trash" data-action-remove="<?= $row_index ?>"></button>
					</td>
				</tr>
				<?php
			}


			public function ajax_html_row( $input_name ){
				ob_start();
				$this->admin_input_set_attributes( 'name', $input_name );
				$this->the_row_html( 0 );
				return ob_get_clean();
			}


			/**
			 * @param null $value
			 * @param array $attributes
			 * @return string
			 */
			public function admin_get_input( $value = null, $attributes = [] ){
				\hiweb\css( HIWEB_DIR_CSS . '/field-repeat.css' );
				\hiweb\js( HIWEB_DIR_JS . '/field-repeat.js', [ 'jquery-ui-sortable' ] );
				///
				ob_start();
				?>
				<div class="hiweb-field-repeat" name="<?= $this->admin_input_get_attribute('name') ?>" data-input-name="<?= $this->admin_input_get_attribute('name') ?>" data-global-id="<?= $this->global_id() ?>">
					<?php if( !$this->have_cols() ){
						?><p class="empty-message"><?= sprintf( __( 'For repeat input [%s] not add col fields. For that do this: <code>$field->add_col(\'col-id\')</code>' ), $this->id() ) ?></p><?php
					} else {
						?>
						<table class="widefat striped"><?php
						$this->the_head_html( true );
						?>
						<tbody data-rows-list>
						<?php
							if( $this->have_rows( $value ) ){
								foreach( $this->value_sanitize( $value ) as $row_index => $row ){
									$this->the_row_html( $row_index, $row );
								}
							}
						?>
						</tbody>
						<tbody data-rows-message>
						<tr data-row-empty="<?= $this->have_rows( $value ) ? '1' : '0' ?>">
							<td colspan="<?= ( count( $this->get_cols() ) + 2 ) ?>"><p class="message"><?= 'Таблица пуста. Для добавления хотя бы одног поля, кликните по кнопке "+"' ?></p></td>
						</tr>
						</tbody>

						<?php
						$this->the_head_html( false );
						?></table><?php
					} ?>
				</div>
				<?php
				return ob_get_clean();
			}


		}


		class col{

			/** @var int */
			public $width = 1;
			/** @var string */
			public $label = '';
			/** @var string */
			public $description = '';
			/** @var field */
			public $parent_field;
			/** @var field */
			public $field;


			public function __construct( field $parent_field, field $field ){
				$this->field = $field;
				$this->parent_field = $parent_field;
				$this->label = $field->admin_label();
				$this->description = $field->admin_description();
			}


			public function id(){
				return $this->field->id();
			}


			/**
			 * @param null|string $set
			 * @return col|string
			 */
			public function label( $set = null ){
				if( is_null( $set ) ){
					return $this->label;
				} else {
					$this->label = $set;
					$this->field->admin_label( $set );
					return $this;
				}
			}


			/**
			 * @param null|string $set
			 * @return col|string
			 */
			public function description( $set = null ){
				if( is_null( $set ) ){
					return $this->description;
				} else {
					$this->description = $set;
					$this->field->admin_description( $set );
					return $this;
				}
			}


			/**
			 * @param null $set
			 * @return col|int
			 */
			public function width( $set = null ){
				if( is_null( $set ) ){
					return $this->width;
				} else {
					$this->width = $set;
					return $this;
				}
			}


			/**
			 * @param null|field $set
			 * @return col|field
			 */
			public function field( $set = null ){
				if( is_null( $set ) ){
					return $this->field;
				} else {
					$this->field = $set;
					return $this;
				}
			}


			public function get_input_by_row( $row_index, $row = [] ){
				$cell_value = null;
				if( !is_array( $row ) || ( !is_string( $row_index ) && !is_numeric( $row_index ) ) ){
					///
				} elseif( array_key_exists( $this->id(), $row ) ) {
					$cell_value = $row[ $this->id() ];
				} elseif( array_key_exists( $this->id(), array_values( $row ) ) ) {
					$cell_value = arrays::get_value_by_index( $row, $this->id() );
				}
				//
				$attributes = [
					'id' => $this->parent_field->admin_input_name( $this->id(), rand( 1000, 9999 ) ),
					'name' => $this->parent_field->admin_input_get_attribute( 'name' ) . '[' . $row_index . '][' . $this->id() . ']'
				];
				//
				return $this->field->admin_get_input( $cell_value, $attributes );
			}

		}
	}