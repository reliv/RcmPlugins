function RcmAdminController(dialog, adminMenu)
{
    var me = this;

    me.dialog = dialog;

    me.adminMenu = adminMenu;

    me.init = function()
    {
        me.dialog.init();
        me.adminMenu.init();
    };

    me.setAdminMenu = function(adminMenu)
    {
        me.adminMenu = adminMenu;
    };

    me.getAdminMenu = function()
    {
        return me.adminMenu;
    };

    me.setDialog = function (dialog)
    {
        me.dialog =  dialog;
    };

    me.getDialog = function getDialog()
    {
        return me.dialog;
    };
}

function RcmNavMenuHelper(dialog)
{
    var me = this;

    me.dialog = dialog;

    me.init = function()
    {
        jQuery(".RcmAdminMenu").find("a").click(function(event){
            event.preventDefault();

            var link = jQuery(this);

            var url = link.attr('href');

            if (link.parent().hasClass('RcmForm')) {
                me.dialog.switchStrategy('RcmForm')
            }

            me.dialog.load(url);
        });
    };

    me.setDialog = function (dialog)
    {
        me.dialog =  dialog;
    };

    me.getDialog = function getDialog()
    {
        return me.dialog;
    };
}

function RcmStandardDialogStrategy(dialogHelper)
{
    var me = this;

    me.dialog = dialogHelper;

    me.dialogTemplate = "#RcmStandardDialogTemplate";

    me.load = function(url,data)
    {
        var template = jQuery("#RcmStandardDialogTemplate").find('.modal-dialog').clone();
        var contentBody = template.find(".modal-body");

        jQuery(contentBody).load(url, data, function(response) {
            me.loadCallback(template, response);
        });
    };

    me.postOpen = function()
    {
        return;
    };

    me.loadCallback = function(template, response) {
        var title = jQuery(response).filter('title').text();
        jQuery(template).find('.modal-title').html(title);

        jQuery("#AdminDialogWindow").html(template);

        me.dialog.open();
    };
}

function RcmFormStrategy(dialogHelper)
{
    var me = this;

    me.dialog = dialogHelper;

    me.dialogTemplate = "#RcmStandardDialogTemplate";

    me.load = function(url,data)
    {
        me.dialog.stratageies.standard.load(url, data);
    };

    me.postOpen = function()
    {
        jQuery(".saveBtn").click(function(event) {
            var form = me.dialog.getDialogWindow().find('form');
            var data = form.serializeArray();
            var actionUrl = form.attr('action');
            me.dialog.load(actionUrl, data);
        })
    };
}

function RcmAdminDialogHelper()
{
    var me = this;

    me.dialogWindowId = "#AdminDialogWindow";

    me.currentStatagy = null;

    me.stratageies = null;

    me.init = function()
    {
        me.stratageies = {
            'RcmForm'  : new RcmFormStrategy(me),
            'standard' : new RcmStandardDialogStrategy(me)
        };

        me.currentStatagy = me.stratageies.standard;
    };

    me.open = function(stategy)
    {
        jQuery(me.dialogWindowId).modal('show');

        jQuery(me.dialogWindowId).on('shown.bs.modal', function(event) {
            jQuery('.modal-dialog').draggable({handle: '.modal-header'})
        });

        me.currentStatagy.postOpen();
    };

    me.load = function(url,data)
    {
        me.currentStatagy.load(url,data);
    };

    me.switchStrategy = function(strategy)
    {
        console.log(me.stratageies);

        if (!strategy
            || me.stratageies[strategy] === undefined
            || me.stratageies[strategy] === null
        ) {
            me.currentStatagy = me.stratageies.standard;
            return;
        }

        me.currentStatagy = me.stratageies[strategy];
    };

    me.addStrategy = function(stategy)
    {
        me.stratageies.push(stategy);
    };

    me.getContentPlaceHolder = function()
    {
        return jQuery(loadPlaceHolder)
    };

    me.setDialogWindow = function(dialogWindow)
    {
        me.dialogWindow = dialogWindow;
    };

    me.getDialogWindow = function()
    {
        return $(me.dialogWindowId).find(".modal-body");
    };
}

function RcmAdminFactory()
{
    var RcmDialog = new RcmAdminDialogHelper();
    var RcmAdminMenu = new RcmNavMenuHelper(RcmDialog);
    return new RcmAdminController(RcmDialog, RcmAdminMenu);
}


var RcmAdmin = RcmAdminFactory();

jQuery(function(){
    RcmAdmin.init();
});
