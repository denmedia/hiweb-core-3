jQuery(document).ready(function ($) {
    var hiweb_field_select = {

        init: function () {
            $('.hiweb-field-select').each(function () {
                hiweb_field_select.make_dropdown(this)
            });
        },

        make_dropdown: function (root) {
            var max = 999, min = 1;
            var rand = Math.floor(Math.random() * (max - min + 1)) + min;
            $(root).find('select').addClass('rand-' + rand).dropdown({
                forceSelection: false,
                sortSelect: true
            });
        }


    };

    hiweb_field_select.init();
    $('body').on('init_3', '.hiweb-field-select', function () {
        hiweb_field_select.make_dropdown(this);
    });

});