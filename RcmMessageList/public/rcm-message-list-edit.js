/**
 * RcmMessageListEdit
 * @param instanceId
 * @param container
 * @constructor
 */
var RcmMessageListEdit = function (instanceId, container, pluginHandler) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type this
     */
    var me = this;

    me.propertyName = 'User Message List';

    me.instanceConfig = {
        source: null,
        level: null,
        hasViewed: null
    };


    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {

        pluginHandler.getInstanceConfig(
            function (instanceConfig, defaultInstanceConfig) {
                me.instanceConfig = instanceConfig;
                me.completeEditInit();
            }
        );
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return me.instanceConfig;
    };

    me.completeEditInit = function () {

        //Add right click menu
        $.contextMenu(
            {
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
            }
        );
    };

    me.showEditDialog = function () {

        var fields = {};
        fields.source = $.dialogIn(
            'text',
            'Message Sources to Display (source) - Leave blank to dispaly ALL.',
            me.instanceConfig.source
        );
        fields.level = $.dialogIn(
            'select',
            'Show Messages of Level - Use if only specific message levels need be displayed',
            {
                2: 'CRITICAL Only',
                4: 'ERROR Only',
                8: 'WARNING Only',
                16: 'INFO Only',
                32: 'SUCCESS Only',
                0: 'All'
            },
            me.instanceConfig.level
        );
        fields.hasViewed = $.dialogIn(
            'select',
            'Show Viewed Messages',
            {
                1: 'Viewed_Only',
                0: 'Non-Viewed_Only',
                '': 'All'
            },
            me.instanceConfig.hasViewed
        );

        var form = $('<form></form>')
            .addClass('simple')
            .append(fields.source, fields.level, fields.hasViewed)
            .dialog(
            {
                title: me.propertyName,
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form
                        me.instanceConfig.source = fields.source.val();
                        me.instanceConfig.level = fields.level.val();
                        me.instanceConfig.hasViewed = fields.hasViewed.val();

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};