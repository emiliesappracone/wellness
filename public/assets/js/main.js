$(document).ready(function () {
    /**
     * Front
     */
    if ($('div').hasClass('front')) {
        $('.providers').select2({});

        $('.search').next().hide();

        $('.search').on('click', function () {
            if ($(this).next().attr('style') == 'display: none;') {
                $(this).next().show();
            } else {
                $(this).next().hide();
            }
        });
    }
    /**
     * Back
     */
    if ($('div').hasClass('admin')) {
        $('textarea.ckeditor').ckeditor();
    }

});