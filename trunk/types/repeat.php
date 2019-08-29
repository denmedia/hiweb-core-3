<?php

	namespace {


		use hiweb\fields;


		add_action( 'wp_ajax_hiweb-field-repeat-get-row', function(){
			$field_global_id = \hiweb\urls::request( 'id' );
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
					$R['data'] = $input->ajax_html_row( \hiweb\urls::request( 'input_name' ), \hiweb\urls::request( 'index' , 0), \hiweb\urls::request( 'values' ) );
					$R['values'] = $input->ajax_filter_values( \hiweb\urls::request( 'values' ) );
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


		use hiweb\arrays;
		use hiweb\hidden_methods;
		use function hiweb\css;
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


			/**
			 * @param                     $group_name
			 * @param \hiweb\fields\field $field
			 * @return col
			 */
			public function add_col_flex_field( $group_name, \hiweb\fields\field $field ){
				/** @var input $input */
				$input = $this->INPUT();

				return $input->add_col_flex_field( $group_name, $field );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			/** @var col[][] */
			public $cols = [];
			/** @var row[] */
			public $repeat_rows = [];


			/**
			 * @param \hiweb\fields\field $field
			 * @return col
			 */
			public function add_col_field( \hiweb\fields\field $field ){
				return $this->add_col_flex_field( '', $field );
			}


			/**
			 * @param                     $group_name
			 * @param \hiweb\fields\field $field
			 * @return col
			 */
			public function add_col_flex_field( $group_name, \hiweb\fields\field $field ){
				$new_col = new col( $this, $field );
				$this->cols[ $group_name ][ $field->id() ] = $new_col;

				return $new_col;
			}


			/**
			 * @param string $group - cols group
			 * @return bool
			 */
			public function have_cols( $group = null ){
				if( !is_string( $group ) ){
					return !arrays::is_empty( $this->cols );
				} else {
					return ( isset( $this->cols[ $group ] ) && is_array( $this->cols[ $group ] ) && count( $this->cols[ $group ] ) > 0 );
				}
			}


			/**
			 * @return bool
			 */
			public function have_flex_rows(){
				return ( count( $this->cols ) > 1 || ( count( $this->cols ) == 1 && !isset( $this->cols[''] ) ) );
			}


			/**
			 * @param string $group
			 * @return col[]
			 */
			public function get_cols( $group = '' ){
				return ( isset( $this->cols[ $group ] ) && is_array( $this->cols[ $group ] ) ) ? $this->cols[ $group ] : [];
			}


			/**
			 * @return string[]
			 */
			public function get_flex_names(){
				$R = [];
				if( $this->have_flex_rows() ){
					return array_keys( $this->cols );
				}

				return $R;
			}


			private function the_head_html( $thead = true, $handle_title = '&nbsp;' ){
				?>
				<?= $thead ? '<thead class="ui segment">' : '<tfoot class="ui segment">' ?>
				<tr>
					<th class="collapsing"></th>
					<?php
						if( $this->have_flex_rows() ){
							?>
							<th class="flex-column"><?= $handle_title ?></th><?php
						} else {
							if( $this->have_cols() ){
								///COMPACT GROUP
								$compacted_cols = [];
								$index = 0;
								foreach( $this->get_cols() as $col_id => $col ){
									$compacted_cols[ $index ][] = $col;
									if( !$col->compact() ){
										$index ++;
									}
								}
								///
								$width_cols = [];
								foreach( $compacted_cols as $index => $cols ){
									/** @var col $col */
									foreach( $cols as $subindex => $col ){
										if( !isset( $width_cols[ $index ] ) || ( isset( $width_cols[ $index ] ) && $col->width() > $width_cols[ $index ] ) ){
											$width_cols[ $index ] = $col->width();
										}
									}
								}
								///
								/** @var col[] $cols */
								foreach( $compacted_cols as $index => $cols ){
									$width = ceil( $width_cols[ $index ] / array_sum( $width_cols ) * 100 ) . '%';
									?>
									<th <?= count( $cols ) > 1 ? '' : 'data-col="' . $cols[0]->id() . '"' ?>
											style="width:<?= $width ?>" class="<?= count( $cols ) > 1 ? 'compacted' : '' ?>">
										<?= $cols[0]->label() . ( $cols[0]->description() != '' ? '<p class="description">' . $cols[0]->description() . '</p>' : '' ) ?>
									</th>
									<?php
								}
							}

							?>

							<?php

						} ?>
					<th class="nowrap collapsing" data-ctrl>
						<?php
							if( $this->have_flex_rows() ){

								?>
								<div class="ui icon mini buttons">
									<div class="ui top right pointing dropdown button">
										<i class="ellipsis vertical icon"></i>
										<div class="menu">
											<div class="item"><i class="dropdown icon"></i>
												<span class="text">Добавить</span>
												<div class="menu">
													<?php
														foreach( $this->get_flex_names() as $name ){
															?>
															<div class="item" data-flex-id="<?= $name ?>" data-action-add="<?= $thead ?>"><i class="plus icon"></i> <?= $name == '' ? 'Строка' : $name ?>
															</div>
															<?php
														}
													?>
												</div>
											</div>
											<div class="item" data-action-clear=""><i class="circle outline icon"></i>Очистить
																													  все
											</div>
										</div>
									</div>
								</div>
								<!--<button class="dashicons dashicons-plus-alt" data-action-add="flex-dropdown" title="<?= $thead ? 'Add flex row to top' : 'Add flex row to bottom' ?>"></button>-->
								<!--<div data-sub-flex-dropdown>
									<?php
									foreach( $this->get_flex_names() as $name ){
										?>
											<div>
												<a href="#" data-flex-id="<?= $name ?>" data-action-add="<?= $thead ?>" title="Добавить новый блок '<?= htmlentities( $name, ENT_QUOTES, 'UTF-8' ) ?>'"><?= $name ?></a>
											</div>
											<?php
									}
								?>
								</div>-->
								<?php
							} else {
								?>
								<!--<button class="dashicons dashicons-plus-alt" data-action-add="<?= $thead ?>" title="<?= $thead ? 'Add row to top' : 'Add row to bottom' ?>"></button>-->
								<div class="ui icon mini buttons">
									<a class="ui top right pointing dropdown basic button">
										<i class="ellipsis vertical icon"></i>
										<div class="menu">
											<div class="item" data-action-add="<?= $thead ?>"><i class="plus icon"></i>Добавить
											</div>
											<div class="item" data-action-clear=""><i class="circle outline icon"></i>Очистить
																													  все
											</div>
										</div>
									</a>
								</div>
								<?php
							}
						?>
						<!--<button title="Clear all table rows..." class="dashicons dashicons-marker" data-action-clear=""></button>-->
					</th>
				</tr>
				<?= $thead ? '</thead>' : '</tfoot>' ?>
				<?php
			}


			/**
			 * Merge parameters to once array
			 * @param     $array
			 * @param int $level
			 * @return array
			 */
			public function ajax_filter_values( $array, $level = null ){
				$R = [];
				$first = false;
				if( is_null( $level ) ){
					$first = true;
					$level = 20;
				}
				foreach( $array as $key => $val ){
					if( is_array( $val ) ){
						if( $level > 1 ){
							$R[ $key ] = $this->ajax_filter_values( $val, $level -- );
						} else {
							$R[ $key ] = json_encode( $val );
						}
					} else {
						$R[ $key ] = $val;
					}
				}
				return $R;
			}


			/**
			 * @param        $input_name
			 * @param int    $row_index
			 * @param null   $values
			 * @return string
			 */
			public function ajax_html_row( $input_name, $row_index = 0, $values = null ){
				$this->name( $input_name );
				if( is_array( $values ) ){
					$new_row = new row( $this, $this->ajax_filter_values( $values ), $row_index );
				} else {
					$new_row = new row( $this, [ '_flex_row_id' => isset( $_POST['flex_row_id'] ) ? $_POST['flex_row_id'] : '' ], $row_index );
				}
				//$new_row->index = intval( $row_index );
				ob_start();
				$new_row->the();

				return ob_get_clean();
			}


			public function html(){
				if( $this->have_cols() ){
					foreach( $this->cols as $flex_id => $flex_cols ){
						if( is_array( $flex_cols ) ){
							foreach( $flex_cols as $col ){
								$col->input()->html();
							}
						}
					}
				}
				ob_start();
				css( HIWEB_URL_CSS . '/field-repeat.css' );
				wp_enqueue_script('jquery-ui-sortable');
				js( HIWEB_URL_JS . '/field-repeat.js', [js( HIWEB_URL_JS . '/deepMerge.min.js', ['jquery-ui-sortable']),'jquery-ui-sortable'] );
				?>
				<div class="hiweb-field-repeat" name="<?= $this->name() ?>" data-input-name="<?= $this->name() ?>"
					 data-global-id="<?= $this->get_parent_field()->global_id() ?>"
					 data-id="<?= $this->get_parent_field()->id() ?>"
					 data-flex="<?= $this->have_flex_rows() ? '1' : '0' ?>">
					<?php if( !$this->have_cols() ){
						?>
						<p class="empty-message"><?= sprintf( __( 'For repeat input [%s] not add col fields. For that do this: <code>$field->add_col_field( add_field_text(...) )</code>' ), $this->get_parent_field()->id() ) ?></p><?php
					} else {
						?>
						<table class="ui mini grey selectable celled table center aligned"><?php
						$this->the_head_html( true );
						?>
						<tbody data-rows-list>
						<?php
							if( $this->VALUE()->rows()->have_rows() ){
								foreach( $this->VALUE()->get_sanitized() as $row_index => $row ){
									$repeat_row = new row( $this, $row, $row_index );
									$repeat_row->index = $row_index;
									$repeat_row->the();
								}
							}
						?>
						</tbody>
						<tbody data-rows-message>
						<tr data-row-empty="<?= $this->VALUE()->rows()->have_rows() ? '1' : '0' ?>">
							<td colspan="<?= $this->have_flex_rows() ? 3 : ( count( $this->get_cols() ) + 2 ) ?>"><p
										class="message"><?= 'Таблица пуста. Для добавления хотя бы одног поля, кликните по кнопке "+"' ?></p>
							</td>
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


		class row{

			/** @var input */
			public $parent_input;

			public $index = 0;
			/** @var col[] */
			public $cols = [];
			public $flex_row_id = '';

			public $have_cols = false;

			public $data = [];


			public function __construct( input $parent_input, $data = [], $row_index = 0 ){
				$this->parent_input = $parent_input;
				$this->flex_row_id = array_key_exists( '_flex_row_id', $data ) ? $data['_flex_row_id'] : '';
				$this->data = $data;
				foreach( $parent_input->get_cols( $this->flex_row_id ) as $id => $col ){
					$this->have_cols = true;
					$this->cols[ $id ] = clone $col;
					$this->cols[ $id ]->set_row( $this );
					$this->cols[ $id ]->input()->name( $this->parent_input->name() . '[' . $row_index . '][' . $id . ']' );
					if( isset( $data[ $id ] ) ){
						$this->cols[ $id ]->value()->set( $data[ $id ] );
					}
				}
			}


			public function the(){
				if( !$this->have_cols ){
					return;
				}
				$compacted_cols = [];
				$index = 0;
				foreach( $this->cols as $col_id => $col ){
					$compacted_cols[ $index ][] = $col;
					if( !$col->compact() ){
						$index ++;
					}
				}
				?>
				<tr data-row="<?= $this->index ?>" data-flex-id="<?= $this->flex_row_id ?>" class="ui segment">
					<td data-drag data-col="_flex_row_id">
						<i class="sort icon"></i>
						<input type="hidden" name="<?= $this->parent_input->name() ?>[<?= $this->index ?>][_flex_row_id]"
							   value="<?= $this->flex_row_id ?>"/>
					</td>
					<?php

						if( $this->parent_input->have_flex_rows() ){
							?>
							<td class="flex-column">
								<table class="hiweb-field-repeat-flex">
									<thead>
									<?php
										/**
										 * @var int   $index
										 * @var col[] $cols
										 */
										foreach( $compacted_cols as $index => $cols ){
											?>
											<th class="hiweb-field-repeat-flex-header"><?= $cols[0]->label() ?></th>
											<?php
										} ?>
									</thead>
									<tbody>
									<tr>
										<?php
											foreach( $compacted_cols as $index => $cols ){
												?>
												<td <?= count( $cols ) > 1 ? 'class="compacted"' : 'data-first-col="' . $cols[0]->id() . '"' ?>>
													<?php
														foreach( $cols as $subindex => $col ){
															?>
															<div class="compacted-col-input" data-col="<?= $col->id() ?>">
																<?php if( $subindex > 0 ){
																	?><p class="flex-label"><?= $col->label() ?></p><?php
																} ?>
																<?php $col->the() ?>
																<?php if( $col->description() != '' ){
																	?>
																	<p class="description flex-description"><?= $col->description() ?></p><?php
																} ?>
															</div>
															<?php
														}
													?>
												</td>
												<?php
											} ?>
									</tr>
									</tbody>
								</table>
							</td>
							<?php
						} else {
							//							$last_compact = false;
							//							foreach ( $this->cols as $col ) {
							//								?>
							<!--								<td data-col="--><? //= $col->id() ?><!--" class="--><? //= ( $col->compact() || $last_compact ) ? 'compact' : '' ?><!--">--><?php //$col->the(); ?><!--</td>-->
							<!--								--><?php
							//								$last_compact = $col->compact();
							//							}
							foreach( $compacted_cols as $index => $cols ){
								?>
								<td <?= count( $cols ) > 1 ? 'class="compacted"' : 'data-first-col="' . $cols[0]->id() . '"' ?>>
									<?php
										foreach( $cols as $subindex => $col ){
											?>
											<div class="compacted-col-input" data-col="<?= $col->id() ?>">
												<?php if( $subindex > 0 ){
													?><p class="flex-label"><?= $col->label() ?></p><?php
												} ?>
												<?php $col->the() ?>
												<?php if( $col->description() != '' ){
													?><p class="description flex-description"><?= $col->description() ?></p><?php
												} ?>
											</div>
											<?php
										}
									?>
								</td>
								<?php
							}
						} ?>
					<td data-ctrl>
						<div class="ui vertical mini compact icon menu">
							<a class="item" title="Копировать строку" data-action-duplicate="<?= $this->index ?>">
								<i class="copy icon"></i>
							</a>
							<a class="item" title="Удалить строку" data-action-remove="<?= $this->index ?>">
								<i class="trash icon"></i>
							</a>
						</div>
						<!--<button title="Duplicate row" class="dashicons dashicons-admin-page" data-action-duplicate="<?= $this->index ?>"></button>
						<button title="Remove row..." class="dashicons dashicons-trash" data-action-remove="<?= $this->index ?>"></button>-->
					</td>
				</tr>
				<?php

			}


		}


		class col{

			/** @var int */
			protected $width = 1;
			/** @var string */
			protected $label = '';
			/** @var string */
			protected $description = '';
			/** @var field */
			protected $embedded_field;
			/** @var field */
			protected $parent_input;
			/** @var \hiweb\fields\input */
			protected $input;
			/** @var \hiweb\fields\value */
			protected $value;
			/** @var row|null */
			private $parent_repeat_row = null;
			/** @var bool */
			private $is_compact = false;

			use hidden_methods;


			public function __construct( input $parent_input, \hiweb\fields\field $embedded_field, $parent_repeat_row = null ){
				$this->parent_input = $parent_input;
				$this->embedded_field = $embedded_field;
				$this->label = $embedded_field->label();
				$this->description = $embedded_field->description();
				$this->parent_repeat_row = $parent_repeat_row;
				$this->input = clone $this->embedded_field->INPUT();
				$this->value = $this->input->VALUE();
			}


			/**
			 * @return \hiweb\fields\input
			 */
			public function input(){
				return $this->input;
			}


			/**
			 * @return \hiweb\fields\value
			 */
			public function value(){
				return $this->value;
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