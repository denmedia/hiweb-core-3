/**
 * Created by DenMedia on 21.11.2016.
 */
var hiweb_field_images_advanced = {

    selected_main_element: null,

    init: function () {
        jQuery('body').on('click', '.hiweb-field-images-advanced [data-ctrl-sub="left"], .hiweb-field-images-advanced [data-ctrl-sub="right"]', function (e) {
            e.preventDefault();
            hiweb_field_images_advanced.event_click_add(hiweb_field_images_advanced.get_root(this), 'right' === jQuery(this).attr('data-ctrl-sub'));
        });
        jQuery('body').on('click', '.hiweb-field-images-advanced [data-click-edit]', function (e) {
            e.preventDefault();
            hiweb_field_images_advanced.event_click_advance(jQuery(this).closest('[data-image-id]'));
        });
        jQuery('body').on('click', '.hiweb-field-images-advanced [data-click-remove]', function (e) {
            e.preventDefault();
            hiweb_field_images_advanced.event_click_remove(jQuery(this).closest('[data-image-id]'));
        });
        jQuery('body').on('click', '.hiweb-field-images-advanced [data-ctrl] [data-click-reverse]', function (e) {
            e.preventDefault();
            hiweb_field_images_advanced.event_click_reverse(this);
        });
        jQuery('body').on('click', '.hiweb-field-images-advanced [data-ctrl] [data-click-clear]', function (e) {
            e.preventDefault();
            hiweb_field_images_advanced.event_click_clear(this);
        });
        jQuery('body').on('click', '.hiweb-field-images-advanced [data-ctrl] [data-click-add]', function (e) {
            e.preventDefault();
            hiweb_field_images_advanced.event_click_add(hiweb_field_images_advanced.get_root(this), true);
        });
        hiweb_field_images_advanced.make_sortable();
        hiweb_field_images_advanced.make_advanced_dialog();
    },

    get_root: function (e) {
        return jQuery(e).closest('.hiweb-field-images-advanced');
    },

    get_source: function (e) {
        var root = hiweb_field_images_advanced.get_root(e);
        console.info( root ); //todo-
        return root.find('[data-source]');
    },

    get_list_root: function (e) {
        var root = hiweb_field_images_advanced.get_root(e);
        return root.find('.items');
    },

    get_list_images: function (e) {
        return hiweb_field_images_advanced.get_list_root(e).find('> [data-image-id]');
    },

    /**
     * Return advanced form root lement
     * @param element
     * @returns {*}
     */
    get_advanced_form_root: function (element) {
        if (hiweb_field_images_advanced.get_root(jQuery(element)).length > 0) {
            var form_id = hiweb_field_images_advanced.get_root(jQuery(element)).attr('data-advanced-form-id');
            return jQuery('#' + form_id);
        } else {
            return jQuery(element).closest('.hiweb-fields-images-advanced-form');
        }
    },

    /**
     * Get advanced items root element
     * @param e
     * @returns {*}
     */
    get_advanced_items_root: function (e) {
        return jQuery(e).closest('.hiweb-fields-images-advanced-form').find('[data-images]');
    },

    /**
     * Return source advanced item element
     * @param e
     */
    get_advanced_source: function (e) {
        return jQuery(e).closest('.hiweb-fields-images-advanced-form').find('[data-advanced-source]');
    },

    make_sortable: function () {
        jQuery('.hiweb-field-images-advanced .attachments .items').sortable({
            distance: 5,
            helper: 'clone',
            revert: true
        });
    },

    make_advanced_sortable: function (e) {
        hiweb_field_images_advanced.get_advanced_items_root(e).sortable({
            distance: 5,
            helper: 'clone',
            revert: true
        });
    },

    make_advanced_dialog: function (e) {
        jQuery('.hiweb-fields-images-advanced-form').removeClass('hidden').dialog({
            title: 'Прикрепленные изображения',
            dialogClass: 'wp-dialog',
            autoOpen: false,
            draggable: false,
            width: '90%',
            height: 'auto',
            modal: true,
            resizable: false,
            closeOnEscape: true,
            position: {
                my: "center",
                at: "center",
                of: window
            },
            open: function () {
                // close dialog by clicking the overlay behind it
                $('.ui-widget-overlay').bind('click', function () {
                    jQuery('.hiweb-fields-images-advanced-form').dialog('close');
                });
            },
            create: function () {
                // style fix for WordPress admin
                $('.ui-dialog-titlebar-close').addClass('ui-button').on('click', function () {
                    var items = jQuery(this).closest('[aria-describedby="images-advanced-form"]').find('[data-advanced-item]').not('[data-advanced-source],[data-image-empty]');
                    hiweb_field_images_advanced.selected_main_element.find('[data-advanced-items]').append(items);
                });
            }
        });
        //Add button
        jQuery('body')
            .on('click', '[data-click-advance-add]', hiweb_field_images_advanced.event_click_advanced_add)
            .on('click', '[data-click-advance-update]', hiweb_field_images_advanced.event_click_advanced_update)
            .on('click', '[data-advanced-item-click-remove]', function () {
                jQuery(this).closest('[data-advanced-item]').remove();
            })
            .on('click', '[data-click-advance-cancel]', function () {
                jQuery('.hiweb-fields-images-advanced-form').dialog('close');
                var items = hiweb_field_images_advanced.get_advanced_items_root(this).find('[data-advanced-item]').not('[data-advanced-source],[data-image-empty]');
                hiweb_field_images_advanced.selected_main_element.find('[data-advanced-items]').append(items);
            });
            // .on('click', '.ui-dialog-titlebar-close', function () {
            //     jQuery('.hiweb-fields-images-advanced-form').dialog('close');
            //     var items = jQuery(this).closest('[aria-describedby="images-advanced-form"]').find('[data-advanced-item]').not('[data-advanced-source],[data-image-empty]');
            //     hiweb_field_images_advanced.selected_main_element.find('[data-advanced-items]').append(items);
            // });
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
                hiweb_field_images_advanced.select_image(root, add_right, attachment.toJSON());
            });
            hiweb_field_images_advanced.make_sortable();
            //hiweb_field_images_advanced.refresh_view(current);
        });
        gallery_window.open();
    },

    //Клик "добавить файл" в форме
    event_click_advanced_add: function () {
        var $this = jQuery(this);
        var gallery_window = wp.media({
            title: 'Выбор изображения',
            library: {type: 'image'},
            multiple: true,
            button: {text: 'Выбрать файлы'}
        });
        gallery_window.on('select', function () {
            gallery_window.state().get('selection').map(function (attachment) {
                var item = attachment.toJSON();
                var attachment_id = item.id;
                var image_src = item.url;
                var size_names = ['large', 'medium', 'thumbnail', 'full'];
                for (var key in item.sizes) {
                    if (size_names.indexOf(key) > -1) {
                        image_src = item.sizes[key].url;
                        break;
                    }
                }
                var images_root = hiweb_field_images_advanced.get_advanced_items_root($this);
                var image = hiweb_field_images_advanced.get_advanced_source($this).clone().removeAttr('data-advanced-source');
                image.find('img').attr('src', image_src);
                image.find('input').attr('name', images_root.attr('data-name'));
                image.find('input').val(attachment_id);
                hiweb_field_images_advanced.get_advanced_items_root($this).append(image);
            });
            ///MAKE ADVANCE SORTABLE
            hiweb_field_images_advanced.make_advanced_sortable($this);
        });
        gallery_window.open();
        hiweb_field_images_advanced.make_advanced_sortable($this);
    },

    //Клик "обновить" в форме
    event_click_advanced_update: function (e) {
        var form_root = hiweb_field_images_advanced.get_advanced_form_root(this);
        form_root.dialog('close');
        var items = hiweb_field_images_advanced.get_advanced_items_root(this).find('[data-advanced-item]').not('[data-advanced-source],[data-image-empty]');
        hiweb_field_images_advanced.selected_main_element.find('[data-advanced-items]').append(items);
    },

    //Клик на главном изображении "адванц"
    event_click_advance: function (image) {
        hiweb_field_images_advanced.selected_main_element = image;
        var modal_id = jQuery(image).closest('[data-advanced-form-id]').attr('data-advanced-form-id');
        var image_advance_items_root = image.find('[data-advanced-items]');
        var advanced_name = image_advance_items_root.attr('data-name');
        var data_images_root = $('#' + modal_id).find('[data-images]');
        var items = image.find('[data-advanced-items] li').detach();
        ///
        $('#' + modal_id).dialog('open');
        ///
        data_images_root.attr('data-name', advanced_name).find('[data-advanced-item]').not('[data-advanced-source],[data-image-empty]').remove();
        data_images_root.attr('data-name', advanced_name).append(items);
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
        var source = hiweb_field_images_advanced.get_source(root).clone();
        source.find('img').attr('src', url);
        source.removeAttr('data-source').attr('data-image-id', selection);
        var input = source.find('input');
        input.val(selection.id).attr('name', input.attr('data-name'));
        source.attr('id', root.attr('data-id'));
        if (add_right) {
            hiweb_field_images_advanced.get_list_root(root).append(source);
        } else {
            hiweb_field_images_advanced.get_list_root(root).prepend(source);
        }
    },

    event_click_edit: function (image) {
        var advanced_form = hiweb_field_images_advanced.get_advanced_form_root(image);
        advanced_form.dialog('open');
        hiweb_field_images_advanced.refresh_view(hiweb_field_images_advanced.get_root(this));
    },

    event_click_remove: function (image) {
        jQuery(image).hide('slow', function () {
            jQuery(this).remove();
            hiweb_field_images_advanced.refresh_view(hiweb_field_images_advanced.get_root(this));
        });
    },

    event_click_reverse: function (e) {
        var list = hiweb_field_images_advanced.get_list_root(e);
        list.children().each(function (i, li) {
            list.prepend(li)
        })
    },

    event_click_clear: function (e) {
        if (confirm('Remove all images?')) {
            hiweb_field_images_advanced.get_list_images(e).each(function () {
                hiweb_field_images_advanced.event_click_remove(this);
            });
        }
    },


    refresh_view: function (root) {
        //
    }

};


jQuery(document).ready(hiweb_field_images_advanced.init);