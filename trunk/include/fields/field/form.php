<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 14.02.2018
	 * Time: 19:38
	 */

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	class form{

		/** @var field */
		private $field;

		protected $width;
		protected $show_labels = true;


		/**
		 * form constructor.
		 * @param field $field
		 */
		public function __construct( field $field ){
			$this->field = $field;
			$this->width = new field\form\width( $this );
		}


		/**
		 * @return field
		 */
		public function get_parent_field(){
			return $this->field;
		}


		/**
		 * @param $key
		 * @param null $value
		 * @return $this|null
		 */
		protected function set_property( $key, $value = null ){
			if( !property_exists( $this, $key ) ){
				console::debug_warn( 'Попытка доступа к несуществующему свойству для ' . __METHOD__, $key );
				return null;
			} else {
				if( is_null( $value ) ){
					return $this->{$key};
				} else {
					$this->{$key} = $value;
					return $this;
				}
			}
		}


		/**
		 * @return form\width
		 */
		public function WIDTH(){
			return $this->width;
		}


		/**
		 * @param bool $set
		 * @return form|null
		 */
		public function show_labels( $set = null ){
			return $this->set_property( __FUNCTION__, $set );
		}

	}