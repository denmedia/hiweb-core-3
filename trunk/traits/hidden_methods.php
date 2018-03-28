<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.02.2018
	 * Time: 18:49
	 */

	namespace hiweb;


	trait hidden_methods{

		public static function __callStatic( $name, $arguments ){
			console::debug_warn( 'Попытка вызова несуществующего статического метода', $name );
		}


		public function __call( $name, $arguments ){
			if( method_exists( $this, $name ) ){
				return $this->{$name};
			} else {
				console::debug_warn( 'Попытка вызова несуществующего метода', $name );
			}
		}


		public function __get( $name ){
			console::debug_warn( 'Попытка получения несуществующего свойства', $name );
		}


		public function __set( $name, $value ){
			console::debug_warn( 'Попытка установки несуществующего свойства', $name );
		}


	}