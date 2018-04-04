<?php

	namespace hiweb;


	use hiweb\admin\notices\notice;
	use hiweb\admin\notices\notices;
	use hiweb\admin\pages\page;
	use hiweb\admin\pages\pages;
	use hiweb\admin\pages\subpage;


	class admin{

		/**
		 * @param strings $slug
		 * @param strings $title
		 * @param null $parent_slug
		 *
		 * @return page
		 */
		static function ADD_PAGE( $slug, $title, $parent_slug = null ){
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


		/**
		 * @param strings $content
		 *
		 * @return notice
		 */
		static function NOTICE( $content = '&nosp;' ){
			$NOTICE = new notice( $content );
			notices::$notices[] = $NOTICE;
			return $NOTICE;
		}

	}