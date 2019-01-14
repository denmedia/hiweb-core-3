<?php

	namespace {


		if( !function_exists( 'add_field_fontawesome' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\fontawesome\field
			 */
			function add_field_fontawesome( $id ){
				$new_field = new hiweb\fields\types\fontawesome\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\fontawesome {


		use hiweb\strings;


		class field extends \hiweb\fields\field{

			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}


		}


		class input extends \hiweb\fields\input{

			public function __construct( \hiweb\fields\field $field, \hiweb\fields\value $value ){
				parent::__construct( $field, $value );
				add_action( 'wp_ajax_hiweb-type-fontawesome', function(){
					header( 'Content-Type: application/json' );
					echo json_encode( self::get_icons_list( \hiweb\urls::request( 'query' ) ) );
					die;
				} );
			}


			private function get_icons_list( $query = '' ){
				$icons = json_decode( file_get_contents( HIWEB_DIR_VENDORS . '/font-awesome-5/font-awesome.5.3.1.json' ), true );
				$query = strtolower( trim( $query ) );
				$R = [];
				$limit = 9999;
				foreach( $icons as $icon ){
					//					foreach( $icon['styles'] as $style ){
					//
					//					}
					$class_name = 'fa' . $icon['styles'][0][0] . ' fa-' . $icon['name'];
					if( trim( $query ) != '' ){
						$math = 0;
						if( strpos( $class_name, $query ) !== false ) $math ++;
						if( strpos( strtolower( $icon['label'] ), $query ) !== false ) $math ++;
						foreach( $icon['keywords'] as $keyword ){
							if( strpos( $keyword, $query ) !== false ) $math ++;
						}
						foreach( $icon['categories'] as $keyword ){
							if( strpos( $keyword, $query ) !== false ) $math ++;
						}
						foreach( $icon['membership'] as $groups ){
							foreach( $groups as $keyword ){
								if( strpos( $keyword, $query ) !== false ) $math ++;
							}
						}
						if( $math == 0 ) continue;
					}
					$R['values'][] = [
						'value' => $class_name,
						'name' => '<i class="' . $class_name . '"></i>',
						'text' => $icon['objectID']
					];
					foreach( $icon['styles'] as $style ){
						$class_name = 'fa' . $style[0] . ' fa-' . $icon['name'];
						$R['styles'][ $icon['objectID'] ][] = $class_name;
					}
					$limit --;
					if( $limit < 1 ) break;
				}
				return $R;
			}


			public function html(){
				\hiweb\css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/svg-with-js.min.css' );
				$font_awesome = \hiweb\js( HIWEB_DIR_VENDORS . '/font-awesome-5/js/all.min.js' );
				\hiweb\css( HIWEB_URL_CSS . '/field-fontawesome.css' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
				\hiweb\js( HIWEB_URL_ASSETS . '/js/field-fontawesome.js', [ 'jquery' ] );
				///
				ob_start();
				$this->attributes['class'] = '';
				$this->attributes['value'] = $this->VALUE()->get();
				$rnd_id = strings::rand( 10 );
				?>
				<div class="hiweb-field-fontawesome" data-dialog-title="Выбор иконки" data-rand-id="<?= $rnd_id ?>">
					<div class="ui action input">
						<input data-placement="top" autocomplete="off" class="form-control" <?= $this->sanitize_attributes() ?> type="text" data-rand-id="<?= $rnd_id ?>" title="Для поиска иконки, введите часть слова латинскими буквами и подождите несколько секунд"/>
						<span class="input-group-addon">
						<i class="<?= $this->VALUE()->get() ?>" aria-hidden="true"></i>
					</span>
						<!--<a href="#" data-click class="button" title="Выбрать иконку" data-rand-id="<?= $rnd_id ?>">...</a>-->
						<button class="ui icon button disabled" data-click="styles" title="Выбор альтернативных вариантов иконки">
							<i class="adjust icon"></i>
						</button>
						<button class="ui icon button" data-click="icons" data-rand-id="<?= $rnd_id ?>" title="Выбор из всей библиотеки иконок">
							<i class="caret down icon"></i>
						</button>
					</div>
					<div class="ui fluid search dropdown">
						<div class="menu">
						</div>
					</div>
				</div>
				<?php
				return ob_get_clean();
			}
		}


		class value extends \hiweb\fields\value{

			public $data = 'fab fa-wordpress';

		}
	}