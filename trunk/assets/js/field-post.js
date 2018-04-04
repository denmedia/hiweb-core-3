/**
 * Created by denmedia on 08.06.2017.
 */


var hw_input_post = {

    init: function () {
        jQuery('body').on('init', '.hiweb-field-post', hw_input_post._event_init);
        jQuery('.hiweb-field-post').each(hw_input_post._event_init);
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
                    error: function(err){
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