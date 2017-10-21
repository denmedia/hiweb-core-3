<?php

	namespace hiweb\fields;


	use hiweb\console;
	use hiweb\fields\locations\location;
	use hiweb\fields\locations\locations;


	class forms{

		/**
		 * @param field $field
		 * @return string
		 */
		static public function get_field_input_name( field $field ){
			return $field->admin_input_name( 'hiweb' );
		}


		/**
		 * @param field $field
		 * @return string
		 */
		static public function get_field_input_option_name( field $field ){
			$options_admin_menus = $field->location()->get_options_by_type( 'admin_menus' );
			if( isset( $options_admin_menus['menu_slug'] ) ) return $field->admin_input_name( 'hiweb-option-' . $options_admin_menus['menu_slug'] );
			return $field->admin_input_name( 'hiweb-option' );
		}


		/**
		 * @param $page_slug
		 * @return string
		 */
		static public function get_section_id( $page_slug ){
			return 'hiweb-options-section-' . $page_slug;
		}


		/**
		 * @param $field_id
		 * @return string
		 */
		static function get_columns_field_id( $field_id ){
			return 'hiweb-column-' . $field_id;
		}


		/**
		 * @param field $field
		 * @param string $templateName
		 */
		static function set_field_defaultTemplate( field $field, $templateName = 'default' ){
			if( $field->admin_template() == 'default' ){
				$field->admin_template( $templateName );
			}
		}


		/**
		 * @param field[] $fields
		 * @param string $templateName
		 */
		static function set_fields_defaultTemplate( $fields, $templateName = 'default' ){
			if( is_array( $fields ) ) foreach( $fields as $field ){
				self::set_field_defaultTemplate( $field, $templateName );
			}
		}


		/**
		 * @param field $field
		 * @param mixed $value
		 * @param array $attributes
		 * @return string
		 */
		static public function get_fieldset( field $field, $value = null, $attributes = [] ){
			$R = '';
			$template_path = __DIR__ . '/templates/' . $field->admin_template() . '.php';
			if( !is_file( $template_path ) || !\is_readable( $template_path ) ){
				console::debug_warn( 'Не удалось найти шаблон для поля', $field->admin_template() );
				$template_path = __DIR__ . '/templates/default.php';
			}
			if( !is_file( $template_path ) || !\is_readable( $template_path ) ){
				console::debug_error( 'Не удалось найти шаблон для поля DEFAULT', $field->admin_template() );
			} else {
				ob_start();
				include $template_path;
				$R = ob_get_clean();
			}
			return $R;
		}


		/**
		 * @param field[] $fields
		 * @param null|\WP_Post|\WP_Term|\WP_User|string $contextObject
		 * @param string $formTemplate
		 * @return string
		 */
		static public function get_form_by_fields( $fields = [], $contextObject = null, $formTemplate = 'default' ){
			$fields_by_template = [];
			$templates_by_index = [];
			$last_template = '';
			$template_index = - 1;
			///
			foreach( $fields as $field_id => $field ){
				self::set_field_defaultTemplate( $field, $formTemplate );
				if( !$field instanceof field ) continue;
				if( $field->admin_template() != $last_template ){
					$template_index ++;
					$last_template = $field->admin_template();
				}
				$fields_by_template[ $template_index ][] = $field;
				$templates_by_index[ $template_index ] = $last_template;
			}
			///
			$R = [];
			if( count( $fields_by_template ) > 0 ){
				foreach( $fields_by_template as $index => $fields ){
					$template_name = $templates_by_index[ $index ];
					ob_start();
					$template_path = __DIR__ . '/templates/' . $template_name . '-form.php';
					if( !\hiweb\file( $template_path )->is_readable ) $template_path = __DIR__ . '/templates/default-form.php';
					include $template_path;
					$form_html = ob_get_clean();
					$fields_html = [];
					/**
					 * @var string $field_id
					 * @var field $field
					 */
					foreach( $fields as $field_id => $field ){
						$fields_html[] = $field->context( $contextObject )->get_fieldset( [ 'name' => is_string( $contextObject ) ? self::get_field_input_option_name( $field ) : self::get_field_input_name( $field ) ] );
					}
					$R[] = str_replace( '<!--fields-->', implode( '', $fields_html ), $form_html );
				}
			}
			return implode( '<!--form-->', $R );
			///
		}


		/**
		 * @param array $fields
		 * @param null $contextObject
		 * @param string $formTemplate
		 */
		final static function the_form_by_fields( $fields = [], $contextObject = null, $formTemplate = 'default' ){
			echo self::get_form_by_fields( $fields, $contextObject, $formTemplate );
		}


		/**
		 * @param location $contextLocation
		 * @param null $contextObject
		 * @param string $formTemplate
		 */
		final static function the_form_by_contextLocation( location $contextLocation, $contextObject = null, $formTemplate = 'default' ){
			$fields = locations::get_fields_by_contextLocation( $contextLocation );
			self::the_form_by_fields( $fields, $contextObject, $formTemplate );
		}


		/**
		 * @param null $contextObject
		 * @param string $formTemplate
		 */
		final static function the_form_by_contextObject( $contextObject = null, $formTemplate = 'default' ){
			$fields = locations::get_fields_by_contextObject( $contextObject );
			self::the_form_by_fields( $fields, $contextObject, $formTemplate );
		}

	}