/**
 * Created by DenMedia on 25.10.2016.
 */

var hw_input_file = {

    init: function () {
        jQuery('body').on('click', '.hiweb-field-file [data-click="select"], .hiweb-field-image [data-click="select"]', function (e) {
            var current = jQuery(e.currentTarget).closest('[data-file-mime]');
            hw_input_file.event_click_select(current);
        });
        jQuery('body').on('click', '.hiweb-field-file [data-click="edit"], .hiweb-field-image [data-click="edit"]', function (e) {
            e.preventDefault();
            var current = jQuery(e.currentTarget).closest('[data-file-mime]');
            hw_input_file.edit_file(current);
        });
        jQuery('body').on('click', '.hiweb-field-file [data-click="deselect"], .hiweb-field-image [data-click="deselect"]', function (e) {
            e.preventDefault();
            var current = jQuery(e.currentTarget).closest('[data-file-mime]');
            hw_input_file.deselect_file(current);
        });
        jQuery('body').on('change', '.hiweb-field-file input[name]', function () {
            //console.info('!!!'); //todo детек изменения поля для сброса
        });
    },

    event_click_select: function (current) {
        var media_options = {
            title: 'Выбор файла',
            multiple: false,
            button: {text: 'Выбрать файл'}
        };
        if (current.is('.hiweb-field-image')) {
            media_options.library = {type: 'image'};
        }
        var gallery_window = wp.media(media_options);
        gallery_window.on('select', function () {
            hw_input_file.select_file(current, gallery_window.state().get('selection').first().toJSON());
        });
        gallery_window.open();
    },

    select_file: function (current, selection) {
        var input = current.find('input');
        var media_id = selection.id;

        //var file_preview = current.find('.thumbnail');
        input.val(media_id);
        current.find('[data-file-name]').html(selection.filename);
        //file_preview.css('background-file', 'url(' + url + ')');
        current.attr('data-has-file', '1');
        current.attr('data-file-mime', selection.mime);
        current.attr('data-file-image', selection.mime.indexOf('image') === 0 ? 'image' : 'file');
        if (selection.mime.indexOf('image') === 0) {
            current.find('.thumbnail').css('background-image', 'url(' + selection.url + ')');
        }
    },

    edit_file: function (current) {
        var input = current.find('input');
        var media_options = {
            title: 'Обновление файла',
            multiple: false,
            button: {text: 'Обновить файл'}
        };
        if (current.is('.hiweb-field-image')) {
            media_options.library = {type: 'image'};
        }
        var gallery_window = wp.media(media_options);
        gallery_window.on('open', function () {
            var selection = gallery_window.state().get('selection');
            ids = [input.val()];
            ids.forEach(function (id) {
                attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });
        });
        gallery_window.on('select', function () {
            hw_input_file.select_file(current, gallery_window.state().get('selection').first().toJSON());
        });
        gallery_window.open();
    },

    deselect_file: function (current) {
        var input = current.find('input').val('');
        var file_preview = current.find('.thumbnail');
        file_preview.css('background-file', 'none');
        current.attr('data-has-file', '0');
        current.attr('data-file-mime', '');
        current.attr('data-file-image', '');
        current.find('[data-file-name]').html('');
    }

};

jQuery(document).ready(hw_input_file.init);