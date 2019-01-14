jQuery(document).ready(function ($) {

    var hiweb_field_content = {

        root_selector: '.hiweb-field-content',
        tinymces: {},


        init: function () {
            if (window.wpActiveEditor) {

            }
            tinyMCEPreInit = {
                baseURL: $(hiweb_field_content.root_selector).attr('data-baseurl') + "/wp-includes/js/tinymce",
                suffix: ".min",
                mceInit: {},
                qtInit: {},
                dragDropUpload: true,
                ref: {plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview", theme: "modern", language: "ru"},
                load_ext: function (url, lang) {
                    var sl = tinymce.ScriptLoader;
                    sl.markDone(url + '/langs/' + lang + '.js');
                    sl.markDone(url + '/langs/' + lang + '_dlg.js');
                }
            };
            $(hiweb_field_content.root_selector).each(function () {
                hiweb_field_content.make(this);
            });
            ////WP Editor Mode Switch
            if ( typeof tinymce !== 'undefined' ) {
                if ( tinymce.Env.ie && tinymce.Env.ie < 11 ) {
                    tinymce.$( '.wp-editor-wrap ' ).removeClass( 'tmce-active' ).addClass( 'html-active' );
                    return;
                }

                for ( id in tinyMCEPreInit.mceInit ) {
                    init = tinyMCEPreInit.mceInit[id];
                    $wrap = tinymce.$( '#wp-' + id + '-wrap' );

                    if ( ( $wrap.hasClass( 'tmce-active' ) || ! tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) && ! init.wp_skip_init ) {
                        tinymce.init( init );

                        if ( ! window.wpActiveEditor ) {
                            window.wpActiveEditor = id;
                        }
                    }
                }
            }

            if ( typeof quicktags !== 'undefined' ) {
                for ( id in tinyMCEPreInit.qtInit ) {
                    quicktags( tinyMCEPreInit.qtInit[id] );

                    if ( ! window.wpActiveEditor ) {
                        window.wpActiveEditor = id;
                    }
                }
            }
        },

        make: function (element) {
            element = $(element);
            var id = element.attr('data-rand-id');
            if (hiweb_field_content.tinymces[id]) {
                tinymce.get(id).destroy();
                hiweb_field_content.make_tinymce(id);
            } else {
                hiweb_field_content.make_tinymce(id);

                if (typeof quicktags !== 'undefined') {
                    var settings = Object.assign({}, window.hiweb_field_content_qtags_default);
                    settings.id = id;
                    quicktags(settings);
                }
            }
        },

        make_tinymce: function (id) {
            var settings = Object.assign({}, window.hiweb_field_content_tinymce_default);
            settings.selector = '#' + id;
            hiweb_field_content.tinymces[id] = tinymce.init(settings);
            ////WP Editor Mode Switch
            $(document).on('click', '#' + id + '-tmce', function(){
                var $wrap = $('#wp-' + id + '-wrap');
                var $tmce = $('#wp-' + id + '-wrap .mce-container');
                var $html = $('#wp-' + id + '-wrap .wp-editor-area');
                tinymce.get(id).setContent( $html.val() );
                $wrap.addClass('tmce-active').removeClass('html-active');
                $tmce.show();
                $html.hide();
            });
            $(document).on('click', '#' + id + '-html', function(){
                var $wrap = $('#wp-' + id + '-wrap');
                var $tmce = $('#wp-' + id + '-wrap .mce-container');
                var $html = $('#wp-' + id + '-wrap .wp-editor-area');
                $html.val( tinymce.get(id).getContent() );
                $wrap.addClass('html-active').removeClass('tmce-active');
                $tmce.hide();
                $html.show();
            });
        }
    };

    hiweb_field_content.init();
    // $('body').on('init_3 drag_stop', '.hiweb-field-content', function () {
    //     hiweb_field_content.make(this);
    // });
    jQuery('body').on('hiweb-field-repeat-added-new-row-fadein hiweb-field-repeat-drag-stop', '[data-col]', function (e, col, row, root) {
        col.find('.hiweb-field-content').each(function () {
            hiweb_field_content.make(this);
        });
    });

});