function RcmStandardDialogStrategy(dialogHelper)
{
    var me = this;

    me.dialog = dialogHelper;

    me.dialogTemplate = "#RcmStandardDialogTemplate";

    me.load = function(url,title, data)
    {
        var template = jQuery("#RcmStandardDialogTemplate").find('.modal-dialog').clone();
        var contentBody = template.find(".modal-body");

        jQuery(template).find('.modal-title').html(title);

        jQuery(contentBody).load(url, data, function(response) {
            me.loadCallback(template, response);
        });
    };

    me.postOpen = function()
    {
        return;
    };

    me.loadCallback = function(template, response) {

        jQuery("#AdminDialogWindow").html(template);

        me.dialog.open();
    };
}