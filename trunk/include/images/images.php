<?php

	namespace hiweb;


	use hiweb\images\image;


	class images{

		/** @var images\image[] */
		static private $images = [];


		/**
		 * @param int $attach_id
		 * @return image
		 */
		static public function get( $attach_id ){
			if( !isset( self::$images[ $attach_id ] ) ){
				self::$images[ $attach_id ] = new image( $attach_id );
			}
			return self::$images[ $attach_id ];
		}

	}