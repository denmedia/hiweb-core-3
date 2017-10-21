<?php

	namespace hiweb;


	use hiweb\admin\pages\page;
	use hiweb\admin\pages\pages;
	use hiweb\admin\pages\subpage;


	class admin{

		/**
		 * @param string $slug
		 * @param string $title
		 * @param null $parent_slug
		 * @return page
		 */
		static function add_page( $slug, $title, $parent_slug = null ){
			if( is_null( $parent_slug ) ){
				$PAGE = new page( $slug, $title );
				pages::$pages[ $slug ] = $PAGE;
			} else {
				$PAGE = new subpage( $slug, $title );
				$PAGE->parent_slug( $parent_slug );
				pages::$pages[ $slug ] = $PAGE;
			}
			return $PAGE;
		}

	}