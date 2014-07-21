function RcmBlankDialogStrategy(dialogHelper) {
    var me = this;

    me.dialog = dialogHelper;

    me.load = function (url, title, data) {

        jQuery(me.dialog.dialogWindowId).load(url, data, function (response, status, xhr) {

            var contentType = xhr.getResponseHeader('Content-Type');

            if (contentType.indexOf('application/json') > -1) {
                var jsonResponse = jQuery.parseJSON(xhr.responseText);

                if (jsonResponse.redirect !== undefined) {
                    window.location.replace(jsonResponse.redirect);
                }
            }

            me.dialog.open();
        });
    };

    me.postOpen = function () {
        return;
    };
}