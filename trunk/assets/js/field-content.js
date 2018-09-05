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
            //

        },

        make_tinymce: function (id) {
            var settings = Object.assign({}, window.hiweb_field_content_tinymce_default);
            settings.selector = '#' + id;
            hiweb_field_content.tinymces[id] = tinymce.init(settings);
        }

    };

    hiweb_field_content.init();
    $('body').on('init_3 drag_stop', '.hiweb-field-content', function () {
        hiweb_field_content.make(this);
    });

});