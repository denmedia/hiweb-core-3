<?php

	namespace {


		use hiweb\fields\types\date\field;


		if( !function_exists( 'add_field_date' ) ){
			/**
			 * @param $id
			 * @return field
			 */
			function add_field_date( $id ){
				$new_field = new field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\date {


		class field extends \hiweb\fields\field{

			protected $placeholder = 'YYYY-MM-DD';


			/**
			 * @param null $set
			 * @return $this|string
			 */
			public function placeholder( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				\hiweb\js( HIWEB_DIR_JS . '/field-date.min.js', [ 'jquery' ] );
				\hiweb\css( HIWEB_DIR_CSS . '/field-date.min.css' );
				\hiweb\css( HIWEB_DIR_VENDORS . '/jquery.zabuto_calendar/zabuto_calendar.min.css' );
				\hiweb\js( HIWEB_DIR_VENDORS . '/jquery.zabuto_calendar/zabuto_calendar.min.js', [ 'jquery' ] );
				ob_start();
				?>
				<div class="hiweb-field-date">
					<div class="ui action input">
						<input type="text" placeholder="<?= $this->get_parent_field()->placeholder() ?>" name="<?= $this->name() ?>" value="<?= $this->VALUE()->get() ?>">
						<button class="ui icon button" data-datepicker-show>
							<i class="calendar icon"></i>
						</button>

						<div class="ui flowing popup top center transition hidden">
							<div data-calendarpicker="1"></div>
						</div>

					</div>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}