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
var RcmLoginEdit = function(instanceId, container){

    var me = this;

    me.container = container;

    me.invalidErrorMsg = $("#rcmLoginBoxInvalidError").html();
    me.missingError = $("#rcmLoginBoxMissingError").html();
    me.systemFailureError = $("#rcmLoginBoxSystemError").html();
    me.notAuthError = $("#rcmLoginBoxNoAuthError").html();


    /**
     * Called by RelivContentManger to make the random image editable
     */
    me.initEdit = function(){
        me.addContextMenu();
        $(rcm.getPluginContainerSelector(instanceId)).not('[contenteditable="true"]').dblclick(function(e){
            me.showEditDialog();
            e.preventDefault();
        });

        //Hide error messages.. just in case
        $("#rcmLoginBoxInvalidError").hide();
        $("#rcmLoginBoxMissingError").hide();
        $("#rcmLoginBoxSystemError").hide();
        $("#rcmLoginBoxNoAuthError").hide();
    };

    /**
     * Called by RelivContentManger to get the state of this plugin to pass to
     * the server
     * @return {Object}
     */
    me.getSaveData = function(){
        return {
            loginErrorInvalidCopy: me.invalidErrorMsg,
            loginErrorMissingCopy: me.missingError,
            loginErrorSystemCopy: me.systemFailureError,
            loginErrorAuthCopy : me.notAuthError
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
            'text', 'Invalid Error Message', me.invalidErrorMsg
        );

        var missingError = $.dialogIn(
            'text', 'Missing Items Error Message', me.missingError
        );

        var systemFailureError = $.dialogIn(
            'text', 'System Failure Error Message', me.systemFailureError
        );

        var notAuthError = $.dialogIn(
            'text', 'System Failure Error Message', me.notAuthError
        );

        var form = $('<form>')
            .append(invalidError, missingError, systemFailureError, notAuthError)
            .dialog({
                title:'Properties',
                modal:true,
                width:620,
                buttons:{
                    Cancel:function () {

                        $(this).dialog("close");
                    },
                    Ok:function () {
                        me.invalidErrorMsg = invalidError.val();
                        me.missingError = missingError.val();
                        me.systemFailureError = systemFailureError.val();
                        me.notAuthError = notAuthError.val();

                        $("#rcmLoginBoxInvalidError").html(me.invalidErrorMsg);
                        $("#rcmLoginBoxMissingError").html(me.missingError);
                        $("#rcmLoginBoxSystemError").html(me.systemFailureError);
                        $("#rcmLoginBoxNoAuthError").html(me.notAuthError);

                        //Close the dialog
                        okClicked = true;
                        $(this).dialog("close");
                    }
                }
            });
    }
};