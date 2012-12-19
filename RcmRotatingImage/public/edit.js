/**
 * Random Image
 *
 * JS for editing Random Image
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmRotatingImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmRotatingImageEdit = function(instanceId, container){

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmRotatingImage}
     */
    var me = this;

    var data;

    /**
     * Called by RelivContentManger to make the random image editable
     *
     * @return {Null}
     */
    me.initEdit = function(){

        //Pull all images and their data from app server
        $.getJSON('/rcm-plugin-admin-proxy/rcm-rotating-image/' + instanceId + '/data',
            function success(returnedData){
                data = returnedData;
                me.completeInitEdit();
            }
        );
    }

    /**
     * Called by RelivContentManger to get the state of this plugin to pass to
     * the server
     *
     * @return {Object}
     */
    me.getSaveData = function(){
        return {'images':data.images};
    }

    me.getAssets = function(){
        var assets = [];
        $.each(data.images,function(){
            assets.push(this.href);
            assets.push(this.src);
        });
        return assets;
    }

    /**
     * Updates the DOM according to our current data
     *
     * @return {Null}
     */
    me.update = function(){

        //Ensure we didn't go out of bounds
        if(me.current < 0){
            me.current = data.images.length-1;
        }else if(me.current >= data.images.length){
            me.current = 0;
        }

        var image =  data.images[me.current];

        //Render image
        var a = container.find('a');
        var img = container.find('.winner');
        img.attr('src', image.src);
        img.attr('alt', image.alt);
        a.attr('href', image.href);

        //Render # of # display
        me.numberDisplay.html('Image #' + (me.current+1) + ' of ' + data.images.length);
    }

    /**
     * Finishes making the plugin editable. Is called when the AJAX request for
     * all images gets back to the browser
     *
     * @return {Null}
     */
    me.completeInitEdit = function(){


        //The div that holds our edit buttons
        var tools = $('<div class="tools"></div>');
        container.children('div').append(tools);

        //# of #
        me.numberDisplay = $('<span></span>');
        tools.append(me.numberDisplay);

        //Arrows
        tools.append($('<img title="Last image" src="/modules/rcm/images/icons/left.png">')
            .click(function(){
                --me.current;
                me.update();
            }
        ));

        tools.append($('<img title="Next image" src="/modules/rcm/images/icons/right.png" class="right">')
            .click(function(){
                ++me.current;
                me.update();
            }
        ));

        //Edit by clicking main image
        container.find('a img').dblclick(function(){
                me.showEditDialog();
            }
        );

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId) +' a',

            //Make nav stay popped up when right click menu opens
            build:function (target) {
                me.popupToKeepUp = $(target).closest('div.popup');
                me.popupToKeepUp.attr('style', 'left:auto')
            },

            events:{
                hide:function (opt) {
                    //Keep nav open for 200ms after the right click menu closes
                    //to ensure the nav stays open if the mouse is still over it
                    setTimeout(function () {
                        me.popupToKeepUp.removeAttr('style')
                    }, 200);
                }
            },



            //Here are the right click menu options
            items:{
                createNew:{
                    name:'Add New Image',
                    icon:'edit',
                    callback:function () {
                        data.images.push(me.getBlankImage());
                        me.current = data.images.length-1;
                        me.update();
                        me.showEditDialog(true);
                    }
                },
                separator1:"-",
                deleteMe:{
                    name:'Remove Image',
                    icon:'delete',
                    callback:function () {
                        if (!data.images.length){
                            $().alert('No images to remove.');
                        } else {
                            $().confirm(
                                'Remove image #' + (me.current + 1) + '?',
                                function() {
                                    data.images.splice(me.current,1);
                                    if(data.images.length==0){
                                        data.images.push(
                                            me.getBlankImage()
                                        );
                                    }else{
                                        --me.current;
                                    }
                                    me.update();
                                }
                            );
                        }
                    }
                },
                separator3:"-",
                edit:{
                    name:'Edit Image Properties',
                    icon:'edit',
                    callback:function () {
                        me.showEditDialog();
                    }
                }

            }
        });

        //Run update to render our first image
        me.current = 0;
        me.update();
    }

    /**
     * Pops up a dialog to edit a single image and its properties
     *
     * @param {Boolean} deleteOnClose used for the cancel button on new images
     *
     * @return {Null}
     */
    me.showEditDialog = function (deleteOnClose) {

        var okClicked = false;

        //If user clicked the edit button but we have no images
        if(!data.images.length){
            $().alert('No images to edit.');
            return null;
        }

        //Show the dialog
        var form = $('<form>')
        .addClass('simple')
        .addImage('src', 'Image', data.images[me.current].src)
        .addInput('alt', 'Alt Text', data.images[me.current].alt)
        .addInput('href', 'Link Url', data.images[me.current].href)
        .dialog({
            title:'Properties',
            modal:true,
            width:620,
            close: function() {
                if (deleteOnClose && !okClicked) {
                    //Delete image
                    data.images.pop();
                }
                me.update();
            },
            buttons:{
                Cancel:function () {

                    $(this).dialog("close");
                },
                Ok:function () {

                    //Get user-entered data from form
                    data.images[me.current].alt = form.find('[name=alt]').val();
                    data.images[me.current].href = form.find('[name=href]').val();
                    data.images[me.current].src = form.find('[name=src]').val();

                    //Close the dialog
                    okClicked = true;
                    $(this).dialog("close");
                }
            }
        });
    }

    /**
     * Returns data for a blank image so we can create new ones and deal with
     * times when we have none
     *
     * @return {Object}
     */
    me.getBlankImage = function(){
        return {src:'/modules/rcm/vendor/ckeditor/skins/kama/images/noimage.png',href:'',alt:''};
    }
}