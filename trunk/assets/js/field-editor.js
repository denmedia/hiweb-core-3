/**
 * Created by denmedia on 14.06.2017.
 */


jQuery(document).ready(function ($) {

    var hiweb_field_editor_init = function (idOrElement) {
        if (typeof idOrElement === 'string') {
            idOrElement = $('#' + idOrElement);
        } else {
            idOrElement = $(this).find('> textarea');
        }
        if (idOrElement.length === 0) {
            console.error('Ошибка получения ID текстового поля для создания field editor!');
        } else {
            idOrElement.froalaEditor({
                theme: 'gray',
                //autofocus: true,
                language: 'ru',
                iconsTemplate: 'font_awesome_5',
                fontSize: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 18, 20, 24, 28, 34, 36, 44, 50, 56, 64, 74, 88, 96, 128],
                toolbarButtons: ['paragraphFormat', 'fontSize', '|', 'bold', 'italic', 'formatUL', 'formatOL', 'quote', 'align', 'insertLink', 'html', 'insertTable', '|', 'strikeThrough', 'underline', 'subscript', 'superscript', 'insertHR', 'color', 'clearFormatting', 'specialCharacters', 'outdent', 'indent', 'undo', 'redo', 'help', 'fullscreen'],
                toolbarButtons2: ['', '', , '|', '', 'inlineStyle', '|', '', '', '-', 'insertImage', 'insertVideo', 'embedly', 'insertFile', 'insertTable', '|', 'emoticons', 'selectAll', '|', 'print', 'spellChecker', '', '', '|'],
                htmlAllowedTags: ['a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 'b', 'base', 'bdi', 'bdo', 'blockquote', 'br', 'button', 'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'hgroup', 'hr', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'menu', 'menuitem', 'meter', 'nav', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'pre', 'progress', 'queue', 'rp', 'rt', 'ruby', 's', 'samp', 'script', 'style', 'section', 'select', 'small', 'source', 'span', 'strike', 'strong', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track', 'u', 'ul', 'var', 'video', 'wbr']
            });
            idOrElement.froalaEditor('events.focus');
        }
    };


    $('.hiweb-field-editor').each(hiweb_field_editor_init);
    $('body').on('init_3', '.hiweb-field-editor', hiweb_field_editor_init);

});

