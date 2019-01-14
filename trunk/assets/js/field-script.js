jQuery(document).ready(function ($) {

    var hiweb_field_script = {

        init: function () {
            $('.hiweb-field-script').each(hiweb_field_script.make);
        },

        make: function () {
            var $root = $(this);
            if($root.find('.CodeMirror').length === 0) {
                var rand_id = $root.data('rand-id');
                console.info(rand_id);
                wp.codeEditor.initialize(rand_id, {
                    mode: "text/html",
                    lineWrapping: true,
                    lineNumbers: true,
                    inputStyle: 'textarea',
                    extraKeys: {"Ctrl-Space": "autocomplete"},
                    theme: $root.data('script-theme')
                });
            }
        }

    };

    hiweb_field_script.init();
    $('body').on('init_3', '.hiweb-field-script', hiweb_field_script.make);

});