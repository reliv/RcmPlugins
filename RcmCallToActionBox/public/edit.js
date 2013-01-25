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
var RcmCallToActionBoxEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmCallToActionBox}
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
    var imgTag = container.find('img');

    /**
     * Called by content management system to make this plugin user-editable
     *
     * @return {Null}
     */
    me.initEdit = function(){

        //Double clicking will show properties dialog
        container.delegate('div', 'dblclick', function(event){
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


    }

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        return {
            'href': aTags.attr('href'),
            'imageSrc': imgTag.attr('src')
        }
    }

    me.getAssets = function(){
        var data = me.getSaveData();
        return [data.imageSrc, data.href];
    };

    /**
     * Displays a dialog box to edit href and image src
     *
     * @return {Null}
     */
    me.showEditDialog = function () {

        var srcInput = $().dialogIn('image', 'Image', imgTag.attr('src'));
        var hrefInput = $().dialogIn('text', 'Link Url', aTags.attr('href'));

        var form = $('<form></form>')
            .addClass('simple')
            .append(srcInput, hrefInput)
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
                        imgTag.attr('src', srcInput.val());
                        aTags.attr('href', hrefInput.val());

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};