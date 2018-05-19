jQuery(document).ready(function ($) {

    //$('.my').iconpicker();

    var hiweb_field_fontawesome = {


        init: function () {
            var input = $('.hiweb-field-fontawesome input');
            var offset = input.offset();
            if (offset !== undefined && offset.hasOwnProperty('top')) {
                if (offset.top < 250) input.attr('data-placement', 'bottom');
            }
            input.each(hiweb_field_fontawesome.make);
        },

        make: function () {
            $(this).iconpicker({});
        },

        input_change: function () {
            var input = $(this);
            var icon_wrap = input.closest('.hiweb-field-fontawesome').find('.input-group-addon');
            icon_wrap.html('<i class="' + input.val() + '"></i>');
        }

    };

    hiweb_field_fontawesome.init();
    jQuery('body').on('init_3', '.hiweb-field-fontawesome', hiweb_field_fontawesome.init);
    jQuery('body').on('keyup', '.hiweb-field-fontawesome input', hiweb_field_fontawesome.input_change);

});
