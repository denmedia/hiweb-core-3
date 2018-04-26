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
            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",
            toolbar_items_size: 'small',
            init_instance_callback: function (editor) {
                editor.on('KeyUp NodeChange Change', function (e) {
                    clearTimeout(hiweb_types_editor_timeout_change[id]);
                    var editor = this;
                    hiweb_types_editor_timeout_change[id] = setTimeout(function () {
                        console.info('change...['+id+']'); //todo-
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

