/**
 * Created by DenMedia on 25.10.2016.
 */

var hiweb_field_image = {

    init: function () {
        jQuery('body').on('click', '.hiweb-field-image[data-has-image="0"]', function (e) {
            var current = jQuery(e.currentTarget).closest('.hiweb-field-image');
            hiweb_field_image.event_click_select(current);
        });
        jQuery('body').on('click', '.hiweb-field-image[data-has-image="1"]', function (e) {
            e.preventDefault();
            var current = jQuery(e.currentTarget).closest('.hiweb-field-image');
            hiweb_field_image.deselect_image(current);
        });
        jQuery('body').on('change', '.hiweb-field-image input[name]', function () {
            //console.info('детек изменения поля для сброса'); //todo детек изменения поля для сброса
        });
    },

    is_parent_repeat: function (current) {
        return jQuery(current).parent().is('[data-col]');
    },

    event_click_select: function (current) {
        var gallery_window = wp.media({
            title: 'Выбор изображения',
            library: {type: 'image'},
            multiple: hiweb_field_image.is_parent_repeat(current),
            button: {text: hiweb_field_image.is_parent_repeat(current) ? 'Выбрать Изображения' : 'Выбрать Изображение'}
        });
        gallery_window.on('select', function () {
            //hiweb_field_image.select_image(current, gallery_window.state().get('selection').first().toJSON());
            var index = 0;
            gallery_window.state().get('selection').map(function (attachment) {
                if(hiweb_field_image.is_parent_repeat(current)){
                    if(index === 0) {
                        hiweb_field_image.select_image(current, attachment.toJSON());
                    } else {
                        var repeat_root = current.closest('.hiweb-field-repeat');
                        hiweb_field_repeat.add_rows(repeat_root, false, 2, function(repeat_root, newLine){ hiweb_field_image.select_image(newLine.find('.hiweb-field-image'), attachment.toJSON()); });
                    }
                    index ++;
                } else {
                    hiweb_field_image.select_image(current, attachment.toJSON());
                }
            });
        });
        gallery_window.open();
    },

    select_image: function (current, selection) {
        var input = current.find('input');
        var image_preview = current.find('.thumbnail');
        switch ('object') {
            case typeof selection.sizes.medium:
                var url = selection.sizes.medium.url;
                break;
            case typeof selection.sizes.large:
                var url = selection.sizes.large.url;
                break;
            case typeof selection.sizes.thumbnail:
                var url = selection.sizes.thumbnail.url;
                break;
            case typeof selection.sizes.full:
                var url = selection.sizes.full.url;
                break;
            default:
                var url = false;
        }
        var media_id = selection.id;
        input.val(media_id);
        input.trigger('change');
        image_preview.css('background-image', 'url(' + url + ')');
        current.attr('data-has-image', '1');
    },

    deselect_image: function (current) {
        var input = current.find('input').val('');
        input.trigger('change');
        var image_preview = current.find('.thumbnail');
        image_preview.css('background-image', 'none');
        current.attr('data-has-image', '0');
    }

};

jQuery(document).ready(hiweb_field_image.init);