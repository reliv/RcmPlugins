function RcmFormStrategy(dialogHelper) {
    var me = this;

    me.dialog = dialogHelper;

    me.dialogTemplate = "#RcmStandardDialogTemplate";

    me.load = function (url, title, data) {
        me.dialog.stratageies.standard.load(url, title, data);
    };

    me.postOpen = function () {
        jQuery(".saveBtn").click(function (event) {
            var form = me.dialog.getDialogWindow().find('form');
            var data = form.serializeArray();
            var actionUrl = form.attr('action');
            me.dialog.load(actionUrl, me.dialog.currentTitle, data);
        })
    };
}