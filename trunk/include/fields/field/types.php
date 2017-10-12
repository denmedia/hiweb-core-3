<?php

	namespace hiweb\fields\field;


	use hiweb\console;
	use hiweb\fields\field;


	class types{

		/**
		 * @var array
		 */
		static $types = [];
		static $inputs = [];


		/**
		 * @param string $type
		 * @param field  $field
		 * @return bool|type
		 */
		static function get( $type = 'text', $field = null ){
			$class = false;
			if( self::has_type( $type ) ){
				$classNames = array_reverse( self::$types[ $type ] );
				foreach( $classNames as $className ){
					if( !class_exists( $className ) ){
						console::debug_warn( 'Класс для инпута не найден', $classNames );
						continue;
					}
					///Make input
					$class = new $className( $field, $type );
					if( !$class instanceof type ){
						console::debug_warn( sprintf( __( 'Class [%s] dosen\'t extends hw_input! For this class Assign heir:\n<?php class %s  extends hw_input{ ... }' ), $className ) );
						continue;
					}
					break;
				}
			}

			if( !$class instanceof type ){
				console::debug_warn( sprintf( __( 'Type of input [%s] not found', 'hw-core-2' ), $type ) );
				///Make default input
				$class = new type( $field, $type );
			}

			//$class->tag_add( 'data-global-id', $global_id );
			self::$inputs[ spl_object_hash( $class ) ] = $class;
			return $class;
		}


		/**
		 * Register input ype for
		 * @param      $type
		 * @param      $className
		 * @param null $include_path
		 * @param int  $priority
		 */
		static function register( $type, $className, $include_path = null, $priority = 10 ){
			if( is_null( $include_path ) || trim( $include_path ) == '' ){
				$include_file = \hiweb\file( $include_path );
				if( $include_file->extension == 'php' ) include_once $include_file->path; else console::debug_warn( 'Путь до файла регистрируемого типа указан не верно', $include_path );
			} else {
				$include_path = HIWEB_DIR_FIELD_TYPES . '/' . $type . '/.php';
				include_once $include_path;
			}
			$priority = intval( $priority );
			if( !array_key_exists( $type, self::$types ) ){
				self::$types[ $type ] = [];
			}
			if( array_key_exists( $priority, self::$types[ $type ] ) ){
				$movedClassName = self::$types[ $type ][ $priority ];
				self::register( $type, $priority + 1, $movedClassName );
			}
			self::$types[ $type ][ $priority ] = $className;
		}


		/**
		 * Возвращает TRUE, если тип инпута существует
		 * @param $type
		 * @return bool
		 */
		static function has_type( $type ){
			return ( array_key_exists( $type, self::$types ) && is_array( self::$types[ $type ] ) && count( self::$types[ $type ] ) > 0 );
		}


		private function get_free_global_id( $input_name ){
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $input_name . '_' . $count;
				if( !isset( $this->inputs[ $input_name_id ] ) ) return $input_name_id;
			}
			return false;
		}


	}