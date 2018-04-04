<?php

	namespace {


		use hiweb\fields;


		if ( ! function_exists( 'get_field' ) ) {
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 *
			 * @return mixed
			 */
			function get_field( $fieldId, $contextObject = null ) {
				return fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
			}
		}

		if ( ! function_exists( 'get_field_default' ) ) {
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 *
			 * @return mixed
			 */
			function get_field_default( $fieldId, $contextObject = null ) {
				return fields::get_by_context( $fieldId, $contextObject )->VALUE()->get_sanitized();
			}
		}

		if ( ! function_exists( 'the_field' ) ) {
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 */
			function the_field( $fieldId, $contextObject = null ) {
				echo fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
			}
		}

		if ( ! function_exists( 'the_field_default' ) ) {
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 */
			function the_field_default( $fieldId, $contextObject = null ) {
				echo fields::get_by_context( $fieldId, $contextObject )->VALUE()->get_sanitized();
			}
		}

		if ( ! function_exists( 'get_field_content' ) ) {
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 *
			 * @return mixed
			 */
			function get_field_content( $fieldId, $contextObject = null ) {
				return fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_content();
			}
		}

		if ( ! function_exists( 'the_field_content' ) ) {
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 *
			 * @return mixed
			 */
			function the_field_content( $fieldId, $contextObject = null ) {
				echo fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_content();
			}
		}
	}