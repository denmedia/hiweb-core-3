/**
 * Created by DenMedia on 26.10.2016.
 */

var hiweb_field_images = {

    selector_root: '.hiweb-field-images',

    init: function () {
        jQuery('body').on('click', '.hiweb-field-images [data-ctrl-sub="left"], .hiweb-field-images [data-ctrl-sub="right"]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_add(hiweb_field_images.get_root(this), 'right' === jQuery(this).attr('data-ctrl-sub'));
        });
        jQuery('body').on('click', '.hiweb-field-images [data-click-remove]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_remove(jQuery(this).closest('[data-image-id]'));
        });
        jQuery('body').on('click', '.hiweb-field-images [data-click-reverse]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_reverse(this);
        });
        jQuery('body').on('click', '.hiweb-field-images [data-click-random]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_random(this);
        });
        jQuery('body').on('click', '.hiweb-field-images [data-click-clear]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_clear(this);
        });
        jQuery('body').on('click', '.hiweb-field-images [data-click-add]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_add(hiweb_field_images.get_root(this), true);
        });
        jQuery('body').on('click', '.hiweb-field-images [data-click-edit]', function (e) {
            e.preventDefault();
            hiweb_field_images.event_click_edit(this);
        });
        hiweb_field_images.make_sortable();
        hiweb_field_images.make_dropdown();
    },

    shuffleElements: function ($elements) {
        var i, index1, index2, temp_val;

        var count = $elements.length;
        var $parent = $elements.parent();
        var shuffled_array = [];


        // populate array of indexes
        for (i = 0; i < count; i++) {
            shuffled_array.push(i);
        }

        // shuffle indexes
        for (i = 0; i < count; i++) {
            index1 = (Math.random() * count) | 0;
            index2 = (Math.random() * count) | 0;

            temp_val = shuffled_array[index1];
            shuffled_array[index1] = shuffled_array[index2];
            shuffled_array[index2] = temp_val;
        }

        // apply random order to elements
        $elements.detach();
        for (i = 0; i < count; i++) {
            $parent.append($elements.eq(shuffled_array[i]));
        }
    },

    get_root: function (e) {
        return jQuery(e).closest('.hiweb-field-images');
    },

    get_source: function (e) {
        var root = hiweb_field_images.get_root(e);
        return root.find('[data-source]');
    },

    get_list_root: function (e) {
        var root = hiweb_field_images.get_root(e);
        return root.find('[data-images-wrap]');
    },

    get_list_images: function (e) {
        return hiweb_field_images.get_list_root(e).find('> [data-image-id]');
    },

    make_dropdown: function () {
        jQuery(hiweb_field_images.selector_root + ' .dropdown')
            .dropdown({
                action: 'hide'
            });
    },

    make_sortable: function () {
        jQuery('.hiweb-field-images [data-images-wrap]').sortable({
            distance: 5,
            cursor: 'move',
            handle: '.overlay, .button',
            helper: 'original',
            revert: true
            //forcePlaceholderSize: true
        });
    },

    event_click_add: function (root, add_right) {
        var gallery_window = wp.media({
            title: 'Выбор изображения',
            library: {type: 'image'},
            multiple: true,
            button: {text: 'Выбрать файлы'}
        });
        gallery_window.on('select', function () {
            gallery_window.state().get('selection').map(function (attachment) {
                hiweb_field_images.select_image(root, add_right, attachment.toJSON());
            });
            hiweb_field_images.make_sortable();
            //hiweb_field_images.refresh_view(current);
        });
        gallery_window.open();
    },

    select_image: function (root, add_right, selection) {
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
        var source = hiweb_field_images.get_source(root).clone();
        source.find('img').attr('src', url);
        source.removeAttr('data-source').attr('data-image-id', selection);
        var input = source.find('input');
        input.val(selection.id).attr('name', input.attr('data-name')).removeAttr('data-name');
        source.attr('id', root.attr('data-id'));
        source.attr('data-tooltip', selection.filename);
        if (add_right) {
            hiweb_field_images.get_list_root(root).append(source);
        } else {
            hiweb_field_images.get_list_root(root).prepend(source);
        }
        var items_count = hiweb_field_images.get_list_root(root).find('> li').length - 1;
        root.attr('data-images-count', items_count);
        root.find('[data-images-count]').html(items_count);
    },

    event_click_edit: function (item) {
        item = jQuery(item).closest('[data-image-id]');
        var image_id = item.attr('data-image-id');
        var media_options = {
            title: 'Обновление файла',
            multiple: false,
            button: {text: 'Обновить файл'},
            library: {type: 'image'}
        };
        var gallery_window = wp.media(media_options);
        gallery_window.on('open', function () {
            var selection = gallery_window.state().get('selection');
            attachment = wp.media.attachment(image_id);
            attachment.fetch();
            selection.add(attachment ? [attachment] : []);
        });
        gallery_window.on('select', function () {
            gallery_window.state().get('selection').map(function (attachment) {
                var new_id = attachment.toJSON().id;
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
            });
        });
        gallery_window.open();
    },

    event_click_remove: function (image) {
        jQuery(image).hide('slow', function () {
            jQuery(this).remove();
            hiweb_field_images.refresh_view(hiweb_field_images.get_root(this));
        });
    },

    event_click_reverse: function (e) {
        var list = hiweb_field_images.get_list_root(e);
        list.children().each(function (i, li) {
            list.prepend(li)
        })
    },

    event_click_random: function (e) {
        var list = hiweb_field_images.get_list_root(e);
        hiweb_field_images.shuffleElements(list.find('li[data-image-id]'));
    },

    event_click_clear: function (e) {
        if (confirm('Remove all images?')) {
            hiweb_field_images.get_list_images(e).each(function () {
                hiweb_field_images.event_click_remove(this);
            });
            var root = hiweb_field_images.get_root(e);
            root.attr('data-images-count', 0);
            root.find('[data-images-count]').attr('data-images-count', 0);
        }
    },


    refresh_view: function (root) {
        //
    }

};


jQuery(document).ready(hiweb_field_images.init);