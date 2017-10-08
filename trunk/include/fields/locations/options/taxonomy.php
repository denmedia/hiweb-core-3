<?php

	namespace hiweb\fields\locations;


	use hiweb\fields\field;
	use hiweb\fields\locations\options\options;
	use hiweb\fields\separator;


	class taxonomy extends options{


		public function hierarchical( $set = true, $append = false ){
			$this->set_option( __FUNCTION__, $set, $append );
			return $this;
		}


		public function name( $set, $append = false ){
			$this->set_option( __FUNCTION__, $set, $append );
			return $this;
		}


		public function object_type( $set, $append = false ){
			$this->set_option( __FUNCTION__, $set, $append );
			return $this;
		}


		public function term( $taxonomy, $terms, $term_field = 'slug' ){
			$this->set_option( __FUNCTION__, [ $taxonomy => [ $terms, $term_field ] ], false );
			return $this;
		}


		/**
		 * @return location
		 */
		public function get_location(){
			return $this->location;
		}

	}
