<?php

	namespace {


		if( !function_exists( 'add_field_file' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\file\field
			 */
			function add_field_file( $id ){
				$new_field = new hiweb\fields\types\file\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\file {


		use hiweb\files;
		use hiweb\path;


		class field extends \hiweb\fields\field{


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{


			private $has_file = [];


			/**
			 * Возвращает TRUE, если файл существует
			 * @return bool
			 */
			public function have_file(){
				$attachment_url = wp_get_attachment_url( $this->VALUE()->get() );
				$this->has_file[ $attachment_url ] = path::is_readable( $attachment_url );
				return $this->has_file[ $attachment_url ];
			}


			public function html(){
				wp_enqueue_media();
				\hiweb\js( HIWEB_DIR_JS . '/field-file.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-file.css' );
				///
				$file = false;
				$attachment_id = $this->VALUE()->get();
				if( $this->have_file() ){
					$file = files::get( wp_get_attachment_url( $attachment_id ) );
				}
				ob_start();
				?>
				<div class="ui move up reveal hiweb-field-file" id="<?= $this->global_id() ?>" data-has-file="<?= $this->have_file() ? '1' : '0' ?>" data-file-mime="<?= $file instanceof files\file ? $file->mime : '' ?>" data-file-image="<?= $file instanceof files\file ? ( $file->is_image() ? 'image' : 'file' ) : '' ?>">
					<div class="visible content">
						<div class="ui center aligned raised segment" data-segment="1">
							<i class="question circle outline icon" data-icon="select"></i>
							<i class="file alternate outline icon" data-icon="deselect"></i>
							<div class="thumbnail" <?= ( $file instanceof files\file ? $file->is_image() : false ) ? 'style="background-image: url(' . get_image( $attachment_id )->get_src() . ')"' : '' ?>>

							</div>
						</div>
						<div class="ui bottom attached label" data-file-nonselect-text>Файл не выбран</div>
						<div class="ui bottom attached blue label" data-file-name><?= $file instanceof files\file ? $file->basename : '' ?></div>
					</div>
					<div class="hidden content">
						<div class="ui segment center aligned" data-segment="2">

							<div class="big ui primary icon button" data-click="select" data-tooltip="Выбрать файл..." data-inverted="">
								<i class="folder open outline icon"></i>
							</div>

							<div class="ui buttons">
								<div class="ui icon primary button" data-click="edit" data-tooltip="Редактировать..." data-inverted="">
									<i class="edit outline icon"></i>
								</div>
								<div class="ui icon button" data-click="deselect" data-tooltip="Отменить выбор" data-inverted="">
									<i class="window close outline icon"></i>
								</div>
							</div>

						</div>
					</div>
					<input type="hidden" <?= $this->sanitize_attributes() ?> value="<?= ( $this->has_file ? $this->VALUE()->get() : '' ) ?>"/>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}