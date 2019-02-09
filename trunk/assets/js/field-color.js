jQuery(document).ready(function ($) {

    var hiweb_field_color = {

        init: function () {
            jQuery('body').on('hiweb-field-repeat-added-row-fadein', '[data-col]', function () {
                hiweb_field_color._make($(this).find('.hiweb-field-color'));
            });
            jQuery('.hiweb-field-color').each(function () {
                hiweb_field_color._make(this);
            });
        },

        _make: function (root) {
            // jQuery('[data-type-color]').removeAttr('data-type-color').colorPicker({
            //     opacity: true
            // });
            //jQuery('[data-type-color]').tinycolorpicker();
            jQuery('body').on('click', '.hiweb-field-color .ui.icon.button', function (e) {
                e.preventDefault();
            });
            console.info( root.length ); //todo-
            jQuery(root).find('[data-colorpicker-show]')
                .popup({
                    inline: false,
                    hoverable: true,
                    position: 'bottom center',
                    popup: jQuery('.custom.popup'),
                    on: 'click',
                    delay: {
                        show: 300,
                        hide: 800
                    }
                });
            jQuery(root).find('.menu .item').tab({});
            jQuery(root).find('[data-colorpicker-wrap]').append('<canvas></canvas>');
            jQuery(root).find('[data-colorpicker-wrap]').find('img').each(function () {
                var canvas = $(this).parent().find('canvas')[0];
                // preview function mousemove
                this.addEventListener('click', function (e) {
                    // chrome
                    if (e.offsetX) {
                        x = e.offsetX;
                        y = e.offsetY;
                    }
                    // firefox
                    else if (e.layerX) {
                        x = e.layerX;
                        y = e.layerY;
                    }

                    hiweb_field_color.useCanvas(canvas, this, function () {

                        // get image data
                        var p = canvas.getContext('2d')
                            .getImageData(x, y, 1, 1).data;
                        // show preview color
                        hiweb_field_color.select_color(root, hiweb_field_color.rgbToHex(p[0], p[1], p[2]));
                    });
                }, false);
            });
        },

        select_color: function (root, rgbOrHex) {
            $(root).find('input').val(rgbOrHex);
            $(root).find('.ui.icon.button').css('background-color', rgbOrHex);
        },

        useCanvas: function (el, image, callback) {
            el.width = image.width; // img width
            el.height = image.height; // img height
            // draw image in canvas tag
            el.getContext('2d')
                .drawImage(image, 0, 0, image.width, image.height);
            return callback();
        },

        _: function (el) {
            return document.querySelector(el);
        },

        componentToHex: function (c) {
            var hex = c.toString(16);
            return hex.length === 1 ? "0" + hex : hex;
        },

        rgbToHex: function (r, g, b) {
            return "#" + hiweb_field_color.componentToHex(r) + hiweb_field_color.componentToHex(g) + hiweb_field_color.componentToHex(b);
        },

        findPos: function (obj) {
            var curleft = 0, curtop = 0;
            if (obj.offsetParent) {
                do {
                    curleft += obj.offsetLeft;
                    curtop += obj.offsetTop;
                } while (obj = obj.offsetParent);
                return {x: curleft, y: curtop};
            }
            return undefined;
        }


    };
    hiweb_field_color.init();
});