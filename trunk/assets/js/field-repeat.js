/**
 * Created by hiweb on 21.10.2016.
 */

let hiweb_field_repeat = {

    selector: '.hiweb-field-repeat',
    selector_source: '[data-row-source]',
    selector_wrap: 'tbody.wrap',
    selector_row: '[data-row]',
    selector_button_add: '[data-action-add]',
    selector_button_clear: '[data-action-clear]',
    selector_button_remove: '[data-action-remove]',
    selector_button_duplicate: '[data-action-duplicate]',

    init: function (root) {
        hiweb_field_repeat.make_sortable(root);
        jQuery(root).find('> table > thead .dropdown, > table > tfoot .dropdown')
            .dropdown({
                action: 'hide'
            });
        ///Set init names super repeat root
        if (jQuery(root).parent().closest(hiweb_field_repeat.selector).length === 0) {
            hiweb_field_repeat.set_input_names(root);
        }
    },

    init_once: function () {
        jQuery(hiweb_field_repeat.selector).each(function () {
            hiweb_field_repeat.init(this);
        });
        jQuery('body').on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_add, hiweb_field_repeat.click_add).on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_remove, hiweb_field_repeat.click_remove).on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_duplicate, hiweb_field_repeat.click_duplicate).on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_clear, hiweb_field_repeat.click_clear_full);

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

    /**
     *
     * @param row
     * @returns {*}
     */
    get_cols_by_row: function (row) {
        return jQuery(row).find('> [data-col], > td > [data-col], > td > table > tbody > tr > [data-col]');
    },

    make_sortable: function (root) {
        var rows = hiweb_field_repeat.get_rows_list(root);
        if (typeof rows.sortable == 'undefined') {
            alert('Плагин jquery.ui.sortable.js не подключен!');
            return;
        }
        if (rows['sortable'].hasOwnProperty('destroy')) {
            rows.sortable("destroy");
        }
        rows.sortable({
            update: function (e, ui) {
                hiweb_field_repeat.set_input_names(jQuery(this).closest(hiweb_field_repeat.selector));
                ui.placeholder.find('[data-col]').each(function () {
                    jQuery(this).trigger('hiweb-field-repeat-drag-update', [jQuery(this), jQuery(this).closest('[data-row]'), root]);
                });
            },
            distance: 3,
            handle: '> [data-drag], > [data-drag] button, > [data-drag] i',
            helper: function (e, ui) {
                ui.find('th, td').each(function () {
                    jQuery(this).width(jQuery(this).width());
                });
                ui.find('[data-col]').each(function () {
                    jQuery(this).trigger('hiweb-field-repeat-dragged', [jQuery(this), jQuery(this).closest('[data-row]'), root]);
                });
                return ui;
            },
            revert: true,
            start: function (e, elements) {
                elements.placeholder.height(elements.helper.height());
                elements.helper.find('[data-col]').each(function () {
                    jQuery(this).trigger('hiweb-field-repeat-drag-start', [jQuery(this), jQuery(this).closest('[data-row]'), root]);
                });
            },
            stop: function (e, ui) {
                ui.item.find('[data-col]').each(function () {
                    jQuery(this).trigger('hiweb-field-repeat-drag-stop', [jQuery(this), jQuery(this).closest('[data-row]'), root]);
                });
            }
        });
    },

    getRandomColor: function () {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    },

    set_input_names: function (root) {
        root = jQuery(root);
        //set rows index
        root.find('[data-row]').each(function () {
            jQuery(this).attr('data-row', jQuery(this).index());
        });
        //set sub-input names
        var $sub_inputs = root.find('[name]');
        $sub_inputs.each(function () {
            jQuery(this).attr('name', hiweb_field_repeat.get_input_name(jQuery(this)));
        });
        //
        root.find('> table > tbody[data-rows-message] [data-row-empty]').attr('data-row-empty', hiweb_field_repeat.get_rows(root).length > 0 ? '1' : '0');
    },

    get_input_name: function ($input) {
        $input = jQuery($input);
        var name_items = [];
        var limit = 10;
        while (limit > 0) {
            var $col = $input.closest('[data-col]');
            if ($col.length !== 1) {
                return $input.closest('.hiweb-field-repeat[data-input-name]').attr('data-input-name');
            }
            //name sufix extract
            let name_segments = $input.attr('name').split('[' + $col.attr('data-col') + ']');
            if (name_segments.length > 1) {
                let name_sufix = name_segments.pop();
                if (name_sufix !== '') {
                    name_items.push(name_sufix);
                }
            }
            name_items.push('[' + $col.attr('data-col') + ']');
            //row
            var $row = $col.closest('[data-row]');
            if ($row.length !== 1) {
                console.warn('2: row not found');
                break;
            }
            name_items.push('[' + $row.attr('data-row') + ']');
            //repeat or super col
            var $repeat = $row.closest('.hiweb-field-repeat[data-id]');
            if ($repeat.length !== 1) {
                console.warn('3: repeat not found');
            }
            var $super_col = $repeat.closest('[data-col]');
            if ($super_col.length !== 1) {
                //name_items.push($repeat.attr('data-id'));
                name_items.push($repeat.attr('data-input-name'));
                break;
            } else {
                $input = $repeat;
            }
            limit--;
        }
        return name_items.reverse().join('');
    },


    get_global_id: function (e) {
        return jQuery(e).is('[data-global-id]') ? jQuery(e).attr('data-global-id') : jQuery(e).closest('[data-global-id]').attr('data-global-id');
    },

    get_name_id: function (e) {
        return jQuery(e).is('[data-input-name]') ? jQuery(e).data('input-name') : jQuery(e).closest('[data-input-name]').data('input-name');
    },

    click_add: function (e) {
        e.preventDefault();
        if (jQuery(this).is('[data-action-add="flex-dropdown"]')) {
            var dropdown = jQuery(this).closest('[data-ctrl]').find('[data-sub-flex-dropdown]');
            var timeout = null;
            dropdown.fadeIn().off('mouseover mouseout').on('mouseout', function () {
                timeout = setTimeout(function () {
                    dropdown.fadeOut();
                }, 1000);
            }).on('mouseover', function () {
                clearTimeout(timeout);
            });
        } else {
            var prepend = jQuery(this).is('[data-action-add="1"]');
            var root = jQuery(this).closest(hiweb_field_repeat.selector);
            var flex_row_id = jQuery(this).is('[data-flex-id]') ? jQuery(this).attr('data-flex-id') : '';
            if (flex_row_id !== '') {
                jQuery(this).closest('[data-sub-flex-dropdown]').fadeOut();
            }
            hiweb_field_repeat.add_rows(root, prepend, 1, '', flex_row_id);
        }
    },

    add_rows: function (root, prepend, rows_count, callback, flex_row_id) {
        if (typeof rows_count === 'undefined') rows_count = 1;
        if (typeof prepend === 'undefined') prepend = false;
        if (typeof flex_row_id === 'undefined') flex_row_id = '';
        ///
        var row_list = hiweb_field_repeat.get_rows_list(root);
        var index = prepend ? 0 : hiweb_field_repeat.get_rows(root).length;
        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {
                id: hiweb_field_repeat.get_global_id(root),
                method: 'ajax_html_row',
                input_name: hiweb_field_repeat.get_name_id(root),
                index: index,
                flex_row_id: flex_row_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.hasOwnProperty('result') && response.result === true) {
                    var newLine = jQuery(response.data).hide().fadeIn();
                    for (n = 0; n < rows_count; n++) {
                        if (prepend) {
                            row_list.prepend(newLine);
                        } else {
                            row_list.append(newLine);
                        }
                        newLine.find('[data-col]').each(function () {
                            jQuery(this).trigger('hiweb-field-repeat-add-new-row', [jQuery(this), newLine, root]);
                        });
                        newLine.find('> td')
                            .wrapInner('<div style="display: none;" />')
                            .parent()
                            .find('> td > div')
                            .slideDown(400, function () {
                                var $set = jQuery(this);
                                $set.replaceWith($set.contents());
                                newLine.find('[data-col]').each(function () {
                                    jQuery(this).trigger('hiweb-field-repeat-added-row-fadein', [jQuery(this), newLine, root]);
                                    jQuery(this).trigger('hiweb-field-repeat-added-new-row-fadein', [jQuery(this), newLine, root]);
                                });
                            });
                        hiweb_field_repeat.set_input_names(root);
                        newLine.find('[data-col]').each(function () {
                            jQuery(this).trigger('hiweb-field-repeat-added-row', [jQuery(this), newLine, root]);
                            jQuery(this).trigger('hiweb-field-repeat-added-new-row', [jQuery(this), newLine, root]);
                        });
                    }
                } else {
                    console.warn(response);
                }
            },
            error: function (response) {
                console.warn(response);
            }
        });
    },

    paramsToArray: function (name, value) {
        var keys = name.match(/(\[?[\d\w\-\_]+\]?)/g);
        var R = temp = {};
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i].replace(/^\[?([\d\w\-\_]+)\]?/, '$1');
            if (i === keys.length - 1) {
                temp = temp[key] = value;
            } else {
                temp = temp[key] = {};
            }
        }
        return R;
    },


    click_duplicate: function (e) {
        e.preventDefault();
        var root = jQuery(this).closest(hiweb_field_repeat.selector);
        var row_list = hiweb_field_repeat.get_rows_list(root);
        var currentRow = jQuery(this).closest(hiweb_field_repeat.selector_row);
        var values = [];
        var base_name = hiweb_field_repeat.get_input_name(this) + '[' + currentRow.attr('data-row') + ']';
        currentRow.find('[name]').each(function () {
            var input_name = jQuery(this).attr('name');
            if (input_name.indexOf(base_name) === 0) {
                input_name = input_name.substr(base_name.toString().length).replace(/^\[([\w\d\-_]+)\]/g, '$1');
                values.push(hiweb_field_repeat.paramsToArray(input_name, jQuery(this).val()));
            }
        });

        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {
                id: hiweb_field_repeat.get_global_id(this),
                method: 'ajax_html_row',
                row_index: currentRow.attr('data-row'),
                values: deepMerge.all(values)
            },
            dataType: 'json',
            success: function (response) {
                if (response.hasOwnProperty('result') && response.result === true) {
                    var newLine = jQuery(response.data).hide().fadeIn();
                    currentRow.after(newLine);
                    newLine.find('[data-col]').each(function () {
                        jQuery(this).trigger('hiweb-field-repeat-add-new-row', [jQuery(this), newLine, root]);
                    });
                    newLine.css('opacity', 0).animate({opacity: 1}).find('> td')
                        .wrapInner('<div style="display: none;" />')
                        .parent()
                        .find('> td > div')
                        .slideDown(700, function () {
                            var $set = jQuery(this);
                            $set.replaceWith($set.contents());
                            newLine.find('[data-col]').each(function () {
                                jQuery(this).trigger('hiweb-field-repeat-cloned-row-fadein', [jQuery(this), newLine, root]);
                                jQuery(this).trigger('hiweb-field-repeat-added-new-row-fadein', [jQuery(this), newLine, root]);
                            });
                        });
                    hiweb_field_repeat.set_input_names(root);
                    newLine.find('[data-col]').each(function () {
                        jQuery(this).trigger('hiweb-field-repeat-cloned-row', [jQuery(this), newLine, root]);
                        jQuery(this).trigger('hiweb-field-repeat-added-new-row', [jQuery(this), newLine, root]);
                    });
                } else {
                    console.warn(response);
                }
            },
            error: function (data) {
                console.warn(data);
            }
        });
    },

    click_remove: function (e) {
        e.preventDefault();
        var row = jQuery(this).closest(hiweb_field_repeat.selector_row);
        hiweb_field_repeat.do_remove_row(row);
    },

    do_remove_row: function (row) {
        return jQuery(row).find('> td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('> td > div')
            .slideUp(700, function () {
                var root = row.closest(hiweb_field_repeat.selector);
                jQuery(this).parent().parent().remove();
                row.remove();
                hiweb_field_repeat.set_input_names(root);
                if (hiweb_field_repeat.get_rows(root).length === 0) {
                    jQuery(root).find('.message').fadeIn();
                }
            });
    },

    click_clear_full: function (e) {
        e.preventDefault();
        if (confirm('Remove all table rows?')) {
            var root = jQuery(this).closest(hiweb_field_repeat.selector);
            hiweb_field_repeat.get_rows(root).each(function () {
                hiweb_field_repeat.do_remove_row(this)
            });
        }
    }

};

jQuery(document).ready(hiweb_field_repeat.init_once);
jQuery('body').on('hiweb-field-repeat-added-new-row', '[data-col]', function (e, col, row, root) {
    col.find('.hiweb-field-repeat').each(function () {
        hiweb_field_repeat.init(this);
    });
});