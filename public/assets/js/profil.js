$(document).ready(function () {

    var Validator = new Password();

    // check on keyup and blur if password respect contrains
    $('#new').on('keyup blur', function () {
        var password = $(this).val();
        var conf = $("#confirm");
        Validator.checkRegex(password, $(this).next(), conf, $(conf).next(), $("#changePassword"));
    });

    // check on keyup and focus if new password is confirmed correctly
    $('#confirm').on('keyup focus', function () {
        var conf = $(this).val();
        var password = $("#new").val();
        Validator.match(password, conf, $(this).next(), $("#changePassword"));
    });
});