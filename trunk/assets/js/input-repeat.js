/**
 * Created by hiweb on 21.10.2016.
 */
String.prototype.escapeRegExp = function () {
    return this.toString().replace(/[\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
};

var hw_input_repeat = {

    selector: '.hw-input-repeat',
    selector_source: '[data-row-source]',
    selector_wrap: 'tbody.wrap',
    selector_row: '[data-row]',
    selector_button_add: '[data-action-add]',
    selector_button_clear: '[data-action-clear]',
    selector_button_remove: '[data-action-remove]',
    selector_button_duplicate: '[data-action-duplicate]',

    init: function () {
        jQuery('body').on('click', hw_input_repeat.selector + ' ' + hw_input_repeat.selector_button_add, hw_input_repeat.click_add).on('click', hw_input_repeat.selector + ' ' + hw_input_repeat.selector_button_remove, hw_input_repeat.click_remove).on('click', hw_input_repeat.selector + ' ' + hw_input_repeat.selector_button_duplicate, hw_input_repeat.click_duplicate).on('click', hw_input_repeat.selector + ' ' + hw_input_repeat.selector_button_clear, hw_input_repeat.click_clear_full);
        hw_input_repeat.make_sortable();
        // hw_input_repeat.make_table_names(jQuery(hw_input_repeat.selector));
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_row_source: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-source] > tr[data-row]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_rows_list: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-list]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_rows: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-list] > tr[data-row]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_cols: function (root) {
        return jQuery(root).find('> table > thead [data-col]');
    },

    get_cols_by_row: function (row) {
        return row.find('> [data-col]');
    },

    make_sortable: function () {
        var roots = hw_input_repeat.get_rows_list(hw_input_repeat.selector);
        if (roots['sortable']['destroy'] !== undefined) {
            roots.sortable("destroy");
        }
        roots.sortable({
            update: function (e, ui) {
                hw_input_repeat.make_table_names(jQuery(this).closest(hw_input_repeat.selector));
                ui.placeholder.find('> [data-col] > *').trigger('drag_update');
            },
            distance: 3,
            handle: '> [data-drag], > [data-drag] button, > [data-drag] i',
            helper: function (e, ui) {
                ui.find('th, td').each(function () {
                    jQuery(this).width(jQuery(this).width());
                });
                ui.find('> [data-col] > *').trigger('dragged');
                return ui;
            },
            revert: true,
            start: function (e, elements) {
                elements.placeholder.height(elements.helper.height());
                elements.helper.find('> [data-col] > *').trigger('drag_start', elements);
            },
            stop: function (e, ui) {
                ui.item.find('> [data-col] > *').trigger('drag_stop', ui.placeholder);
            }
        });
        jQuery(hw_input_repeat.selector + ' tbody').disableSelection();
    },

    make_table_names: function (root) {
        root = jQuery(root);
        var base_name = root.attr('name');
        //set rows
        var index = 0;
        //Each rows
        hw_input_repeat.get_rows(root).each(function () {
            var row = jQuery(this).attr('data-row', index);
            //Each cols
            hw_input_repeat.get_cols_by_row(row).each(function () {
                var col_id = jQuery(this).attr('data-col');
                var replace_name = base_name + '[' + index + '][' + col_id + ']';
                //Each inputs
                jQuery(this).find('[name]').each(function () {
                    var input = jQuery(this);
                    var pattern = '^' + base_name.escapeRegExp() + '\\\[(\\\s{1}|\\\d+)\\\]\\\[' + col_id + '\\\]';
                    var newName = input.attr('name').replace(new RegExp(pattern, 'i'), replace_name);
                    input.attr('name', newName);
                });
            });
            index++;
        });
        root.find('> table > tbody[data-rows-message] [data-row-empty]').attr('data-row-empty', index > 0 ? '1' : '0');
        hw_input_repeat.make_sortable();
    },


    get_global_id: function (e) {
        return jQuery(e).closest('[data-global-id]').attr('data-global-id');
    },

    get_name_id: function (e) {
        return jQuery(e).closest('[data-input-name]').attr('data-input-name');
    },

    click_add: function (e) {
        e.preventDefault();
        var prepend = jQuery(this).is('[data-action-add="1"]');
        var root = jQuery(this).closest(hw_input_repeat.selector);
        var row_list = hw_input_repeat.get_rows_list(root);
        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {id: hw_input_repeat.get_global_id(this), method: 'ajax_html_row', params: hw_input_repeat.get_name_id(this)},
            dataType: 'json',
            success: function (data) {

                if (data.hasOwnProperty('result') && data.result === true) {
                    var newLine = jQuery(data.data).hide().fadeIn();
                    if (prepend) {
                        row_list.prepend(newLine);
                    } else {
                        row_list.append(newLine);
                    }
                    newLine.find('[data-col] > *').trigger('init');
                    newLine.css('opacity', 0).animate({opacity: 1}).find('> td')
                        .wrapInner('<div style="display: none;" />')
                        .parent()
                        .find('> td > div')
                        .slideDown(700, function () {
                            var $set = jQuery(this);
                            $set.replaceWith($set.contents());
                            newLine.find('[data-col] > *').trigger('init_3');
                        });
                    hw_input_repeat.make_table_names(root);
                    newLine.find('[data-col] > *').trigger('init_2');
                } else {
                    console.warn(data);
                }
            },
            error: function (data) {
                console.warn(data);
            }
        });
    },

    objectifyForm: function (formArray) {//serialize data function

        var returnArray = {};
        for (var i = 0; i < formArray.length; i++) {
            returnArray[formArray[i]['name']] = formArray[i]['value'];
        }
        return returnArray;
    },

    click_duplicate: function (e) {
        e.preventDefault();
        var root = jQuery(this).closest(hw_input_repeat.selector);
        var row_list = hw_input_repeat.get_rows_list(root);
        var currentRow = jQuery(this).closest(hw_input_repeat.selector_row);
        var values = {};
        hw_input_repeat.get_cols(root).each(function () {
            var col_id = jQuery(this).attr('data-col');
            values[col_id] = hw_input_repeat.objectifyForm(currentRow.find('> td[data-col="' + col_id + '"] [name]').serializeArray());
        });

        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {id: hw_input_repeat.get_global_id(this), method: 'ajax_html_row', params: hw_input_repeat.get_name_id(this), value: values}, //todo: value
            dataType: 'json',
            success: function (data) {
                if (data.hasOwnProperty('result') && data.result === true) {
                    var newLine = jQuery(data.data).hide().fadeIn();
                    row_list.append(newLine);
                    newLine.find('[data-col] > *').trigger('init');
                    newLine.css('opacity', 0).animate({opacity: 1}).find('> td')
                        .wrapInner('<div style="display: none;" />')
                        .parent()
                        .find('> td > div')
                        .slideDown(700, function () {
                            var $set = jQuery(this);
                            $set.replaceWith($set.contents());
                        });
                    hw_input_repeat.make_table_names(root);
                } else {
                    console.warn(data);
                }
            },
            error: function (data) {
                console.warn(data);
            }
        });
        /*
        var row = jQuery(this).closest(hw_input_repeat.selector_row);
        var newRow = row.clone(false).insertAfter(row);
        newRow.find('[data-col] > *').trigger('init');
        newRow.css('opacity', 0).animate({opacity: 1}).find('> td')
            .wrapInner('<div style="display: none;" />')
            .parent()
            .find('> td > div')
            .slideDown(700, function () {
                var $set = jQuery(this);
                $set.replaceWith($set.contents());

            });
        hw_input_repeat.make_table_names(jQuery(this).closest(hw_input_repeat.selector));
         newRow.find('[data-col] > *').trigger('init_2');*/
    },

    click_remove: function (e) {
        e.preventDefault();
        var row = jQuery(this).closest(hw_input_repeat.selector_row);
        hw_input_repeat.do_remove_row(row);
    },

    do_remove_row: function (row) {
        return jQuery(row).find('> td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('> td > div')
            .slideUp(700, function () {
                var root = row.closest(hw_input_repeat.selector);
                jQuery(this).parent().parent().remove();
                row.remove();
                hw_input_repeat.make_table_names(root);
                if (hw_input_repeat.get_rows(root).length === 0) {
                    jQuery(root).find('.message').fadeIn();
                }
            });
    },

    click_clear_full: function (e) {
        e.preventDefault();
        if (confirm('Remove all table rows?')) {
            var root = jQuery(this).closest(hw_input_repeat.selector);
            hw_input_repeat.get_rows(root).each(function () {
                hw_input_repeat.do_remove_row(this)
            });
        }
    }

};

jQuery(document).ready(hw_input_repeat.init);