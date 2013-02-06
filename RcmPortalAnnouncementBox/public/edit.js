/**
 * RcmPortalAnnouncementBox
 *
 * JS for editing RcmPortalAnnouncementBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmPortalAnnouncementBox
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   http://www.nolicense.com None
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmPortalAnnouncementBoxEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmPortalAnnouncementBoxEdit}
     */
    var me = this;

    /**
     * jQuery object for the two links
     *
     * @type {Object}
     */
    var aTags = container.find('a');

    /**
     * Background image jQuery object
     *
     * @type {Object}
     */

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function(){

       // container.find('.urlContainer').attr('class','dottedeUrlContainer');

        //Double clicking will show properties dialog
        container.delegate('div', 'dblclick', function(){
            me.showEditDialog();
        });

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items:{
                edit:{
                    name:'Edit Properties',
                    icon:'edit',
                    callback:function () {
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
            'href': aTags.attr('href')
        }
    };

    me.getAssets = function(){
        var saveData = me.getSaveData();
        return [saveData.href];
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    me.showEditDialog = function () {

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .addInput('href', 'Link Url', aTags.attr('href'))
            .dialog({
                title:'Properties',
                modal:true,
                width:620,
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {

                        //Get user-entered data from form
                        aTags.attr('href', form.find('[name=href]').val());

                        $(this).dialog("close");
                    }
                }
            });

    };
};