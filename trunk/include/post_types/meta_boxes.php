<?php

	namespace hiweb\post_types;


	class meta_boxes{

		/** @var string */
		protected $_id;
		protected $title = '&nbsp;';
		protected $callback;
		protected $screen = [];
		protected $context = 'normal'; //normal, advanced или side
		protected $priority = 'default';
		protected $callback_args;
		protected $callback_save_post;

		/** @var string */
		protected $fields_prefix = 'hw_wp_meta_boxes_';


		public function __construct( $id ){
			$this->_id = $id;
			$this->my_hooks();
		}


		/**
		 * Возвращает ID текущего мета-бокса
		 * @return string
		 */
		public function id(){
			return $this->_id;
		}


		/**
		 * @param $title
		 * @return $this
		 */
		public function title( $title = null ){
			if( is_null( $title ) ) return $this->title; else $this->title = $title;
			return $this;
		}


		/**
		 * @param $callback
		 * @return $this
		 */
		public function callback( $callback = null ){
			if( is_null( $callback ) ) return $this->callback; else $this->callback = $callback;
			return $this;
		}


		/**
		 * @param null $screen
		 * @param bool $append
		 * @return $this
		 */
		public function screen( $screen = null, $append = true ){
			$this->screen = is_array( $this->screen ) ? $this->screen : [ $this->screen ];
			if( is_null( $screen ) ){
				return $this->screen;
			} else {
				if( !is_array( $screen ) ) $screen = [ $screen ];
				$this->screen = $append ? $this->screen + $screen : $screen;
			}
			return $this;
		}


		/**
		 * @param null $context
		 * @return $this
		 */
		public function context( $context = null ){
			if( is_null( $context ) ) return $this->context; else $this->context = $context;
			return $this;
		}


		/**
		 * @param null $priority
		 * @return $this
		 */
		public function priority( $priority = null ){
			if( is_null( $priority ) ) return $this->priority; else $this->priority = $priority;
			return $this;
		}


		/**
		 * @param null $callback_args
		 * @return $this
		 */
		public function callback_args( $callback_args = null ){
			if( is_null( $callback_args ) ) return $this->callback_args; else $this->callback_args = $callback_args;
			return $this;
		}


		/**
		 * @param null $callback
		 * @return $this
		 */
		public function callback_save_post( $callback = null ){
			if( is_null( $callback ) ) return $this->callback_save_post; else $this->callback_save_post = $callback;
			return $this;
		}


		public function __call( $name, $arguments ){
			switch( $name ){
				case 'add_action_add_meta_box':
					$this->add_action_add_meta_box( $arguments[0], isset( $arguments[1] ) ? $arguments[1] : null );
					break;
				case 'add_action_save_post':
					$this->add_action_save_post( $arguments[0] );
					break;
				case 'generate_meta_box':
					$this->generate_meta_box( $arguments[0], $arguments[1] );
					break;
			}
		}


		/**
		 * @return hw_input[]
		 */
		public function fields(){
			return $this->fields;
		}


		/**
		 *
		 */
		protected function my_hooks(){
			add_action( 'add_meta_boxes', [ $this, 'add_action_add_meta_box' ], 10, 2 );
			add_action( 'save_post', [ $this, 'add_action_save_post' ], 10, 2 );
		}


		protected function add_action_add_meta_box( $post_type, $post = null ){
			add_meta_box( $this->_id, $this->title, is_null( $this->callback ) ? [ $this, 'generate_meta_box' ] : $this->callback, $this->screen, $this->context, $this->priority, $this->callback_args );
		}


		protected function add_action_save_post( $post_id = null ){
			if( !is_null( $this->callback_save_post ) ) return call_user_func( $this->callback_save_post, $post_id ); else {
				if( is_array( $this->fields ) ) foreach( $this->fields as $id => $field ){
					update_post_meta( $post_id, $field->name(), $_POST[ $field->name() ] );
				}
			}
			return $post_id;
		}


		protected function generate_meta_box( $post, $meta_box ){
			if( is_array( $this->fields ) ) foreach( $this->fields as $id => $field ){
				if( $post instanceof WP_Post ) $field->value( get_post_meta( $post->ID, $field->name(), true ) );
				?>
				<p>
					<strong><?php echo $field->label(); ?></strong>
					<label class="screen-reader-text" for="<?php echo $id ?>"><?php echo $field->label() ?></label>
				</p>
				<?php $field->the();
			} else ?><span>no fields</span><?php
		}
	}