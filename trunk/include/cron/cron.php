<?php

	namespace hiweb;


	class cron{

		/**
		 * @param string $jobs
		 * @return array
		 */
		static private function string2array( $jobs = '' ){
			$array = explode( "\r\n", trim( $jobs ) ); // trim() gets rid of the last \r\n
			foreach( $array as $key => $item ){
				if( $item == '' ){
					unset( $array[ $key ] );
				}
			}
			return $array;
		}


		/**
		 * @param array $jobs
		 * @return string
		 */
		static private function array2string( $jobs = [] ){
			$string = implode( "\r\n", $jobs );
			return $string;
		}


		/**
		 * @return array
		 */
		static function get_jobs(){
			$output = shell_exec( 'crontab -l' );
			return self::string2array( $output );
		}


		/**
		 * @param array $jobs
		 * @return string
		 */
		static function save_jobs( $jobs = [] ){
			$output = shell_exec( 'echo "' . self::array2string( $jobs ) . '" | crontab -' );
			return $output;
		}


		/**
		 * @param string $job
		 * @return bool
		 */
		static function job_exists( $job = '' ){
			$jobs = self::get_jobs();
			if( in_array( $job, $jobs ) ){
				return true;
			} else {
				return false;
			}
		}


		/**
		 * @param string $job
		 * @return bool|string
		 */
		static function add_job( $job = '' ){
			if( self::job_exists( $job ) ){
				return false;
			} else {
				$jobs = self::get_jobs();
				$jobs[] = $job;
				return self::save_jobs( $jobs );
			}
		}


		/**
		 * @param string $job
		 * @return bool|string
		 */
		static function remove_job( $job = '' ){
			if( string::is_regex( $job ) ){
				$jobs = self::get_jobs();
				foreach( $jobs as $j ){
					if( preg_match( $job, $j ) > 0 ) unset( $jobs[ array_search( $job, $jobs ) ] );
				}
				return self::save_jobs( $jobs );
			} else {
				if( self::job_exists( $job ) ){
					$jobs = self::get_jobs();
					unset( $jobs[ array_search( $job, $jobs ) ] );
					return self::save_jobs( $jobs );
				} else {
					return false;
				}
			}
		}


		/**
		 * @return string
		 */
		static function clear_jobs(){
			return exec( 'crontab -r', $crontab );
		}
	}