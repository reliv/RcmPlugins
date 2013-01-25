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
 * @author    Brian Janish <bjanish@relivinc.com>
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
    var containerSelector = rcm.getPluginContainerSelector(instanceId);

    me.newImageTemplate = '<a href="#" rel="" class="image"><img src="" class="thumb" border="0" /></a>';
    me.emptyImageTemplate = '<a href="#" rel="/modules/reliv-application/content/images/rcm-image-with-thumbnails/placeholder.jpg" class="image"><img src="/modules/reliv-application/content/images/rcm-image-with-thumbnails/thumb_placeholder.jpg" class="thumb" border="0" /></a>';
    me.emptyMainImageTemplate = '<div class="mainImage"><img src="/modules/reliv-application/content/images/rcm-image-with-thumbnails/placeholder.jpg" border="0"/></div>';
    /**
     * Called by content management system to make this plugin user-editable
     *
     * @return {Null}
     */
    me.initEdit = function(){
        container.delegate('a.image', 'dblclick', function(event){
            me.showEditDialog($(this));
        });

        container.find('.imgThumbs').sortable();

        container.find('.imgThumbs').disableSelection();


        rcmEdit.pluginContextMenu({
                selector:containerSelector+' a.image, ' + containerSelector+' .imgClass',

                items:{
                    edit:{
                        name:'Edit Properties',
                        icon:'edit',
                        callback:function () {

                            var aTag;

                            if($(this).hasClass('image')) {

                                aTag=$(this);

                            } else
                            if($(this).hasClass('imgClass')) {

                                aTag = container.find('a[rel = "'+($(this).attr('src'))+'"]');

                            }
                            me.showEditDialog(aTag);
                        }
                    },
                    createNew:{
                        name:'Add New Image',
                        icon:'edit',
                        callback:function () {
                            var newImg=$(me.newImageTemplate);
                            $(this).after(newImg);
                            me.showEditDialog(newImg, true);
                        }
                    },

                    deleteImage:{
                        name:'Delete Image',
                        icon:'delete',
                        callback:function () {

                            var aTags = container.find('a');
                            var aTag;


                           /* var selectedIndex = container.find('.mainImage').attr('data-selected');

                            if($(this).hasClass('mainImage')){
                                aTag = $(aTags.get(selectedIndex));
                            }else{
                                aTag = $(this);
                            }
                            */
                            if($(this).hasClass('image')) {

                                aTag=$(this);

                            } else
                            if($(this).hasClass('imgClass')) {

                                aTag = container.find('a[rel = "'+($(this).attr('src'))+'"]');

                            }

                          // alert($(aTags).length);

                            if(aTags.length==1){

                                    $().confirm(

                                        'Delete this link? <br /><br />' + aTag.html() + '<br /><br />',

                                        function () {

                                           // aTag.remove();

                                            var newImg=$(me.emptyImageTemplate);
                                            var newMainImg=$(me.emptyMainImageTemplate);

                                            var mainImageVal = container.find('.mainImage');

                                            $(aTag).replaceWith(newImg);
                                            $(mainImageVal).replaceWith(newMainImg);
                                        },

                                        function(){

                                        }

                                    );

                            } else {

                                $().confirm(
                                    'Delete this link? <br /><br />' + aTag.html() + '<br /><br />',
                                    function () {

                                        var mainImageVal = container.find('.imgClass').attr('src');
                                        var mainRelVal = aTag.attr('rel');

                                        console.log(mainRelVal);
                                        console.log(mainImageVal);
                                        console.log(initialImg);

                                        aTag.remove();

                                        var initialImg = container.find('a.image').attr('rel');
                                        if(mainImageVal == mainRelVal){

                                            container.find('.imgClass').attr('src', initialImg);

                                        }
                                    }
                                );

                            }
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


        var aTags = container.find('.image');
        var imageArray = [];

        $.each(aTags, function(key, nonJqueryATag) {

            var aTag=$(nonJqueryATag);

            imageArray.push(
                {
                    'main':aTag.attr('rel'),
                    'thumb':aTag.find('.thumb').attr('src')
                }
            );

        });

       return imageArray;

    }

    me.getAssets = function(){
        return me.getSaveData();
    }

    /**
     * Displays a dialog box to edit href and image src
     *
     * @return {Null}
     */
    me.showEditDialog = function (aTag, deleteOnClose) {
        //console.log(aTag);


        var mainVal = aTag.attr('rel');
        var thumbVal = aTag.find('.thumb').attr('src')

        var okClicked = false;

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .addImage('main', 'Image', mainVal)
            .addImage('thumb', 'Image', thumbVal)

            .dialog({
                title:'Properties',
                modal:true,
                width:620,
                close:function () {
                    if (deleteOnClose && !okClicked) {
                        // Remove the new li that was created if the user clicks
                        // cancel
                        aTag.remove();
                        me.refresh();
                    }
                },
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {
                        //Get user-entered data from form

                        var newMainVal = form.find('[name=main]').val();
                        var newThumbVal = form.find('[name=thumb]').val();

                        aTag.attr('rel', newMainVal);
                        aTag.find('.thumb').attr('src', newThumbVal);
                        var button = this;
                        var continueOkClick = function () {
                        okClicked = true;
                        $(button).dialog("close");
                        }
                        //$(this).dialog("close");
                        continueOkClick();
                    }
                }
            });

    }
}