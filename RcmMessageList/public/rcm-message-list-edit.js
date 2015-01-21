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
        showHasViewed: false,
        showDefaultMessage: false
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

    /**
     * completeEditInit
     */
    me.completeEditInit = function () {

        // add a border to show plugin if it is empty
        //$('.rcmPlugin.RcmMessageList').css('border', '1px dotted #DDDDDD');
        //$('.rcmPlugin.RcmMessageList').css('min-height', '1em');
        //$('.rcmPlugin.RcmMessageList .rcmMessageList').css('min-height', '1em');
        // Show mock alert
        me.buildMockAlert();

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

    /**
     * buildMockAlert
     */
    me.buildMockAlert = function () {

        var html = '<div class="alert alert-info" role="alert">' +
            '<button type="button" class="close" aria-label="Close">' +
            '<span aria-hidden="true">×</span>' +
            '</button>' +
            '<span class="subject">Some message subject: </span>' +
            '<span class="body">Some message content here</span>' +
            '</div>';

        var pluginElms = $('.rcmPlugin.RcmMessageList .userMessageList');

        pluginElms.each(
            function (index) {
                if($(this).find('.alert').length < 1){
                    $(this).append(html);
                }
            }
        );
    };

    /**
     * showEditDialog
     */
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
        fields.showHasViewed = $.dialogIn(
            'select',
            'Show Viewed Messages',
            {
                1: 'Viewed_Only',
                0: 'Non-Viewed_Only',
                '': 'All'
            },
            me.instanceConfig.showHasViewed
        );

        fields.showDefaultMessage = $.dialogIn(
            'select',
            'Show Default Message (show &quot;No Messages&quot; when there are no messages)',
            {
                1: 'Show',
                0: 'Hide'
            },
            me.instanceConfig.showDefaultMessage
        );

        var form = $('<form></form>')
            .addClass('simple')
            .append(
            fields.source,
            fields.level,
            fields.showHasViewed,
            fields.showDefaultMessage
        )
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
                        me.instanceConfig.showHasViewed = fields.showHasViewed.val();
                        me.instanceConfig.showDefaultMessage = fields.showDefaultMessage.val();

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};