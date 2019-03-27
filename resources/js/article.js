// require('./bootstrap');
// require('jquery');
require('summernote/dist/summernote-lite.css');
require('summernote/dist/summernote-lite');
require('bootstrap-tagsinput/dist/bootstrap-tagsinput.css');
require('bootstrap-tagsinput/dist/bootstrap-tagsinput');

$(document).ready(function () {
    $('#context').summernote({
        placeholder: 'Content',
        tabsize: 2,
        height: 350,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen'/*, 'codeview' */]],   // remove codeview button
            ['help', ['help']]
        ],
    });
    $('#tag').tagsinput({
        trimValue: true,
        tagClass: 'big',
        maxTags: 5,
        focusClass: 'my-focus-class'
    });
    $('#do-search').on('click', function () {
        window.location.href = '//' + window.location.host + '/article/search?tags=' + $('#tag').val()
    });
    $('#do-suggestion-search').on('click', function () {
        window.location.href = '//' + window.location.host + '/admin/article/suggest?tags=' + $('#tag').val()
    });
    $('#select-language a').on('click', function () {
        let lang = this.dataset.language;
        document.cookie = `lang_id=${lang}`
    })
});