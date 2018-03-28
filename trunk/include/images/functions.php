<?php

	/**
	 * @param $pathOrUrlOrId
	 * @return \hiweb\images\image
	 */
	function get_image( $pathOrUrlOrId ){
		return hiweb\images::get( $pathOrUrlOrId );
	}