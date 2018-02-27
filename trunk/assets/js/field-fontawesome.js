jQuery(document).ready(function ($) {

    //$('.my').iconpicker();

    var hiweb_field_fontawesome = {


        init: function () {
            $('.hiweb-field-fontawesome input').each(hiweb_field_fontawesome.make);
        },

        make: function () {
            $(this).iconpicker({

            });
        }

    };

    hiweb_field_fontawesome.init();
    jQuery('body').on('init_3', '.hiweb-field-fontawesome', hiweb_field_fontawesome.init);

});
