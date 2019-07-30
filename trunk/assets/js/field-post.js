/**
 * Created by denmedia on 08.06.2017.
 */


var hw_input_post = {

    init: function () {
        jQuery('.hiweb-field-post select').each(hw_input_post.make_selectize);
    },

    _load_posts: function ($root, query, callback) {
        if (typeof query !== 'string') query = '';
        jQuery.ajax({
            //url: ajaxurl + '?action=hiweb-type-post' + (typeof query === 'undefined') ? '' : '&' + encodeURIComponent(query),
            url: ajaxurl + '?action=hiweb-type-post',
            type: 'POST',
            dataType: 'json',
            data: {global_id: $root.attr('data-global-id'), search: query},
            error: function () {
                if (callback === 'function') {
                    callback();
                }
            },
            success: function (res) {
                if (res.hasOwnProperty('success')) {
                    if (res.success && typeof callback === 'function') {
                        callback(res.items);
                    } else {
                        console.warn(res);
                    }
                } else {
                    console.error('hiweb-input-post: Не удалорсь загрузить список записей');
                }

            }
        });
    },

    make_selectize: function () {
        var $this = jQuery(this);
        $this.selectize({
            closeAfterSelect: true,
            allowEmptyOption: true,
            valueField: 'value',
            labelField: 'title',
            searchField: 'title',
            options: [],
            create: false,
            onInitialize: function () {
                hw_input_post._load_posts($this, '', function (items) {
                    let value = jQuery.parseJSON($this.attr('data-value'));
                    $this.each(function () {
                        for (let index in items) {
                            this.selectize.addOption(items[index]);
                        }
                        this.selectize.addItem(value);
                    });
                });
            },
            // score: function (search) {
            //     var score = this.getScoreFunction(search);
            //     return function (item) {
            //         return score(item);
            //     };
            // },
            load: function (query, callback) {
                if (!query.length) return callback();
                hw_input_post._load_posts($this, query, callback);
            }
        });
    }

};

jQuery(document).ready(hw_input_post.init);
jQuery(document).on('hiweb-field-repeat-added-new-row', '[data-col]', function (e, col, row, root) {
    col.find('.hiweb-field-post select').each(hw_input_post.make_selectize);
});