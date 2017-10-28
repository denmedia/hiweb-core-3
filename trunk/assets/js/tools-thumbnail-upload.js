/**
 * Created by denmedia on 11.03.2017.
 */

jQuery(document).ready(function ($) {

    var hw_tool_thumbnail_upload = {


        init: function () {
            $('.wp-list-table tbody tr[id]').each(function () {
                ///
                var tr = $(this);
                var upload_root = tr.find('.thumb_hw_upload_zone');

                if (tr.attr('id').match(/^post-/) != null) {
                    var post_id = tr.attr('id').replace('post-', '');
                    var type = 'post';
                } else {
                    var post_id = tr.attr('id').replace('tag-', '');
                    var type = 'taxonomy';
                }
                ///
                hw_tool_thumbnail_upload._make_events(upload_root, post_id, type);
                hw_tool_thumbnail_upload._make_upload_zone(upload_root, post_id, type);
            });

            var showDrag = false,
                timeout = -1;

            $('html, .thumb_hw_upload_zone').bind('dragenter', function () {
                $('.thumb_hw_upload_zone').addClass('global-dragover');
                showDrag = true;
            }).bind('dragover', function () {
                showDrag = true;
            }).bind('dragleave', function (e) {
                showDrag = false;
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    if (!showDrag) {
                        $('.thumb_hw_upload_zone').removeClass('global-dragover');
                    }
                }, 200);
            });

        },

        open_media: function (place, post_id, type) {
            var gallery_window = wp.media({
                title: 'Выбор изображения',
                library: {type: 'image'},
                multiple: false,
                button: {text: 'Select Image'}
            });
            gallery_window.on('select', function () {
                var data = gallery_window.state().get('selection').first().toJSON();
                ///FIND IMG URL
                if (!data.hasOwnProperty('id') || !data.hasOwnProperty('type') || data.type !== 'image' || !data.hasOwnProperty('sizes')) {
                    alert('Ошибка выбора файла');
                } else {
                    var default_sizes = ['thumbnail', 'medium', 'full'];
                    var img_src = false;
                    for (var size in data.sizes) {
                        if (default_sizes.indexOf(size) > -1) {
                            img_src = data.sizes[size]['url'];
                            break;
                        }
                    }
                    if (img_src === false && data.hasOwnProperty('url')) {
                        img_src = data.url;
                    }
                    ///
                    if (img_src === false) {
                        alert('Ошибка при поиске URL');
                    } else {
                        place.find('[data-img]').css('background-image', 'url(' + img_src + ')');
                        place.attr('data-has-thumbnail', '1').attr('data-is-process', '1');
                        $.ajax({
                            url: ajaxurl + '?action=hiweb-tools-thumbnail-upload-post-type-set',
                            type: 'post',
                            dataType: 'json',
                            data: {do: 'upload', post_id: post_id, thumbnail_id: data.id, type: type},
                            success: function (data) {
                                place.attr('data-is-process', '0');
                                if (data.success) {
                                    place.attr('data-has-thumbnail', '1');
                                } else {
                                    alert(data.data);
                                    console.warn(data);
                                }
                            },
                            error: function (data) {
                                place.attr('data-is-process', '0');
                                console.warn(data);
                            }
                        });
                    }
                }
            });
            gallery_window.open();
        },

        _make_events: function (place, post_id, type) {
            ///CTRL
            $(place).find('[data-ctrl-btn]').on('click', function () {
                var action = $(this).attr('data-ctrl-btn');
                switch (action) {
                    case 'upload':
                        place.trigger('click');
                        break;
                    case 'media':
                        hw_tool_thumbnail_upload.open_media(place, post_id, type);
                        break;
                    case 'remove':
                        hw_tool_thumbnail_upload._click_remove(place, post_id, type);
                        break;
                }
            });
        },

        _make_upload_zone: function (place, post_id, type) {
            if (typeof Dropzone !== 'function') return;
            ///
            var upload_zone_id = $(place).attr('id'),
                showDrag = false,
                timeout = -1;
            if (jQuery("#" + upload_zone_id).length === 0) return;
            ///
            new Dropzone("#" + upload_zone_id, {
                url: ajaxurl + '?action=hiweb-tools-thumbnail-upload-post-type-uploading',
                headers: {'postid': post_id, 'posttype': type},
                maxFilesize: 20,
                filesizeBase: 1024,
                previewsContainer: false,
                type: 'post',
                dataType: 'json',
                data: {do: 'upload', post_id: post_id},
                dragenter: function () {
                    showDrag = true;
                    place.addClass('dragover');
                },
                dragover: function () {
                    showDrag = true;
                },
                dragleave: function () {
                    showDrag = false;
                    clearTimeout(timeout);
                    timeout = setTimeout(function () {
                        if (!showDrag) {
                            place.removeClass('dragover');
                        }
                    }, 200);
                },
                addedfile: function (data) {
                    place.removeClass('dragover');
                    place.attr('data-is-process', '1');
                    $('.global-dragover').removeClass('global-dragover');
                },
                complete: function (answer) {
                    place.attr('data-is-process', '0');
                    place.removeClass('dragover');
                    $('.global-dragover').removeClass('global-dragover');
                    var response = answer.xhr.response;
                    var data = $.parseJSON(response);
                    if (typeof data == 'object') {
                        if (data.success == false) {
                            alert('в ходе загрузки произошла ошибка: ' + data.data);
                        } else {
                            place.attr('data-has-thumbnail', '1');
                            place.find('[data-img]').css('background-image', 'url(' + data.data + ')');
                        }
                    } else {
                        alert('В ходе загрузки произошла ошибка: 1');
                        console.warn(data);
                    }
                }
            });
        },

        _click_remove: function (place, post_id, type) {
            place.removeClass('dragover');
            place.attr('data-is-process', '1');
            $.ajax({
                url: ajaxurl + '?action=hiweb-tools-thumbnail-upload-post-type-remove',
                type: 'post',
                dataType: 'json',
                data: {do: 'upload', post_id: post_id},
                success: function (data) {
                    place.attr('data-is-process', '0');
                    if (data.success) {
                        place.attr('data-has-thumbnail', '0');
                    } else {
                        console.warn(data);
                        alert(data.data);
                    }
                },
                error: function (data) {
                    place.attr('data-is-process', '0');
                    console.warn(data);
                }
            });
        }

    };

    hw_tool_thumbnail_upload.init();

    ////
});