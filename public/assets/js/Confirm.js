$(document).ready(function () {
    $('button').on('click', function (e) {
        e.preventDefault();
        if ($(this).attr('type') == 'submit') {
            if (confirm('Are you sure you want to do that !? ')) {
                $('form').submit();
            }
        }
    });
});