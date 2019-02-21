/**
 * Password Object validator
 * @type Object
 */
function Password() {
    // regex : combine ([**1 lowercase** or [[1 uppercase] or **1 numeric**]]) string length > 7
    this.mediumReg = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{7,})");
    // regex : 1 lowercase, 1 uppercase, 1 numeric, 1 special caract, string length > 8
    this.hardReg = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\\$%\\^&\\*])(?=.{8,})");
    // hard test on password
    this.checkHard = function (password) {
        return this.hardReg.test(password);
    };
    // medium test on password
    this.checkMedium = function (password) {
        return this.mediumReg.test(password);
    };
    // use methods object to alert user on bad password
    this.checkRegex = function (password, messageLocation, conf, where, button) {
        if (this.checkHard(password)) {
            $(messageLocation).text('Strong').css({'color': 'green'});
        } else if (this.checkMedium(password)) {
            $(messageLocation).text('Weak').css({'color': 'blue'});
        } else {
            $(messageLocation).text('Not allow').css({'color': 'red'});
        }
        // required message
        if (!password) {
            $(messageLocation).text('Required * ').css({'color': 'red'});
        }
        if (conf.val()) {
            this.match(password, conf, where, button);
        }
    };
    // test if two password match
    this.match = function (password, conf, messageLocation, button) {
        // required message
        if (!conf) {
            $(messageLocation).text('Required * ').css({'color': 'red'});
        }
        console.log(password);
        console.log(conf);
        console.log(0);
        if (password == conf) {
            $(messageLocation).text('Matching').css({'color': 'green'});
            $(button).prop('disabled', false);
            console.log(1);
            // return true;
        } else {
            console.log(2);
            $(messageLocation).text('Different').css({'color': 'red'});
            $(button).prop('disabled', true);
            // return false;
        }

    };
};