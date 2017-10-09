<?php

	namespace hiweb\fields\field;


	use hiweb\fields\field;


	class frontend{

		/** @var field */
		private $field;

		public function __construct(field $filed){
			$this->field = $filed;
		}

	}