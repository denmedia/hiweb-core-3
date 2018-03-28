<?php

	namespace hiweb\fields;


	use hiweb\arrays;
	use hiweb\console;
	use function hiweb\css;
	use function hiweb\dump;
	use hiweb\fields\locations\location;
	use hiweb\fields\locations\locations;


	class forms{

		/**
		 * @param field $field
		 * @return string
		 */
		static public function get_field_input_name( field $field ){
			return 'hiweb-' . $field->id();
		}


		/**
		 * @param field $field
		 * @return string
		 */
		static public function get_field_input_option_name( field $field ){
			$options_admin_menus = $field->LOCATION()->_get_options_by_type( 'admin_menus' );
			if( isset( $options_admin_menus['menu_slug'] ) ) return 'hiweb-option-' . $options_admin_menus['menu_slug'] . '-' . $field->id();
			return 'hiweb-option-' . $field->id();
		}


		/**
		 * @param $page_slug
		 * @return string
		 */
		static public function get_section_id( $page_slug ){
			return 'hiweb-options-section-' . $page_slug;
		}


		static public function get_option_group_id( $menu_slug ){
			return 'hiweb-option-group-' . $menu_slug;
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
		 * @return string
		 */
		static public function get_fieldset_classes( field $field ){
			$classes = [ 'hiweb-fieldset' ];
			$classes[] = 'hiweb-fieldset-width-' . $field->FORM()->WIDTH()->get();
			$classes[] = 'hiweb-field-' . $field->id();
			$classes[] = 'hiweb-field-' . $field->global_id();
			return implode( ' ', $classes );
		}


		/**
		 * Get template form php-file path
		 * @param string $name
		 * @param bool $get_field_template
		 * @return bool|string
		 */
		static private function get_template( $name = 'default', $get_field_template = false ){
			$formTemplate_path = __DIR__ . '/templates/' . $name . ( $get_field_template ? '-field' : '-form' ) . '.php';
			if( !file_exists( $formTemplate_path ) || !is_readable( $formTemplate_path ) || !is_file( $formTemplate_path ) ){
				if( $name == 'default' ){
					console::debug_warn( 'Попытка получения шаблона формы, но файла по умолчанию не существует' );
					return false;
				} else return self::get_template( 'default', $get_field_template );
			}
			return $formTemplate_path;
		}


		/**
		 * @param field[] $fields
		 * @param null|\WP_Post|\WP_Term|\WP_User|string $contextObject
		 * @param string $formTemplate
		 * @return string
		 */
		static public function get_form_by_fields( $fields = [], $contextObject = null, $formTemplate = 'default' ){
			if( arrays::is_empty( $fields ) ) return '';
			///
			css( HIWEB_URL_CSS . '/fields.css' );
			$template_path = self::get_template( $formTemplate, false );
			if( $template_path === false ) return '';
			$template_field_path = self::get_template( $formTemplate, true );
			if( $template_field_path === false ) return '';
			ob_start();
			@include $template_path;
			$form_html = ob_get_clean();
			///
			$fields_html = [];
			foreach( $fields as $field ){
				//Set input name for OPTIONS PAGE
				if( is_string( $contextObject ) ){
					$field->INPUT()->name( self::get_field_input_option_name( $field ) );
				}
				ob_start();
				@include $template_field_path;
				$fields_html[] = str_replace( '<!--input-->', $field->CONTEXT( $contextObject )->INPUT()->html(), ob_get_clean() );
			}
			///
			return str_replace( '<!--fields-->', implode( chr( 13 ) . chr( 10 ), $fields_html ), $form_html );
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