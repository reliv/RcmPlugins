/**
 * Reliv Content Manager Login
 *
 * JS for editing Login Plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RandomImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmLoginOpenSourceVersionTempRenameEdit = function(instanceId, container){

    var me = this;

    me.container = container;

    me.invalidErrorMsg = $("#rcmLoginBoxInvalidError").html();
    me.missingError = $("#rcmLoginBoxMissingError").html();
    me.systemFailureError = $("#rcmLoginBoxSystemError").html();
    me.notAuthError = $("#rcmLoginBoxNoAuthError").html();
    me.processingMsg = $("#rcmLoginBoxProcessingMessage").html();

    /**
     *
     * @type {RcmLoginOpenSourceVersionTempRename}
     */
    var rcmLogin = window['RcmLoginOpenSourceVersionTempRename'][instanceId];

    var errors = rcmLogin.getErrors();

    /**
     * Called by RelivContentManger to make the random image editable
     */
    me.initEdit = function(){
        //Allow labels to be clicked
        container.find('label').attr('for',null);

        //Disable buttons
        container.find('button').unbind();
        container.find('button').click(function(){return false;});

        me.addContextMenu();
        container.not('[contenteditable="true"]').dblclick(function(e){
            me.showEditDialog();
            e.preventDefault();
        });
    };

    /**
     * Called by RelivContentManger to get the state of this plugin to pass to
     * the server
     * @return {Object}
     */
    me.getSaveData = function(){
        return {
            errors:errors
        };
    };

    me.addContextMenu = function() {

        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId),

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

    me.showEditDialog = function() {

        var invalidError = $.dialogIn(
            'text', 'Invalid Error Message', errors['invalid']
        );

        var missingError = $.dialogIn(
            'text', 'Missing Items Error Message', errors['missing']
        );

        var systemFailureError = $.dialogIn(
            'text', 'System Failure Error Message', errors['systemFailure']
        );

        var form = $('<form></form>')
            .append(invalidError, missingError, systemFailureError)
            .addClass('simple')
            .width(640)
            .dialog({
                title:'Properties',
                width:620,
                modal:true,
                buttons:{
                    Cancel:function () {

                        $(this).dialog("close");
                    },
                    Ok:function () {
                        errors['invalid'] = invalidError.val();
                        errors['missing'] = missingError.val();
                        errors['systemFailure'] = systemFailureError.val();

                        $(this).dialog("close");
                    }
                }
            });
    }
};