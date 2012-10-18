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
var RcmRssFeedEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmCallToActionBox}
     */
    var me = this;
    me.instanceId = instanceId;
    me.container = container;

    var dataContainer = $(container).find(".rcmRssFeedReaderMainContainer");

    me.feedUrl = $(dataContainer).attr("data-rcmRssFeedUrl");
    me.feedLimit = $(dataContainer).attr("data-rcmRssFeedLimit");
    me.feedProxy = $(dataContainer).attr("data-rcmRssFeedProxy");


    me.initEdit = function(){

        //Double clicking will show properties dialog
        container.delegate('div', 'dblclick', function(event){
            me.showEditDialog();
        });

        //Add right click menu
        $.contextMenu({
            selector:'[data-rcmPluginInstanceId="' + instanceId + '"]',
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


    }

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        return {
            'rcmRssFeedUrl': me.feedUrl,
            'rcmRssFeedLimit': me.feedLimit
        }
    }

    /**
     * Displays a dialog box to edit href and image src
     *
     * @return {Null}
     */
    me.showEditDialog = function () {

        //Create and show our edit dialog
        var form = $('<form>')
            .addInput('rcmFeedUrl', 'RSS Feed Url', me.feedUrl)
            .addInput('rcmFeedLimit', 'Entries to Display', me.feedLimit)
            .dialog({
                title:'Properties',
                modal:true,
                width:620,
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {

                        //Grab the non-jquery form so we can get its fields
                        var domForm = form.get(0);

                        //Get user-entered data from form
                        me.feedUrl = domForm.rcmFeedUrl.value;
                        me.feedLimit = domForm.rcmFeedLimit.value;

                        new rssReader(
                            me.feedProxy,
                            me.instanceId,
                            $(".rss-feed-"+me.instanceId),
                            me.feedUrl,
                            me.feedLimit
                        );

                        $(this).dialog("close");
                    }
                }
            });

    }
}