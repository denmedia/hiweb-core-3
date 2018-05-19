/**
 * Created by denmedia on 14.06.2017.
 */


jQuery(document).ready(function ($) {
    var hiweb_types_editor_timeout_change = {};
    var hiweb_types_editor_instance = {};
    var hiweb_types_editor_make = function (id) {
        hiweb_types_editor_instance[id] = tinymce.init({
            selector: '#' + id,
            menubar: false,
            plugins: 'directionality fullscreen image link media charmap hr lists textcolor colorpicker wordpress wpautoresize wpdialogs wpeditimage wpgallery wplink wptextpattern wpview',
            //toolbar3: "hr removeformat | subscript superscript | charmap emoticons | fullscreen | ltr rtl | restoredraft",
            //toolbar_items_size: 'small',
            toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent blockquote | link unlink image media | forecolor backcolor | removeformat | styleselect fontselect fontsizeselect | cut copy paste | undo redo",
            init_instance_callback: function (editor) {
                editor.on('KeyUp NodeChange Change', function (e) {
                    clearTimeout(hiweb_types_editor_timeout_change[id]);
                    var editor = this;
                    hiweb_types_editor_timeout_change[id] = setTimeout(function () {
                        $('#' + id).trigger('change').trigger('input').val(editor.getContent());
                    }, 2000);
                });
            }

        });
    };
    ///
    $('body').on('drag_stop', '.tmce-active', function (item) {
        var id = jQuery(this).find('textarea').attr('id');
        var editor = tinymce.get(id);
        editor.save();
        editor.remove();
    }).on('init_3', '.hiweb-field-editor', function () {
        var id = $(this).find('textarea').attr('id');
        setTimeout(function () {
            hiweb_types_editor_make(id);
        }, 200);
    });
    $('.hiweb-field-editor textarea').each(function () {
        var id = $(this).attr('id');
        hiweb_types_editor_make(id);
    });
});

