/**
 * RcmImageWithThumbnails
 *
 * JS for editing RcmImageWithThumbnails
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmImageWithThumbnails
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
     *
     *
     */

    var mainImage = container.find('a[rel]');

    var imgTagMain1 = $(mainImage.get(0));
    var imgTagMain2 = $(mainImage.get(1));
    var imgTagMain3 = $(mainImage.get(2));

    var thumbs = container.find('img.thumb')

    var imgTagThumb1 = $(thumbs.get(0));
    var imgTagThumb2 = $(thumbs.get(1));
    var imgTagThumb3 = $(thumbs.get(2));


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
    console.log(me.getSaveData());

    }

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        /*
        return {
            "main_image1":"\/modules\/reliv-application\/content\/images\/rcm-image-with-thumbnails\/SlimplicityChoc.jpg",
            "main_image2":"\/modules\/reliv-application\/content\/images\/rcm-image-with-thumbnails\/SlimplicityStraw.jpg",
            "main_image3":"\/modules\/reliv-application\/content\/images\/rcm-image-with-thumbnails\/SlimplicityVan.jpg",
            "thumb1":"\/modules\/reliv-application\/content\/images\/rcm-image-with-thumbnails\/thumb_SlimplicityChoc.jpg",
            "thumb2":"\/modules\/reliv-application\/content\/images\/rcm-image-with-thumbnails\/thumb_SlimplicityStraw.jpg",
            "thumb3":"\/modules\/reliv-application\/content\/images\/rcm-image-with-thumbnails\/thumb_SlimplicityVan.jpg"
        };
        */
        return {
            'main_image1': imgTagMain1.attr('rel'),
            'main_image2': imgTagMain2.attr('rel'),
            'main_image3': imgTagMain3.attr('rel'),
            'thumb1': imgTagThumb1.attr('src'),
            'thumb2': imgTagThumb2.attr('src'),
            'thumb3': imgTagThumb3.attr('src')
        }
    }

    me.getAssets = function(){
        var data = me.getSaveData();
        return [data.main_image1, data.main_image2, data.main_image3, data.thumb1, data.thumb2, data.thumb3];
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
            .addImage('main_image1', 'Image', imgTagMain1.attr('rel'))
            .addImage('main_image2', 'Image', imgTagMain2.attr('rel'))
            .addImage('main_image3', 'Image', imgTagMain3.attr('rel'))
            .addImage('thumb1', 'Image', imgTagThumb1.attr('src'))
            .addImage('thumb2', 'Image', imgTagThumb2.attr('src'))
            .addImage('thumb3', 'Image', imgTagThumb3.attr('src'))
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
                        imgTagMain1.attr('src', form.find('[name=main_image1]').val());
                        imgTagMain2.attr('src', form.find('[name=main_image2]').val());
                        imgTagMain3.attr('src', form.find('[name=main_image3]').val());
                        imgTagThumb1.attr('src', form.find('[name=thumb1]').val());
                        imgTagThumb2.attr('src', form.find('[name=thumb2]').val());
                        imgTagThumb3.attr('src', form.find('[name=thumb3]').val());
                        $(this).dialog("close");
                    }
                }
            });

    }
}