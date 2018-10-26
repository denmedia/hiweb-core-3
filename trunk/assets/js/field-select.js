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
                forceSelection: false
                //sortSelect: true
            });
        }


    };

    hiweb_field_select.init();
    jQuery('body').on('hiweb-field-repeat-added-new-row', '[data-col]', function(e, col, row, root){
        col.find('.hiweb-field-select').each(hiweb_field_select.make_dropdown);
    });

});