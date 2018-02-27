<?php

	namespace hiweb\fields\field\form;


	use hiweb\fields\field\form;


	class width{

		private $parent_form;
		private $width = 'full';


		public function __construct( form $parent_form ){
			$this->parent_form = $parent_form;
		}


		/**
		 * @return string
		 */
		public function get(){
			return $this->width;
		}


		/**
		 * Occupies 1/2 of the window (on medium and large screens)
		 * @return $this
		 */
		public function half(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 1/3 of the window (on medium and large screens)
		 * @return $this
		 */
		public function third(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 2/3 of the window (on medium and large screens)
		 * @return $this
		 */
		public function two_third(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 1/4 of the window (on medium and large screens)
		 * @return $this
		 */
		public function quarter(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 3/4 of the window (on medium and large screens)
		 * @return form|null
		 */
		public function three_quarter(){
			return $this->width = __FUNCTION__;
		}


		/**
		 * Occupies 1/5 of the window (on medium and large screens)
		 * @return $this
		 */
		public function fifth(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 2/5 of the window (on medium and large screens)
		 * @return $this
		 */
		public function two_fifth(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 3/5 of the window (on medium and large screens)
		 * @return $this
		 */
		public function three_fifth(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies 4/5 of the window (on medium and large screens)
		 * @return $this
		 */
		public function four_fifths(){
			$this->width = __FUNCTION__;
			return $this;
		}


		/**
		 * Occupies the rest of the column width
		 * @return $this
		 */
		public function full(){
			$this->width = __FUNCTION__;
			return $this;
		}

	}