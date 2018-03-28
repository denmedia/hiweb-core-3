/**
 * Created by denmedia on 14.06.2017.
 */


jQuery(document).ready(function ($) {
    ///
    $('body').on('drag_stop', '.tmce-active', function (item) {
        var id = jQuery(this).find('textarea').attr('id');
        var editor = tinymce.get(id);
        editor.save();
        editor.remove();
    }).on('init_3', '.hiweb-field-editor', function () {
        var id = $(this).find('textarea').attr('id');
        setTimeout(function () {
            tinymce.init({
                selector: '#' + id
                //toolbar: 'fontsizeselect'
            });
        }, 200);
    });
    $('.hiweb-field-editor textarea').each(function () {
        var id = $(this).attr('id');
        tinymce.init({
            selector: '#' + id
            //toolbar: 'fontsizeselect'
        });

    });
});

