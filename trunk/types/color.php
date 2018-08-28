<?php

	namespace {


		if( !function_exists( 'add_field_color' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\color\field
			 */
			function add_field_color( $id ){
				$new_field = new hiweb\fields\types\color\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\color {


		class field extends \hiweb\fields\field{

			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\js( HIWEB_DIR_JS . '/field-color.min.js' );
				//\hiweb\js( HIWEB_DIR_VENDORS . '/tinyColorPicker/jqColorPicker.min.js' );
				//\hiweb\js( HIWEB_DIR_VENDORS . '/jquery.tinycolorpicker/jquery.tinycolorpicker.min.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-color.min.css' );
				///
				$this->attributes['type'] = 'text';
				$this->attributes['data-type-color'] = ' ';
				$this->attributes['value'] = $this->VALUE()->get_sanitized();
				///
				ob_start();
				?>
				<div class="hiweb-field-color">
					<div class="ui action input">
						<input type="text" <?= $this->sanitize_attributes() ?>>

						<button class="ui icon button" data-colorpicker-show <?= $this->VALUE()->get() != '' ? 'style="background-color: ' . $this->VALUE()->get() . '"' : '' ?>>
							<i class="eye dropper icon"></i>
						</button>

						<div class="ui flowing popup top center transition hidden">
							<div class="ui pointing secondary menu">
								<a class="item active" data-tab="first">
									<i class="th icon"></i>
								</a>
								<a class="item" data-tab="second">
									<i class="tint icon"></i>
								</a>
								<a class="item" data-tab="third">
									<i class="dot circle icon"></i>
								</a>
							</div>
							<div class="ui tab active" data-tab="first">
								<div data-colorpicker-wrap="0">
									<img src="<?= HIWEB_URL_ASSETS . '/img/colorpicker-3.png' ?>"/>
								</div>
							</div>
							<div class="ui tab" data-tab="second">
								<div data-colorpicker-wrap="1">
									<img src="<?= HIWEB_URL_ASSETS . '/img/colorpicker-2.gif' ?>"/>
								</div>
							</div>
							<div class="ui tab" data-tab="third">
								<div data-colorpicker-wrap="2">
									<img src="<?= HIWEB_URL_ASSETS . '/img/colorpicker-1.png' ?>"/>
								</div>
							</div>

						</div>

					</div>
				</div>
				<?php
				return ob_get_clean();
			}


		}
	}