$(document).ready(function () {
    /**
     * front/subscribe/_forms
     */

    // Show the form that match to click
    $(".subWho").on('click', function () {
        // which type of user ?
        if ($(this).attr('data-value') == 'provider') {
            $('#subProvider').show();
        }
        if ($(this).attr('data-value') == 'surfer') {
            $('#subSurfer').show();
        }
        // hide choices box
        $('.subscribeChoice').hide();
        // show whats the choice in title
        $('.subChoiceTitle').next().text('en tant que ' + $(this).text());
        // show arrow to go back and restart process
        $('.subChoiceTitle').parent().prev().html('<i class="fa fa-arrow-left subBack"></i>');

        // On click on arrow back => restart process
        $('.subBack').on('click', function () {
            // show choices box
            $('.subscribeChoice').show();
            $('.messageAlert').css({'display':'none'});
            // hide arrow and type of user
            $('.subChoiceTitle').next().text('');
            $('.subChoiceTitle').parent().prev().html('');
            // hide form
            $('form').hide();
        });
    });

    // if type exists (the user come from log in page)
    if ($('.subscribeChoice').attr('data-type') != 'none') {
        // hide choices box
        $('.subscribeChoice').hide();
        // show the good form
        if ($('.subscribeChoice').attr('data-type') == 'provider') {
            $('#subProvider').show();
        } else if ($('.subscribeChoice').attr('data-type') == 'surfer') {
            $('#subSurfer').show();
        }

        // hide choices box
        $('.subscribeChoice').hide();
        // show whats the choice in title
        $('.subChoiceTitle').next().text('en tant que ' + $('.subscribeChoice').attr('data-type'));
        // show arrow to go back and restart process
        $('.subChoiceTitle').parent().prev().html('<i class="fa fa-arrow-left subBack"></i>');

        // On click on arrow back => restart process
        $('.subBack').on('click', function () {
            // show choices box
            $('.subscribeChoice').show();
            $('.messageAlert').css({'display':'none'});
            // hide arrow and type of user
            $('.subChoiceTitle').next().text('');
            $('.subChoiceTitle').parent().prev().html('');
            // hide form
            $('form').hide();
        });
    }


    /**
     * front/subscribe/_step2
     */
        // 1- instance of Password
    var Validator = new Password();
    var what;
    // get if dom element exists (jquery check on lenght property)
    var surfer = $('#surfer').length;
    var provider = $('#provider').length;
    // 2- check what type of user is
    if (provider) {
        what = $('#sub_provider_password');
        // 3- check if password contains all caracteres needed
        $(what).on('keyup blur', function () {
            var password = $(this).val();
            var conf = $('#passwordConf');
            Validator.checkRegex(password, $("#passwordPower"), conf, $('#passwordMatch'), $('.SaveUser'));
        });
        // 4- check if password match to passwordConf
        $('#passwordConf').on('focus keyup', function () {
            var conf;
            var password;
            conf = $(this).val();
            password = $("#sub_provider_password").val();
            Validator.match(password, conf, $('#passwordMatch'), $('.SaveUser'));
        });
    }
    if (surfer) {
        what = $('#sub_surfer_password');
        // 3- check if password contains all caracteres needed
        $(what).on('keyup blur', function () {
            var password = $(this).val();
            var conf = $('#passwordConf');
            Validator.checkRegex(password, $("#passwordPower"), conf, $('#passwordMatch'), $('.SaveUser'));
        });
        // 4- check if password match to passwordConf
        $('#passwordConf').on('focus keyup', function () {
            var conf = $(this).val();
            var password = $("#sub_surfer_password").val();
            Validator.match(password, conf, $('#passwordMatch'), $('.SaveUser'));
        });
    }


});