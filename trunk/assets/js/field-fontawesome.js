jQuery(document).ready(function ($) {

    //$('.my').iconpicker();

    var hiweb_field_fontawesome = {

        modal_id: 'hiweb-field-fontawesome-modal',
        modal_element: null,
        icons: [],


        init: function () {
            var input = $('.hiweb-field-fontawesome');
            // var offset = input.offset();
            // if (offset !== undefined && offset.hasOwnProperty('top')) {
            //     if (offset.top < 250) input.attr('data-placement', 'bottom');
            // }
            input.each(hiweb_field_fontawesome.make);
        },

        make: function () {
            var root_element = $(this);
            root_element.on('click', '[data-click]', function (e) {
                hiweb_field_fontawesome.make_modal(root_element);
                e.preventDefault();
                if (hiweb_field_fontawesome.modal_element === null || hiweb_field_fontawesome.modal_element.length === 0) {
                    console.error('Окно диалога не создано...');
                } else {
                    hiweb_field_fontawesome.modal_element.attr('data-rand-id', root_element.attr('data-rand-id')).dialog('open');
                }
            }).on('change keyup', 'input[name]', function () {
                root_element.find('.input-group-addon').html('').append('<i class="' + $(this).val() + '"></i>');
            });
        },

        make_modal: function (root_element) {
            if (hiweb_field_fontawesome.modal_element === null) {
                hiweb_field_fontawesome.modal_element = $('<div/>').attr('id', hiweb_field_fontawesome.modal_id).addClass('hiweb-field-fontawesome-modal-icons-wrap');
                ///
                if (typeof ___FONT_AWESOME___ === 'object' && ___FONT_AWESOME___.hasOwnProperty('styles')) {
                    for (var prefix in ___FONT_AWESOME___.styles) {
                        var sub_icons = ___FONT_AWESOME___.styles[prefix];
                        for (var index in sub_icons) {
                            var icon_class = prefix + ' fa-' + index;
                            var icon_element = $('<div class="item" data-click="' + icon_class + '"><i class="' + icon_class + '"></i></div>').on('click', function () {
                                var data_rand_id = hiweb_field_fontawesome.modal_element.attr('data-rand-id');
                                var icon_class = $(this).attr('data-click');
                                $('input[data-rand-id="' + data_rand_id + '"]').val(icon_class);
                                $('.hiweb-field-fontawesome[data-rand-id="' + data_rand_id + '"] .input-group-addon').html('').append('<i class="' + icon_class + '"></i>');
                                hiweb_field_fontawesome.modal_element.dialog('close');
                            });
                            hiweb_field_fontawesome.icons.push(icon_class);
                            hiweb_field_fontawesome.modal_element.append(icon_element);
                        }
                    }
                } else {
                    console.error('hiweb/field/fontawesome: FontAwesome не содержит список иконок');
                }
                ///
                hiweb_field_fontawesome.modal_element.append(hiweb_field_fontawesome.modal_element);
                $('body').append(hiweb_field_fontawesome.modal_element);

                hiweb_field_fontawesome.modal_element.dialog({
                    title: root_element.attr('data-dialog-title'),
                    dialogClass: 'wp-dialog',
                    autoOpen: false,
                    draggable: false,
                    width: 'auto',
                    modal: true,
                    resizable: false,
                    closeOnEscape: true,
                    closeText: 'Закрыть',
                    minHeight: 480,
                    minWidth: 750,
                    maxHeight: 400,
                    maxWidth: 600,
                    position: {
                        my: "center",
                        at: "center",
                        of: window
                    },
                    open: function () {
                        // close dialog by clicking the overlay behind it
                        $('.ui-widget-overlay').bind('click', function () {
                            $('#my-dialog').dialog('close');
                        })
                    },
                    create: function () {
                        // style fix for WordPress admin
                        $('.ui-dialog-titlebar-close').addClass('ui-button');
                    }
                });

            }
        },

        input_change: function () {
            // var input = $(this);
            // var icon_wrap = input.closest('.hiweb-field-fontawesome').find('.input-group-addon');
            // icon_wrap.remove('svg, i').append('<i class="' + input.val() + '"></i>');
        }

    };

    hiweb_field_fontawesome.init();
    jQuery('body').on('init_3', '.hiweb-field-fontawesome', hiweb_field_fontawesome.init);
    jQuery('body').on('change keyup', '.hiweb-field-fontawesome input', hiweb_field_fontawesome.input_change);

});
