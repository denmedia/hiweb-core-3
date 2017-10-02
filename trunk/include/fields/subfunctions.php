<?php

	namespace hiweb\fields;


	use hiweb\fields;


	/**
	 * @param string $field_id
	 * @param string $page_slug
	 * @return string
	 */
	function get_options_field_id( $page_slug, $field_id ){
		return $page_slug . '-' . $field_id;
	}

	/**
	 * @param $page_slug
	 * @return string
	 */
	function get_options_group_id( $page_slug ){
		return 'hiweb-options-group-' . $page_slug;
	}

	/**
	 * @param $field_id
	 * @return string
	 */
	function get_columns_field_id( $field_id ){
		return 'hiweb-column-' . $field_id;
	}

	/**
	 * Смена глобального ID для поля
	 * @param $oldGlobalId
	 * @param $newGlobalId
	 * @return bool
	 */
	function change_globalId( $oldGlobalId, $newGlobalId ){
		if( !isset( fields::$fields[ $oldGlobalId ] ) ) return false;
		$field = fields::$fields[ $oldGlobalId ];
		unset( fields::$fields[ $oldGlobalId ] );
		fields::$fields[ $newGlobalId ] = $field;
		if( isset( fields::$fieldId_globalId[ $field->id() ] ) && is_array( fields::$fieldId_globalId[ $field->id() ] ) ) foreach( fields::$fieldId_globalId[ $field->id() ] as $index => $globalIds ){
			if( $globalIds == $oldGlobalId ){
				fields::$fieldId_globalId[ $field->id() ][ $index ] = $newGlobalId;
			}
		}
		if( isset( fields::$globalId_fieldId[ $oldGlobalId ] ) && is_array( fields::$globalId_fieldId[ $oldGlobalId ] ) ){
			$ids = fields::$globalId_fieldId[ $oldGlobalId ];
			unset( fields::$globalId_fieldId[ $oldGlobalId ] );
			fields::$globalId_fieldId[ $newGlobalId ] = $ids;
		}
		return true;
	}