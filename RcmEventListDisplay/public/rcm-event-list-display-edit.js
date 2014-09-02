/**
 * RcmEventListDisplay
 *
 * JS for editing RcmEventListDisplay
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

$.ajax({
    async: false,
    url: '/modules/rcm-event-calender-core/rcm-event-manager.js',
    dataType: 'script'
});
/**
 * requires AjaxPluginEditHelper which should be included by rcm-admin
 */
var RcmEventListDisplayEdit = function (instanceId, container, pluginHandler) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmEventListDisplayEdit}
     */
    var me = this;

    /**
     * Settings from db
     * @type {Object}
     */
    var data;

    /**
     * Default settings from config json file
     * @type {Object}
     */
    var defaultData;

    var ajaxEditHelper = new AjaxPluginEditHelper(instanceId, container, pluginHandler);

    var eventManager = new RcmEventManager(
        container.find('dataContainer').attr('data-eventCategoryId')
    );

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {
        ajaxEditHelper.ajaxGetInstanceConfigs(me.completeEditInit);
    };

    /**
     * Completes edit init process after we get data from server
     *
     * @param {Object} returnedData
     * @param {Object} returnedDefaultData
     */
    me.completeEditInit = function (returnedData, returnedDefaultData) {
        data = returnedData;
        defaultData = returnedDefaultData;

        //Double clicking will show properties dialog
        container.delegate('.event', 'dblclick', me.handleOpenEventManager);

        //Add right click menu
        $.contextMenu(
            {
                selector: rcm.getPluginContainerSelector(instanceId),
                //Here are the right click menu options
                items: {
                    eventManager: {
                        name: 'Open Event Manager (Add/Remove/Edit Events)',
                        icon: 'edit',
                        callback: eventManager.showManager
                    },
                    'sep1': '-',
                    edit: {
                        name: 'Properties for this Event List Display',
                        icon: 'edit',
                        callback: me.showEditDialog
                    }
                }
            }
        );
    };

    me.handleOpenEventManager = function () {
        var eventId = $(this).attr('data-eventId');
        alert(eventId);
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return data;
    };

    me.showEditDialog = function () {
        eventManager.getCategories(me.showContinueEditDialog);
    };

    /**
     * Displays a dialog box to edit href and image src
     *
     */
    me.showContinueEditDialog = function (categories) {
        //Create and show our edit dialog
        var form = $('<form></form>').addClass('simple');
        var categoryId = $.dialogIn(
            'select',
            'Event Category',
            categories,
            data.categoryId
        );

        var shareThisKey = $.dialogIn(
            'text',
            '"ShareThis" Published Key',
            data.shareThisKey
        );

        var directions = $.dialogIn(
            'text',
            'Directions',
            data.translate['directions']
        );

        var noEvents = $.dialogIn(
            'text',
            'No Events To Display',
            data.translate['noEvents']
        );

        form.append();

        form.append(
            categoryId,
            shareThisKey,
            '<p style="font-weight:bold;">Translations:</p>',
            directions
            , noEvents
        );
        form.dialog({
            title: 'Properties',
            modal: true,
            width: 620,
            buttons: {
                Cancel: function () {
                    $(this).dialog("close");
                },
                Ok: function () {

                    //Get user-entered data from form
                    data.categoryId = categoryId.val();
                    data.shareThisKey = shareThisKey.val();
                    data.translate['directions'] = directions.val();
                    data.translate['noEvents'] = noEvents.val();

                    me.render();

                    $(this).dialog("close");
                }
            }
        });

    };

    me.render = function () {
        container.load(
            '/api/admin/instance-configs'
                + instanceId
            , data
            , function () {
                rcmSocialButtonsReload();
            }
        );
    };

    //Re-render the list if events change in the event manager
    $('body').bind('rcmEventManagerRender', me.render);
};