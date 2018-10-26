jQuery(document).ready(function ($) {

    //$('.my').iconpicker();

    var hiweb_field_fontawesome = {

        icons: [],
        timeout: null,
        ajax_request: null,


        init: function () {
            $('.hiweb-field-fontawesome').each(hiweb_field_fontawesome.make);
        },

        make: function () {
            var $root = $(this);
            $root.on('click', '[data-click="icons"]', function (e) {
                e.preventDefault();
                hiweb_field_fontawesome.do_query_icons($root, '');
            }).on('change keyup', 'input[name]', function () {
                var $this = $(this);
                $root.find('.input-group-addon').html('<i class="' + $this.val() + '"></i>');
                $root.find('.ui.icon.button[data-click="icons"]').removeClass('loading');
                clearTimeout(hiweb_field_fontawesome.timeout);
                hiweb_field_fontawesome.timeout = setTimeout(function () {
                    $root.find('.ui.icon.button[data-click="icons"]').addClass('loading');
                    hiweb_field_fontawesome.do_query_icons($root, $this.val());
                }, 1000);
            }).on('blur', 'input[name]', function () {
                setTimeout(function () {
                    $root.find('.ui.icon.button[data-click="icons"]').removeClass('loading');
                    clearTimeout(hiweb_field_fontawesome.timeout);
                    $root.find('.ui.dropdown').dropdown('hide');
                }, 500);
            }).on('click', '[data-click="styles"]', function (e) {
                e.preventDefault();
                $root.find('[data-click="styles"]').popup('show');
            });
            //dropdown
            $root.find('.ui.dropdown').dropdown({
                on: 'manual',
                allowAdditions: true,
                hideAdditions: false,
                //action: 'auto',
                selectOnKeydown: false,
                forceSelection: false
            });
            //popup
            $root.find('[data-click="styles"]').popup({
                inline: true,
                content: 'TEST',
                hoverable: true,
                exclusive: true,
                position: 'top center',
                on: 'click'
            });
            //Close Dropdown
            $('body').on('click', function (e) {
                if (!$root.is(e.target) && $root.has(e.target).length === 0 && $root.is('.ui.popup[data-field-rand-id="' + $root.data('rand-id') + '"]')) {
                    $root.find('.ui.dropdown').dropdown('hide');
                }
            });
            //Select Icon
            $('body').on('click', '.hiweb-field-fontawesome .dropdown .menu .item', function () {
                var $root = $(this).closest('.hiweb-field-fontawesome');
                hiweb_field_fontawesome.set_value($root, $(this).data('value'));
                var styles = hiweb_field_fontawesome.icons['styles'][$(this).data('text')];
                if (styles.length > 1) {
                    $root.find('.ui.icon.button[data-click="styles"]').removeClass('disabled');
                    $root.find('.ui.icon.button[data-click="styles"]').popup('create');
                    $root.find('.ui.icon.button[data-click="styles"]').popup('get popup').find('.content').html('');
                    for (var id in styles) {
                        $root.find('.ui.icon.button[data-click="styles"]').popup('get popup').find('.content').append('<div class="item" data-value="' + styles[id] + '"><i class="' + styles[id] + '"></i></div>');
                    }
                } else {
                    $root.find('.ui.icon.button[data-click="styles"]').addClass('disabled');
                    $root.find('.ui.icon.button[data-click="styles"]').popup('create');
                    $root.find('.ui.icon.button[data-click="styles"]').popup('hide');
                    $root.find('.ui.icon.button[data-click="styles"]').popup('get popup').find('.content').html('');
                }
            });
            //Select Style Icon
            $('body').on('click', '.hiweb-field-fontawesome .ui.popup .content .item', function () {
                var $root = $(this).closest('.hiweb-field-fontawesome');
                hiweb_field_fontawesome.set_value($root, $(this).data('value'));
            });
        },

        set_value: function ($root, value) {
            $root.find('input[name]').val(value);
            $root.find('.input-group-addon').html('<i class="' + value + '"></i>');
        },

        do_query_icons: function ($root, query) {
            if (hiweb_field_fontawesome.ajax_request !== null) hiweb_field_fontawesome.ajax_request.abort();
            $root.find('.ui.icon.button[data-click="icons"]').addClass('loading');
            hiweb_field_fontawesome.ajax_request = $.ajax({
                url: ajaxurl + '?action=hiweb-type-fontawesome',
                type: 'post',
                data: {query: query},
                dataType: 'json',
                success: function (response) {
                    hiweb_field_fontawesome.icons = response;
                    $root.find('.ui.icon.button[data-click="icons"]').removeClass('loading');
                    $root.find('.ui.dropdown').dropdown('setup').menu({values: response.values});
                    $root.find('.ui.dropdown').dropdown('refresh');
                    $root.find('.ui.dropdown').dropdown('show');
                },
                error: function (response) {
                    console.info(response);
                    $root.find('.ui.icon.button[data-click="icons"]').removeClass('loading');
                }
            });
        }

    };

    hiweb_field_fontawesome.init();
    //jQuery('body').on('init_3', '.hiweb-field-fontawesome', hiweb_field_fontawesome.init);
    jQuery(document).on('hiweb-field-repeat-added-new-row', '[data-col]', function (e, col, row, root) {
        col.find('.hiweb-field-fontawesome').each(hiweb_field_fontawesome.init);
    });

});
