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
});