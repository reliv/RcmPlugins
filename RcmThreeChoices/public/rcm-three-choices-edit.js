/**
 * RcmThreeChoices
 *
 * JS for editing RcmThreeChoices
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
var RcmThreeChoicesEdit = function (instanceId, container) {

    var self = this;

    /**
     * jQuery object for the two links
     *
     * @type {Object}
     */
    var choiceEles = container.find('.choice');
    var linkEle1 = $(choiceEles[0]).find('a.editableLink');
    var linkEle2 = $(choiceEles[1]).find('a.editableLink');
    var linkEle3 = $(choiceEles[2]).find('a.editableLink');

    /**
     * Called by content management system to make this plugin user-editable
     */
    self.initEdit = function () {

        //Double clicking will show properties dialog
        container.dblclick(self.showEditDialog);

        //Add right click menu
        $.contextMenu({
            selector: rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit Properties',
                    icon: 'edit',
                    callback: function () {
                        self.showEditDialog();
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
    self.getSaveData = function () {
        return {
            links: {
                1: linkEle1.attr('href'),
                2: linkEle2.attr('href'),
                3: linkEle3.attr('href')
            }
        }
    };

    /**
     * Displays a dialog box to edit properties
     */
    self.showEditDialog = function () {
        var link1 = $.dialogIn('url', 'Link for Button #1', linkEle1.attr('href'));
        var link2 = $.dialogIn('url', 'Link for Button #2', linkEle2.attr('href'));
        var link3 = $.dialogIn('url', 'Link for Button #3', linkEle3.attr('href'));

        var form = $('<form></form>')
            .addClass('simple')
            .appendMulti([link1, link2, link3])
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {
                        linkEle1.attr('href', link1.val());
                        linkEle2.attr('href', link2.val());
                        linkEle3.attr('href', link3.val());
                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};