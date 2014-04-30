/**
 * This finds any form with attribute data-ng-form-double-submit-protect and ensures
 * that each form can only be submitted once and shows that it is loading via css.
 *
 * setTimeout(func,0) is similar to $.ready()
 */
function formDoubleClickSubmitProtectInit() {
    var forms = document.querySelectorAll("form[data-ng-form-double-submit-protect]");
    for (var i = 0; i < forms.length; ++i) {
        forms[i].addEventListener('submit', function () {
            if (this.className.indexOf('processing') != -1) {
                return false;
            }
            this.className = this.className + ' processing';
            console.log('submitted');
            this.submit();
            return true;
        });
    }
}

if (typeof jQuery == 'function') {
    jQuery().ready(formDoubleClickSubmitProtectInit)
} else {
    //Poor replacement for jQuery().ready that doesn't always work well
    setTimeout(formDoubleClickSubmitProtectInit, 0);
}