<?php

	namespace {


		use hiweb\fields;


		if( !function_exists( 'get_field' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 * @return mixed
			 */
			function get_field( $fieldId, $contextObject = null ){
				return fields::get( $fieldId )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
			}
		}

		if( !function_exists( 'the_field' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 */
			function the_field( $fieldId, $contextObject = null ){
				echo fields::get( $fieldId )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
			}
		}

		if( !function_exists( 'get_field_content' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 * @return mixed
			 */
			function get_field_content( $fieldId, $contextObject = null ){
				return fields::get( $fieldId )->CONTEXT( $contextObject )->VALUE()->get_content();
			}
		}

		if( !function_exists( 'the_field_content' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 * @return mixed
			 */
			function the_field_content( $fieldId, $contextObject = null ){
				echo fields::get( $fieldId )->CONTEXT( $contextObject )->VALUE()->get_content();
			}
		}
	}