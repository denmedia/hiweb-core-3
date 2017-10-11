<?php

	namespace hiweb\fields\field\types;

	use hiweb\fields\field\type;
	use hiweb\fields\field\types;


	types::register('text', __NAMESPACE__.'\text');

	class text extends type{

		public function __construct( $id = false ){ parent::__construct( $id ); }

	}