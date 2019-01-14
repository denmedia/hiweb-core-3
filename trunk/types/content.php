<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 03.08.2018
	 * Time: 15:43
	 */

	namespace {


		if( !function_exists( 'add_field_content' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\content\field
			 */
			function add_field_content( $id ){
				$new_field = new hiweb\fields\types\content\field( $id );
				hiweb\fields::register_field( $new_field );

				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\content {


		use function hiweb\css;
		use function hiweb\js;
		use hiweb\paths;
		use hiweb\strings;
		use hiweb\urls;


		class field extends \hiweb\fields\field{

			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


			protected function get_value_class(){
				return __NAMESPACE__ . '\\value';
			}

		}


		class input extends \hiweb\fields\input{

			static $header_print = false;


			public function __construct( \hiweb\fields\field $field, \hiweb\fields\value $value ){
				parent::__construct( $field, $value );
			}


			protected static function print_mceInit(){
				if( !self::$header_print ){
					self::$header_print = true;
					css( WPINC . '/css/editor.min.css' );
					global $tinymce_version, $concatenate_scripts, $compress_scripts;

					if( !isset( $concatenate_scripts ) ){
						script_concat_settings();
					}

					$suffix = SCRIPT_DEBUG ? '' : '.min';
					$version = 'ver=' . $tinymce_version;
					$baseurl = includes_url( 'js/tinymce' );

					$has_custom_theme = false;

					$compressed = $compress_scripts && $concatenate_scripts && isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) && false !== stripos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) && !$has_custom_theme;

					$mce_suffix = false !== strpos( get_bloginfo( 'version' ), '-src' ) ? '' : '.min';

					if( $compressed ){
						$tinymce = js( "{$baseurl}/wp-tinymce.php?c=1&$version", [ 'jquery' ] )->handle();
					} else {
						$tinymce = js( "{$baseurl}/tinymce{$mce_suffix}.js?$version", [ 'jquery' ] )->handle();
						js( "{$baseurl}/plugins/compat3x/plugin{$suffix}.js?$version" );
					}
					add_action( 'admin_init', 'wp_enqueue_media' );
					$lang = js( HIWEB_DIR_JS . '/tinymce-language-ru.min.js', [ $tinymce ] )->handle();
					js( paths::get( WPINC . '/js/quicktags.min.js' )->get(), [ $lang ] );
					js( HIWEB_DIR_JS . '/field-content.js', [ 'jquery', 'editor', $tinymce ] );
					?>
					<script type="text/javascript">
                        window.hiweb_field_content_tinymce_default = {
                            theme: "modern",
                            skin: "lightgray",
                            language: "ru",
                            formats: {
                                alignleft: [{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign: "left"}}, {selector: "img,table,dl.wp-caption", classes: "alignleft"}],
                                aligncenter: [{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign: "center"}}, {selector: "img,table,dl.wp-caption", classes: "aligncenter"}],
                                alignright: [{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign: "right"}}, {selector: "img,table,dl.wp-caption", classes: "alignright"}],
                                strikethrough: {inline: "del"}
                            },
                            relative_urls: false,
                            remove_script_host: false,
                            convert_urls: false,
                            browser_spellcheck: true,
                            fix_list_elements: true,
                            entities: "38,amp,60,lt,62,gt",
                            entity_encoding: "raw",
                            keep_styles: false,
                            //cache_suffix: "wp-mce-4800-20180716",
                            resize: false,
                            menubar: false,
                            branding: false,
                            preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
                            end_container_on_empty_block: true,
                            wpeditimage_html5_captions: true,
                            wp_lang_attr: "ru-RU",
                            wp_keep_scroll_position: true,
                            wp_shortcut_labels: {
                                "Heading 1": "access1",
                                "Heading 2": "access2",
                                "Heading 3": "access3",
                                "Heading 4": "access4",
                                "Heading 5": "access5",
                                "Heading 6": "access6",
                                "Paragraph": "access7",
                                "Blockquote": "accessQ",
                                "Underline": "metaU",
                                "Strikethrough": "accessD",
                                "Bold": "metaB",
                                "Italic": "metaI",
                                "Code": "accessX",
                                "Align center": "accessC",
                                "Align right": "accessR",
                                "Align left": "accessL",
                                "Justify": "accessJ",
                                "Cut": "metaX",
                                "Copy": "metaC",
                                "Paste": "metaV",
                                "Select all": "metaA",
                                "Undo": "metaZ",
                                "Redo": "metaY",
                                "Bullet list": "accessU",
                                "Numbered list": "accessO",
                                "Insert\/edit image": "accessM",
                                "Remove link": "accessS",
                                "Toolbar Toggle": "accessZ",
                                "Insert Read More tag": "accessT",
                                "Insert Page Break tag": "accessP",
                                "Distraction-free writing mode": "accessW",
                                "Keyboard Shortcuts": "accessH"
                            },
                            content_css: "<?=HIWEB_URL_VENDORS?>/wp-default.min.css",
                            plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
                            //selector: "#content",
                            wpautop: true,
                            indent: false,
                            toolbar1: "formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,dfw,wp_adv",
                            toolbar2: "strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
                            toolbar3: "",
                            toolbar4: "",
                            //tabfocus_elements: "content-html,save-post",
                            //body_class: "content post-type-page post-status-publish page-template-default locale-ru-ru",
                            wp_autoresize_on: true,
                            add_unload_trigger: false
                        };
                        window.hiweb_field_content_qtags_default = {buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"};
                        if (!window.tinyMCEPreInit) {
                            window.tinyMCEPreInit = {
                                baseURL: "<?=urls::root() . '/' . WPINC ?>/js/tinymce",
                                suffix: ".min",
                                dragDropUpload: true,
                                ref: {plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview", theme: "modern", language: "ru"},
                                load_ext: function (url, lang) {
                                    var sl = tinymce.ScriptLoader;
                                    sl.markDone(url + '/langs/' + lang + '.js');
                                    sl.markDone(url + '/langs/' + lang + '_dlg.js');
                                }
                            };
                        }
					</script>
					<?php
				}
			}


			public function html(){
				ob_start();
				$rand_id = strings::rand( 10 );
				$this->attributes['data-rand-id'] = $rand_id;
				$this->attributes['id'] = $rand_id;
				add_action( 'in_admin_footer', function(){
					self::print_mceInit();
				} );
				?>
			<div class="hiweb-field-content" data-rand-id="<?= $rand_id ?>" data-baseurl="<?= urls::root() ?>">
				<div id="wp-<?= $rand_id ?>-wrap" class="wp-core-ui wp-editor-wrap tmce-active has-dfw">
					<div id="wp-<?= $rand_id ?>-editor-tools" class="wp-editor-tools hide-if-no-js">
						<div id="wp-<?= $rand_id ?>-media-buttons" class="wp-media-buttons">
							<button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="<?= $rand_id ?>"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
						</div>
						<div class="wp-editor-tabs">
							<button type="button" id="<?= $rand_id ?>-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="<?= $rand_id ?>">Визуально</button>
							<button type="button" id="<?= $rand_id ?>-html" class="wp-switch-editor switch-html" data-wp-editor-id="<?= $rand_id ?>">Текст</button>
						</div>
					</div>
					<div id="wp-<?= $rand_id ?>-editor-container" class="wp-editor-container">
						<div id="qt_<?= $rand_id ?>_toolbar" class="quicktags-toolbar"></div>
						<textarea class="wp-editor-area" <?= $this->sanitize_attributes() ?> style="height: 300px" autocomplete="off" cols="40" data-rand-id="<?= $rand_id ?>"><?= $this->VALUE()->get() ?></textarea></div>
				</div>
				<?php

				return ob_get_clean();
			}
		}


		class value extends \hiweb\fields\value{

			public function get_content(){
				return apply_filters( 'the_content', $this->get_sanitized() );
			}


		}
	}