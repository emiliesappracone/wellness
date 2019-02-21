$(document).ready(function () {
    $('button.onDelete').on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if ($(this).attr('type') == 'submit') {
            if (confirm('Are you sure you want to do that !? ')) {
                $('form#'+ id).submit();
            }
        }
    });
});