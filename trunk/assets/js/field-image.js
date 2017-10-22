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
            console.info('!!!'); //todo детек изменения поля для сброса
        });
    },

    event_click_select: function (current) {
        var gallery_window = wp.media({
            title: 'Выбор изображения',
            library: {type: 'image'},
            multiple: false,
            button: {text: 'Select Image'}
        });
        gallery_window.on('select', function () {
            hiweb_field_image.select_image(current, gallery_window.state().get('selection').first().toJSON());
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
        image_preview.css('background-image', 'url(' + url + ')');
        current.attr('data-has-image', '1');
    },

    deselect_image: function (current) {
        var input = current.find('input').val('');
        var image_preview = current.find('.thumbnail');
        image_preview.css('background-image', 'none');
        current.attr('data-has-image', '0');
    }

};

jQuery(document).ready(hiweb_field_image.init);