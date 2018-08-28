/**
 * Created by denmedia on 13.07.2017.
 */

jQuery(document).ready(function ($) {
    var hiweb_field_terms = {

        selector_root: '.hiweb-field-terms',

        make: function (root) {
            $(root).find('.ui.dropdown').dropdown({
                saveRemoteData: false,
                filterRemoteData: true,
                multiple: true
            });
        }

    };
    $('body').on('init_3', hiweb_field_terms.selector_root, function () {
        hiweb_field_terms.make(this);
    });
    $(hiweb_field_terms.selector_root).each(function () {
        hiweb_field_terms.make(this);
    });
});
