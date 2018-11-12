/**
 * Created by denmedia on 08.06.2017.
 */


var hw_input_post = {

    init: function () {
        jQuery('body').on('init', '.hiweb-field-post', hw_input_post.make_dropdown);
        jQuery('.hiweb-field-post').each(hw_input_post.make_dropdown);
    },

    make_dropdown: function () {
        var select = jQuery(this).find('select');
        var global_id = select.attr('data-global-id');
        var dropdown = jQuery(this).find('.ui.dropdown');
        dropdown.dropdown({
            ignoreCase: true,
            //forceSelection: false,
            sortSelect: true,
            clearable: true,
            useLabels: true,
            // apiSettings: {
                // this url just returns a list of tags (with API response expected above)
                // url: ajaxurl + '?action=hiweb-type-post&search={query}'
            // },
            saveRemoteData: false,
            // filterRemoteData: true,
            message: {
                count: '{count} выбрано',
                maxSelections: 'Максимум {maxCount} элементов',
                noResults: 'Ничего не найдено.'
            }
            // onShow: function () {
            //     dropdown.addClass('loading');
            //     jQuery.ajax({
            //         url: ajaxurl + '?action=hiweb-type-post',
            //         type: 'post',
            //         data: {global_id: global_id, search: 'при'},
            //         dataType: 'json',
            //         success: function (resp) {
            //             console.info(resp.data);
            //         },
            //         error: function (err) {
            //             console.error(err);
            //             //callback([]);
            //         },
            //         complete: function(){
            //             dropdown.removeClass('loading');
            //         }
            //     });
            // }
        });
    },

    _event_init: function () {
        var select = jQuery(this).find('select');
        var global_id = select.attr('data-global-id');
        select.selectator({
            height: 'auto',
            useDimmer: false,
            useSearch: true,
            delay: 2000,
            keepOpen: true,
            minSearchLength: 2,
            placeholder: 'Выберите запись / страницу',
            load: function (search, callback) {
                jQuery.ajax({
                    url: ajaxurl + '?action=hiweb-type-post',
                    type: 'post',
                    data: {global_id: global_id, search: search},
                    dataType: 'json',
                    success: function (resp) {
                        callback(resp.data);
                    },
                    error: function (err) {
                        console.error(err);
                        callback([]);
                    }
                });
                //callback(results);
            },
            labels: {
                search: 'Поиск...'
            }
        });
    }

};

jQuery(document).ready(hw_input_post.init);