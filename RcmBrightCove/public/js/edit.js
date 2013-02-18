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
 * @package   RcmPlugins\RcmCallToActionBox
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmBrightCoveEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmBrightCoveEdit}
     */
    var me = this;
    me.instanceId = instanceId;
    me.container = container;
    me.apiToken = 'W3IM0czQo2YQ1EIM5CSIMj2KYCX0DrK4_vhAYu9vGSiC5Fw0-cgvow..';
    me.apiUrl = 'http://api.brightcove.com/services';

    me.initEdit = function(){
        //Add overlay to click on
        $(container).append("<div style='width: 50px; height: 50px; position: absolute; background-color: transparent; z-index: 1000; top: 15px; left: 15px; color: #FFFFFF; cursor: pointer;' id='rcmBightCoveOverlay_"+me.instanceId+"'>Edit</div>");
        $("#rcmBightCoveOverlay_"+me.instanceId).click(function() {
            me.showEditDialog();
        })
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
       return {
           playerId: me.getPlayerId()
       };
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    me.showEditDialog = function () {
        me.getPlayLists();
//        var formBrightCoveApiToken = $("<div id='apiReadToken'></div>").dialogIn('text', 'API Read Token', 'W3IM0czQo2YQ1EIM5CSIMj2KYCX0DrK4_vhAYu9vGSiC5Fw0-cgvow..');
//        var form = $('<form></form>')
//            .append(formPlayerId)
//            .dialog({
//                title:'Properties',
//                modal:true,
//                width:620,
//                buttons:{
//                    Cancel:function () {
//                        $(this).dialog("close");
//                    },
//                    Ok:function () {
//
//                        //Get user-entered data from form
//                        aTags.attr('href', form.find('[name=href]').val());
//
//                        $(this).dialog("close");
//                    }
//                }
//            });
    };

    me.getPlayerId = function() {
        return $(me.container).find('[data-playerId]').attr('data-playerId');
    };

    me.getPlayLists = function(pageNumber) {

        if (pageNumber == undefined || pageNumber == '') {
            pageNumber = 0;
        }

        $.getJSON(me.apiUrl+'/services/library', {
            'command' : 'find_all_playlists',
            'token' : me.apiToken,
            'page_size' : 50,
            'page_number' : pageNumber,
            'sort_by' : 'DISPLAY_NAME',
            'sort_order' : 'ASC',
            'get_item_count' : 'true'
        }, function(data) {
            console.log(data);
        });
    };
};