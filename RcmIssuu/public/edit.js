/**
 * RcmCallToActionBox
 *
 * JS for editing RcmCallToActionBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmIssuuEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     */
    var me = this;
    me.instanceId = instanceId;
    me.container = container;

    me.embedContainer = me.container.find(".issuuEmbedContainer");

    me.userName = me.embedContainer.attr('data-userName');
    me.docName = me.embedContainer.attr('data-docName');
    me.embed = me.embedContainer.attr('data-embed');

    me.defualtPageSize = 30;

    me.apiProcessor = new RcmIssuuApiProcessor();
    me.adminEditForm = new RcmIssuuEditDialogForm(me.apiProcessor);

    me.initEdit = function () {
        $('head').append($('<link rel="stylesheet" type="text/css" href=""/>')
            .attr('href', '/modules/rcm-issuu/edit.css'));

        //Add right click menu
        $.contextMenu({
            selector: '[data-rcmPluginInstanceId="' + instanceId + '"]',
            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit Properties',
                    icon: 'edit',
                    callback: function () {
                        me.showEditDialog();
                    }
                }
            }
        });
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        return {
            'userName': me.userName,
            'docName': me.docName,
            'embed': me.embed
        }
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    me.showEditDialog = function () {
        me.adminEditForm.initSelected(me.userName, me.docName);
        me.adminEditForm.getForm().dialog({
            title: 'Properties',
            modal: true,
            width: 620,
            buttons: {
                Cancel: function () {
                    $(this).dialog("close");
                },
                Ok: me.handleOkButton
            }
        });
    };

    me.handleOkButton = function () {
        var document = me.adminEditForm.getCurrentDocument();

        me.userName = document.getUserName();
        me.docName = document.getName();
        me.embed = document.getEmbedHtml();

        me.embedContainer.html(document.getEmbedHtml());
        me.embedContainer.attr('data-embed', me.embed);
        me.embedContainer.attr('data-userName', me.userName);
        me.embedContainer.attr('data-docName', me.docName);

        $(me.container).find('.issuuembed').addClass('fit-container');
        fitContainer();
        $(this).dialog("close");
    }
};