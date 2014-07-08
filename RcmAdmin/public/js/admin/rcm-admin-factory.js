function RcmAdminFactory() {
    var RcmDialog = new RcmAdminDialogHelper();
    var RcmAdminMenu = new RcmNavMenuHelper(RcmDialog);
    return new RcmAdminController(RcmDialog, RcmAdminMenu);
}

var RcmAdmin = RcmAdminFactory();

jQuery(function () {
    RcmAdmin.init();
});