var rcmFormControl = new function () {

    var self = this;

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
            form.submit();
            return false;
        }

        return true;
    }
};
