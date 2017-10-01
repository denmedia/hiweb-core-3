<?php

	namespace hiweb;


	/**
	 * Return path to base hiweb core dir
	 * @return string
	 */
	function DIR(){
		return dirname( __DIR__ );
	}

	/**
	 * Return url to base hiweb core dir
	 * @return string
	 */
	function URL(){
		return path\path_to_url( DIR() );
	}

	/**
	 * @return string
	 */
	function DIR_INCLUDE(){
		return DIR() . '/include';
	}

	/**
	 * @return string
	 */
	function URL_INCLUDE(){
		return path\path_to_url( DIR_INCLUDE() );
	}

	function DIR_VIEWS(){
		return DIR() . '/views';
	}

	/**
	 * @return mixed
	 */
	function URL_VIEWS(){
		return path\path_to_url( DIR_VIEWS() );
	}

	/**
	 * @return string
	 */
	function DIR_ASSETS(){
		return DIR() . '/assets';
	}

	/**
	 * @return mixed
	 */
	function URL_ASSETS(){
		return path\path_to_url( DIR_ASSETS() );
	}

	/**
	 * @return string
	 */
	function DIR_CSS(){
		return DIR_ASSETS() . '/css';
	}

	/**
	 * @return mixed
	 */
	function URL_CSS(){
		return path\path_to_url( DIR_CSS() );
	}

	/**
	 * @return string
	 */
	function DIR_JS(){
		return DIR_ASSETS() . '/js';
	}

	/**
	 * @return mixed
	 */
	function URL_JS(){
		return path\path_to_url( DIR_JS() );
	}

	/**
	 * @return string
	 */
	function DIR_VENDORS(){
		return DIR() . '/vendors';
	}

	/**
	 * @return mixed
	 */
	function URL_VENDORS(){
		return path\path_to_url( DIR_VENDORS() );
	}