<?php

	namespace hiweb;


	/**
	 * @param $pathOrUrl
	 * @return files\file
	 */
	function file( $pathOrUrl ){
		return files::get( $pathOrUrl );
	}