<?php

	namespace {


		function have_rows( $fieldId, $contextObject = null ){

		}

		function the_row(){

		}
	}

	namespace hiweb\fields {


		use hiweb\fields;


		class rows{

			private $current_field_context_id;


			/**
			 * @param field $field
			 * @param null  $contextObject
			 * @return string
			 */
			private function get_field_context_id( field $field, $contextObject = null ){
				return $field->id() . '-' . $field->context( $contextObject )->id();
			}


			public function have_rows( $fieldId, $contextObject = null ){
				if( !fields::is_register( $fieldId ) ) return false;
				$field = fields::get( $fieldId );
				$field_context_id = $this->get_field_context_id( $field, $contextObject );
				///
				return $field->context( $contextObject )->have_rows();
			}


			public function the_row(){
				if( !is_string( $this->current_field_context_id ) ) return null;

			}

		}
	}