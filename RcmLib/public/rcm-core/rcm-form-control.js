var rcmFormControl = new function () {

    var self = this;

    /**
     * submitOnEnter
     * @param form
     * @param event
     * @returns {boolean}
     */
    self.submitOnEnter = function (form, event) {
        var keycode;
        if (window.event) {
            keycode = window.event.keyCode;
        } else if (event) {
            keycode = event.which;
        } else {
            return true;
        }

        if (keycode == 13) {
            self.protectSubmit();
            return false;
        }

        return true;
    };

    /**
     * protectSubmit
     * @param form
     */
    self.protectSubmit = function(form){

        if (form.hasClass('processing')) {
            return false;
        }
        form.addClass('processing');
        form.submit();
        return true;
    };
};

/**
 * This ensures forms can only be submitted once and shows that it is loading via css
 */
$().ready(function () {
    $.each($('form'), function () {
        var form = $(this);
        form.on('submit', function () {
            return rcmFormControl.protectSubmit(form);
        });
    });
});
