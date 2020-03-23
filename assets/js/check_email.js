import "./app";

let $ = require('jquery');
const keyUpWait = 1500;

$('#registration_form_email').on('keyup',
    debounce(function () {
        let $this = $(this);

        $.ajax({
            url: 'check_email',
            type: 'POST',
            data: JSON.stringify({
                'email': $this.val(),
            }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',

            success: function (data) {
                if (data.is_email_exist === true) {
                    alert('The username is already registered.\nPlease, choose another name.');
                }
            },
        })
    }, keyUpWait)
);

function debounce(func, wait, immediate) {
    let timeout;
    return function () {
        let context = this, args = arguments;
        let later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        let callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}
