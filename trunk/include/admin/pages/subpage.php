<?php

	namespace hiweb\admin\pages;


	use hiweb\console;


	class subpage extends page_abstract{

		protected $parent_slug = '';


		/**
		 * @param string $set
		 * @return string
		 */
		public function parent_slug($set = null){
			return $this->set(__FUNCTION__, $set);
		}

	}