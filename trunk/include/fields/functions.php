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
				return fields::get( $fieldId )->context( $contextObject )->value();
			}
		}

		if( !function_exists( 'the_field' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 */
			function the_field( $fieldId, $contextObject = null ){
				echo fields::get( $fieldId )->context( $contextObject )->value();
			}
		}
	}