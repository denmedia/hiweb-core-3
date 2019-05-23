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
                multiple: true,
                forceSelection: false,
                allowCategorySelection: true,
                onChange: function (value, text, $choice) {
                    //console.info( [value, text, $choice] );
                }
            });
            //hiweb_field_terms.make_sortable(root);
        },

        // make_sortable: function(root){
        //     var sortable_root = $(root).find('.ui.dropdown.selection');
        //     var input = $(root).find('input[name]');
        //     sortable_root.sortable({
        //         items: '> .ui.label[data-value]',
        //         opacity: 0.5,
        //         revert: true,
        //         cursor: 'move',
        //         distance: 5,
        //         forceHelperSize: true,
        //         forcePlaceholderSize: true,
        //         start: function(event, ui){
        //             sortable_root.find('.ui-sortable-placeholder').width( ui.helper.width() );
        //         },
        //         change: function(){
        //             sortable_root.find('> .ui.label[data-value]').each(function(){
        //                 input.find("option[value='" + e + "']").prop("selected", true);
        //             });
        //         }
        //     });
        // }

    };
    $('body').on('hiweb-field-repeat-added-row-fadein', '[data-col]', function (e, col, row, root) {
        col.find(hiweb_field_terms.selector_root).each(function(){
            hiweb_field_terms.make($(this));
        });
    });
    $(hiweb_field_terms.selector_root).each(function () {
        hiweb_field_terms.make(this);
    });
});
