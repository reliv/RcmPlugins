function RcmStandardDialogStrategy(dialogHelper) {
    var me = this;

    me.dialog = dialogHelper;

    me.dialogTemplate = "#RcmStandardDialogTemplate";

    me.load = function (url, title, data) {
        var template = jQuery("#RcmStandardDialogTemplate").find('.modal-dialog').clone();
        var contentBody = template.find(".modal-body");

        jQuery(template).find('.modal-title').html(title);

        jQuery(contentBody).load(url, data, function (response, status, xhr) {

            if (status == "error") {
                jQuery(contentBody).html(xhr.responseText);
            }

            var contentType = xhr.getResponseHeader('Content-Type');

            if (contentType.indexOf('application/json') > -1) {
                var jsonResponse = jQuery.parseJSON(xhr.responseText);

                if (jsonResponse.redirect !== undefined) {
                    window.location.replace(jsonResponse.redirect);
                }
            }

            me.loadCallback(template, response);
        });
    };

    me.postOpen = function () {
        return;
    };

    me.loadCallback = function (template, response) {

        jQuery("#AdminDialogWindow").html(template);

        me.dialog.open();
    };
}