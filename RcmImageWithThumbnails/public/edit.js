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
var RcmImageWithThumbnailsEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmImageWithThumbnails}
     */
    var me = this;

    /**
     * Background image jQuery object
     *
     * @type {Object}
     */
    var imgTag = $(container).find('img');

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
            'large_image':imgTag.attr('src'),
            'thumb1': imgTag.attr('src'),
            'thumb2': imgTag.attr('src'),
            'thumb3': imgTag.attr('src')
        }
    }

    me.getAssets = function(){
        var data = me.getSaveData();
        return [data.large_image, data.thumb1, data.thumb2, data.thumb3];
    }

    /**
     * Displays a dialog box to edit href and image src
     *
     * @return {Null}
     */
    me.showEditDialog = function () {

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .addImage('large_image', 'Image', imgTag.attr('src'))
            .addImage('thumb1', 'Image', imgTag.attr('src'))
            .addImage('thumb2', 'Image', imgTag.attr('src'))
            .addImage('thumb3', 'Image', imgTag.attr('src'))
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
                        imgTag.attr('src', form.find('[name=large_image]').val());
                        imgTag.attr('src', form.find('[name=thumb1]').val());
                        imgTag.attr('src', form.find('[name=thumb2]').val());
                        imgTag.attr('src', form.find('[name=thumb3]').val());
                        $(this).dialog("close");
                    }
                }
            });

    }
}