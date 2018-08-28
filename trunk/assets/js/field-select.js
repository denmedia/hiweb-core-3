jQuery(document).ready(function ($) {
    var hiweb_field_select = {

        init: function(){
            $('.hiweb-field-select').each(function(){ hiweb_field_select.make_dropdown(this) });
        },

        make_dropdown: function (root) {
            $(root).find('select').dropdown();
        }


    };

    hiweb_field_select.init();
    $('body').on('init_3', '.hiweb-field-select', hiweb_field_select.make_dropdown);

});