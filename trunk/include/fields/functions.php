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

		if( !function_exists( 'get_field_content' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 * @param null $attr_1
			 * @param null $attr_2
			 * @param null $attr_3
			 * @return mixed
			 */
			function get_field_content( $fieldId, $contextObject = null, $attr_1 = null, $attr_2 = null, $attr_3 = null ){
				return fields::get( $fieldId )->context( $contextObject )->content($attr_1, $attr_2, $attr_3);
			}
		}

		if( !function_exists( 'the_field_content' ) ){
			/**
			 * @param string $fieldId
			 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
			 * @return mixed
			 */
			function the_field_content( $fieldId, $contextObject = null ){
				echo fields::get( $fieldId )->context( $contextObject )->content();
			}
		}
	}