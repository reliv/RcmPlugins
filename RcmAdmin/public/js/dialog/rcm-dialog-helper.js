function RcmAdminDialogHelper() {
    var me = this;

    me.dialogWindowId = "#AdminDialogWindow";

    me.currentStatagy = null;

    me.stratageies = null;

    me.currentTitle = null;

    me.init = function () {
        me.stratageies = {
            'RcmForm': new RcmFormStrategy(me),
            'standard': new RcmStandardDialogStrategy(me)
        };

        me.currentStatagy = me.stratageies.standard;
    };

    me.open = function (stategy) {
        jQuery(me.dialogWindowId).modal('show');

        jQuery(me.dialogWindowId).on('shown.bs.modal', function (event) {
            jQuery('.modal-dialog').draggable({handle: '.modal-header'})
        });

        me.currentStatagy.postOpen();
    };

    me.load = function (url, title, data) {
        me.currentTitle = title;

        me.currentStatagy.load(url, title, data);
    };

    me.switchStrategy = function (strategy) {
        if (!strategy
            || me.stratageies[strategy] === undefined
            || me.stratageies[strategy] === null
            ) {
            me.currentStatagy = me.stratageies.standard;
            return;
        }

        me.currentStatagy = me.stratageies[strategy];
    };

    me.addStrategy = function (stategy) {
        me.stratageies.push(stategy);
    };

    me.getContentPlaceHolder = function () {
        return jQuery(loadPlaceHolder)
    };

    me.setDialogWindow = function (dialogWindow) {
        me.dialogWindow = dialogWindow;
    };

    me.getDialogWindow = function () {
        return $(me.dialogWindowId).find(".modal-body");
    };
}