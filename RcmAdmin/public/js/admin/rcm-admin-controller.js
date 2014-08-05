function RcmAdminController(dialog, adminMenu) {
    var me = this;

    me.dialog = dialog;

    me.adminMenu = adminMenu;

    me.init = function () {
        me.dialog.init();
        me.adminMenu.init();
    };

    me.setAdminMenu = function (adminMenu) {
        me.adminMenu = adminMenu;
    };

    me.getAdminMenu = function () {
        return me.adminMenu;
    };

    me.setDialog = function (dialog) {
        me.dialog = dialog;
    };

    me.getDialog = function getDialog() {
        return me.dialog;
    };
}