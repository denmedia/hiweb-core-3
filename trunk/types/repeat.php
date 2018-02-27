<?php

	namespace {


		use hiweb\fields;
		use hiweb\path;


		add_action( 'wp_ajax_hiweb-field-repeat-get-row', function(){
			$field_global_id = path::request( 'id' );
			///
			$R = [ 'result' => false, 'filed-id' => $field_global_id ];
			//
			if( !is_string( $field_global_id ) || trim( $field_global_id ) == '' ){
				$R['error'] = 'Не передан параметр id инпута. Необходимо указать $_POST[id] или $_GET[id].';
			} else {
				if( !fields::is_register( $field_global_id ) ){
					$R['error'] = 'Поле с id[' . $field_global_id . '] не найден!';
				} else {
					$R['result'] = true;
					/** @var fields\types\repeat\field $field */
					$field = fields::$fields[ $field_global_id ];
					/** @var fields\types\repeat\input $input */
					$input = $field->INPUT();
					$R['data'] = $input->ajax_html_row( path::request( 'input_name' ) );
				}
			}
			//
			echo json_encode( $R, JSON_UNESCAPED_UNICODE );
			die;
		} );

		if( !function_exists( 'add_field_repeat' ) ){
			/**
			 * @param $id
			 * @return fields\types\repeat\field
			 */
			function add_field_repeat( $id ){
				$new_field = new hiweb\fields\types\repeat\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\repeat {


		use function hiweb\css;
		use function hiweb\dump;
		use function hiweb\js;


		class field extends \hiweb\fields\field{


			/**
			 * @param \hiweb\fields\field $field
			 * @return col
			 */
			public function add_col_field( \hiweb\fields\field $field ){
				/** @var input $input */
				$input = $this->INPUT();
				return $input->add_col_field( $field );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			/** @var col[] */
			public $cols = [];
			/** @var row[] */
			public $repeat_rows = [];


			/**
			 * @param \hiweb\fields\field $field
			 * @return col
			 */
			public function add_col_field( \hiweb\fields\field $field ){
				$new_col = new col( $this, $field );
				$this->cols[ $field->id() ] = $new_col;
				return $new_col;
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


			private function the_head_html( $thead = true ){
				?>
				<?= $thead ? '<thead>' : '<tfoot>' ?>
				<tr>
					<th></th>
					<?php if( $this->have_cols() ){
						$width_full = 0;
						$last_col = null;
						$last_compact = false;
						foreach( $this->get_cols() as $col ){
							$width_full += $col->width();
							if( !$last_compact ){
								$last_col = $col;
							} elseif( $last_col instanceof col ) {
								$last_col->width += $col->width();
							}
							$last_compact = $col->compact();
						}
						$last_compact = false;
						foreach( $this->get_cols() as $col ){
							$width = ceil( $col->width() / $width_full * 100 ) . '%';
							?>
							<th data-col="<?= $col->id() ?>" style="width:<?= $width ?>" class="<?= $last_compact ? 'compact' : '' ?>">
								<?= $col->label() . ( $col->description() != '' ? '<p class="description">' . $col->description() . '</p>' : '' ) ?>
							</th>
							<?php
							$last_compact = $col->compact();
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


			/**
			 * @param $input_name
			 * @return string
			 */
			public function ajax_html_row( $input_name ){
				$this->name( $input_name );
				$new_row = new row( $this );
				$new_row->index = 99;
				ob_start();
				$new_row->the();
				return ob_get_clean();
			}


			public function html(){
				if( $this->have_cols() ) foreach( $this->cols as $col ){
					$col->input->html();
				}
				ob_start();
				css( HIWEB_URL_CSS . '/field-repeat.css' );
				js( HIWEB_URL_JS . '/field-repeat.js' );
				?>
				<div class="hiweb-field-repeat" name="<?= $this->name() ?>" data-input-name="<?= $this->name() ?>" data-global-id="<?= $this->get_parent_field()->global_id() ?>">
					<?php if( !$this->have_cols() ){
						?><p class="empty-message"><?= sprintf( __( 'For repeat input [%s] not add col fields. For that do this: <code>$field->add_col_field( add_field_text(...) )</code>' ), $this->get_parent_field()->id() ) ?></p><?php
					} else {
						?>
						<table class="widefat striped"><?php
						$this->the_head_html( true );
						?>
						<tbody data-rows-list>
						<?php
							if( $this->VALUE()->rows()->have_rows() ) foreach( $this->VALUE()->get_sanitized() as $row_index => $row ){
								$repeat_row = new row( $this, $row, $row_index );
								$repeat_row->index = $row_index;
								$repeat_row->the();
							}
						?>
						</tbody>
						<tbody data-rows-message>
						<tr data-row-empty="<?= $this->VALUE()->rows()->have_rows() ? '1' : '0' ?>">
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


		class value extends \hiweb\fields\value{

			/**
			 * @param $value
			 * @return array
			 */
			public function get_value_sanitize( $value ){
				$R = [];
				/** @var input $input */
				$input = $this->get_parent_field()->INPUT();
				if( $input->have_cols() && $this->rows()->have_rows() ){
					foreach( $value as $row_index => $row ){
						foreach( $input->get_cols() as $col_id => $col ){
							$R[ $row_index ][ $col_id ] = isset( $value[ $row_index ][ $col_id ] ) ? $value[ $row_index ][ $col_id ] : null;
						}
					}
				}
				return $R;
			}

			//TODO!
			//			public function get_content(){
			//				list( $col_id, $row_index ) = func_get_arg( 4 );
			//				if( array_key_exists( $col_id, $this->cols ) ){
			//					return $this->cols[ $col_id ]->field()->value()->get_content();
			//				}
			//				return $this->get_sanitized();
			//			}
		}
	}

	namespace hiweb\fields\types\repeat {


		class row{

			/** @var input */
			public $parent_input;

			public $index = 0;
			/** @var col[] */
			public $cols = [];

			public $have_cols = false;

			public $data = [];


			public function __construct( input $input, $data = [], $row_index = 0 ){
				$this->parent_input = $input;
				foreach( $input->get_cols() as $id => $col ){
					$this->have_cols = true;
					$this->cols[ $id ] = clone $col;
					$this->cols[ $id ]->set_row( $this );
					$this->cols[ $id ]->input->name( $this->parent_input->name() . '[' . $row_index . '][' . $id . ']' );
					if( isset( $data[ $id ] ) ) $this->cols[ $id ]->value->set( $data[ $id ] );
				}
			}


			public function the(){
				?>
				<tr data-row="<?= $this->index ?>">
					<td data-drag>
						<i class="dashicons dashicons-sort"></i>
					</td>
					<?php if( $this->have_cols ){
						$last_compact = false;
						foreach( $this->cols as $col ){
							?>
							<td data-col="<?= $col->id() ?>" class="<?= ( $col->compact() || $last_compact ) ? 'compact' : '' ?>"><?php $col->the(); ?></td>
							<?php
							$last_compact = $col->compact();
						}
					} ?>
					<td data-ctrl>
						<button title="Duplicate row" class="dashicons dashicons-admin-page" data-action-duplicate="<?= $this->index ?>"></button>
						<button title="Remove row..." class="dashicons dashicons-trash" data-action-remove="<?= $this->index ?>"></button>
					</td>
				</tr>
				<?php

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
			public $embedded_field;
			/** @var field */
			public $parent_input;
			/** @var \hiweb\fields\input */
			public $input;
			/** @var \hiweb\fields\value */
			public $value;
			/** @var row|null */
			private $parent_repeat_row = null;
			/** @var bool */
			private $is_compact = false;


			public function __construct( input $parent_input, \hiweb\fields\field $embedded_field, $parent_repeat_row = null ){
				$this->parent_input = $parent_input;
				$this->embedded_field = $embedded_field;
				$this->label = $embedded_field->label();
				$this->description = $embedded_field->description();
				$this->parent_repeat_row = $parent_repeat_row;
				$this->input = clone $this->embedded_field->INPUT();
				$this->value = $this->input->VALUE();
			}


			public function __clone(){
				$this->input = clone $this->input;
				$this->value = $this->input->VALUE();
			}


			/**
			 * @return string
			 */
			public function id(){
				return $this->embedded_field->id();
			}


			/**
			 * @param row $parent_repeat_row
			 */
			public function set_row( row $parent_repeat_row ){
				$this->parent_repeat_row = $parent_repeat_row;
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
					//$this->parent_input->label( $set );
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
					//$this->parent_input->description( $set );
					return $this;
				}
			}


			public function compact( $set = null ){
				if( is_null( $set ) ){
					return $this->is_compact;
				} else {
					$this->is_compact = true;
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
			 * @return col|field
			 */
			public function get_embedded_field(){
				return $this->embedded_field;
			}


			/**
			 * Echo the input
			 */
			public function the(){
				echo $this->input->html();
			}

		}
	}