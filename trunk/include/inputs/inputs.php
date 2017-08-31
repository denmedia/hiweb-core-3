<?php

	//	require_once 'inputs/hw_input_attributes.php';
	//	require_once 'inputs/hw_input_value.php';
	//	require_once 'inputs/hw_input_tags.php';
	//	require_once 'inputs/hw_input_axis.php';
	//	require_once 'inputs/hw_input_axis_rows.php';
	//	require_once 'inputs/hw_input.php';

	namespace hiweb;


	class inputs{

		/**
		 * @var array|inputs\input[]
		 */
		static $inputs = [];


		//use hw_hidden_methods_props;


		/**
		 * @var array
		 */
		static private $types = [];


		static $dir_inputs = 'inputs';


		public function __construct(){
			//$this->dir_inputs = hiweb()->dir_include . '/' . $this->dir_inputs;
		}


		/**
		 * Возвращает TRUE, если тип инпута существует
		 * @param $type
		 * @return bool
		 */
		static function has_type( $type ){
			return ( array_key_exists( $type, self::$types ) && is_array( self::$types[ $type ] ) && count( self::$types[ $type ] ) > 0 );
		}


		/**
		 * Создает новый экземпляр инпута и возвращает его
		 * @param string      $type
		 * @param bool|string $id
		 * @param null        $value
		 * @return inputs\input
		 */
		public function create( $type = 'text', $id = false, $value = null ){
			$global_id = arrays::get_free_key( self::$inputs, $id );
			$class = false;
			if( $this->has_type( $type ) ){
				$classNames = array_reverse( self::$types[ $type ] );
				foreach( $classNames as $className ){
					if( !class_exists( $className ) ){
						console::warn( 'Класс для инпута не найден [' . $className . ']', true );
						continue;
					}
					///Make input
					$class = new $className( $id, $type );
					if( !$class instanceof inputs\input ){
						console::warn( sprintf( __( 'Class [%s] dosen\'t extends hw_input! For this class Assign heir:\n<?php class %s  extends hw_input{ ... }' ), $className ) );
						continue;
					}
					break;
				}
			}

			if( !$class instanceof inputs\input ){
				console::warn( sprintf( __( 'Type of input [%s] not found', 'hw-core-2' ), $type ), 0 );
				///Make default input
				$class = new inputs\input( $id, $type );
			}

			if( !is_null( $value ) ) $class->value = $value;
			$class->tag_add( 'data-global-id', $global_id );
			self::$inputs[ $global_id ] = $class;
			return self::$inputs[ $global_id ];
		}


		/**
		 * Зарегистрировать тип инпута
		 * @param string $type
		 * @param string $className
		 * @param int    $priority - приоритет определяет какой класс откроется
		 */
		public function register_type( $type = 'text', $className, $priority = 10 ){
			$priority = intval( $priority );
			if( !array_key_exists( $type, self::$types ) ){
				self::$types[ $type ] = [];
			}
			if( array_key_exists( $priority, self::$types[ $type ] ) ){
				$movedClassName = self::$types[ $type ][ $priority ];
				$this->register_type( $type, $priority + 1, $movedClassName );
			}
			self::$types[ $type ][ $priority ] = $className;
		}


	}