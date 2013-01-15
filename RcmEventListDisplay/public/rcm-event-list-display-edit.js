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
 * @package   RcmPlugins\RcmEventListDisplay
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

/**
 * Synchronously grab dependency object file(s)
 */
$.ajax({
    async: false,
    url: '/modules/rcm/js/admin/ajax-edit-helper.js',
    dataType: 'script'
});
$.ajax({
    async: false,
    url: '/modules/rcm-event-calender-core/rcm-event-manager.js',
    dataType: 'script'
});

var RcmEventListDisplayEdit = function (instanceId, container) {

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

    var ajaxEditHelper = new AjaxEditHelper(instanceId, 'rcm-event-list-display');

    var eventManager = new RcmEventManager(
        container.find('dataContainer').attr('data-eventCategoryId')
    );

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {
        ajaxEditHelper.getDataAndDefaultDataFromServer(
            me.completeEditInit
        );
    };

    /**
     * Completes edit init process after we get data from server
     *
     * @param {Object} returnedData
     * @param {Object} returnedDefaultData
     */
    this.completeEditInit = function(returnedData, returnedDefaultData){
        data = returnedData;
        defaultData = returnedDefaultData;

        //Double clicking will show properties dialog
        container.delegate('.event', 'dblclick', me.handleOpenEventManager);

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId) + ' .event',
            //Here are the right click menu options
            items:{
                addEvent:{
                    name:'Add New Event',
                    icon:'edit',
                    callback:function(){
                        eventManager.addEvent(
                            me.render
                        );
                    }
                },
                deleteEvent:{
                    name:'Delete this Event',
                    icon:'delete',
                    callback:function(){
                        eventManager.deleteEvent(
                            $(this).attr('data-eventId'),
                            me.render
                        );
                    }
                },
                editEvent:{
                    name:'Edit this Event',
                    icon:'edit',
                    callback:function(){
                        eventManager.editEvent(
                            $(this).attr('data-eventId'),
                            me.render
                        )
                    }
                },
                'sep1':'-',
                edit:{
                    name:'Properties for this Event List Display',
                    icon:'edit',
                    callback:me.showEditDialog
                }
            }
        });

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId) + ' .noEvent',
            //Here are the right click menu options
            items:{
                addEvent:{
                    name:'Add New Event',
                    icon:'edit',
                    callback:function(){
                        eventManager.addEvent(
                            me.render
                        );
                    }
                },
                'sep1':'-',
                edit:{
                    name:'Properties for this Event List Display',
                    icon:'edit',
                    callback:me.showEditDialog
                }
            }
        });
    };

    this.requestCategories = function(callBack){
        $.getJSON(
            '/rcm-event-calender/categories',
            function(result) {
                var categories=[];
                $.each(result, function(){
                    categories[this.categoryId]=this.name;
                });
                callBack(categories);
            }
        );
    }

    this.handleOpenEventManager = function(){
        var eventId = $(this).attr('data-eventId');
        alert(eventId);
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    this.getSaveData = function () {
        return data;
    };

    /**
     * Displays a dialog box to edit href and image src
     *
     */
    this.showEditDialog = function () {
        me.requestCategories(me.continueShowEditDialog);
    }

    this.continueShowEditDialog = function(categories){
        //Create and show our edit dialog
        var form = $('<form></form>').addClass('simple');
        form.addSelect('category', 'Event Category', categories, data.category);
        form.addInput(
            'shareThisKey',
            '"ShareThis" Published Key',
            data.shareThisKey
        );
        form.append('<p style="font-weight:bold;">Translations:</p>')
        $.each(defaultData.translate, function(key, value){
            form.addInput(key, value, data.translate[key] );
        });

        form.dialog({
                title:'Properties',
                modal:true,
                width:620,
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {

                        //Get user-entered data from form
                        data.category= form.find('[name=category]').val();
                        data.shareThisKey= form.find('[name=shareThisKey]').val();

                        $.each(defaultData.translate, function(key){
                            data.translate[key] = form.find('[name="'+key+'"]')
                                .val();
                        });

                        me.render();

                        $(this).dialog("close");
                    }
                }
            });

    };

    this.render = function(){
        container.load(
            '/rcm-plugin-admin-proxy/rcm-event-list-display/'
                + instanceId + '/preview'
            ,data
            ,function(){
                rcmSocialButtonsReload();
            }
        );
    }
};