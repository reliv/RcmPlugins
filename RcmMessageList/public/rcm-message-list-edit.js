/**
 * RcmMessageListEdit
 * @param instanceId
 * @param container
 * @constructor
 */
var RcmMessageListEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmGoogleSearchBoxEdit}
     */
    var me = this;

    me.propertyName = 'User Message List';

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {
        //Add right click menu
        $.contextMenu({
            selector: rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit ' + me.propertyName,
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
            'source': null,
            'level': null,
            'hasViewed': null
        }
    };


    me.showEditDialog = function () {

        var saveData = {};
        saveData.source = $.dialogIn(
            'text',
            'Message Sources to Display (source) - Leave blank to dispaly ALL.',
            null
        );
        saveData.level = $.dialogIn(
            'select',
            'Show Messages of Level - Use if only specific message levels need be displayed',
            {2: 'CRITICAL Only', 4: 'ERROR Only', 8: 'WARNING Only', 16: 'INFO Only', 32: 'SUCCESS Only', 0: 'All'},
            null
        );
        saveData.hasViewed = $.dialogIn(
            'select',
            'Show Viewed Messages',
            {1: 'Viewed_Only', 0: 'Non-Viewed_Only', '': 'All'},
            null
        );

        var form = $('<form></form>')
            .addClass('simple')
            .append(saveData.source, saveData.level, saveData.hasViewed)
            .dialog({
                title: me.propertyName,
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};