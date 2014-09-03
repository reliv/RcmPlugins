/**
 * requires AjaxPluginEditHelper which should be included by rcm-admin
 *
 * Random Image
 *
 * JS for editing Random Image
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmRotatingImageEdit = function (instanceId, container, pluginHandler) {

    var me = this;

    var instanceConfig;

    var ajaxEditHelper = new AjaxPluginEditHelper(
        instanceId, container, pluginHandler
    );

    /**
     * Called by RelivContentManger to make the random image editable
     */
    me.initEdit = function () {

        ajaxEditHelper.ajaxGetInstanceConfigs(
            function (returnedInstanceConfig) {

                instanceConfig = returnedInstanceConfig;

                me.completeInitEdit();
            }
        );
    };

    /**
     * Called by RelivContentManger to get the state of this plugin to pass to
     * the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return instanceConfig;
    };

    /**
     * Updates the DOM according to our current instanceConfig
     */
    me.render = function () {

        //Ensure we didn't go out of bounds
        if (me.current < 0) {
            me.current = instanceConfig.images.length - 1;
        } else if (me.current >= instanceConfig.images.length) {
            me.current = 0;
        }

        var image = instanceConfig.images[me.current];

        //Render image
        var a = container.find('a');
        var img = container.find('.winner');
        img.attr('src', image.src);
        img.attr('alt', image.alt);
        a.attr('href', image.href);

        //Render # of # display
        me.numberDisplay.html('Image #' + (me.current + 1) + ' of ' + instanceConfig.images.length);
    };

    /**
     * Finishes making the plugin editable. Is called when the AJAX request for
     * all images gets back to the browser
     */
    me.completeInitEdit = function () {

        //The div that holds our edit buttons
        var tools = $('<div class="tools" style="position: absolute; right: 10px; top: 5px"></div>');
        container.children('div').append(tools);

        //# of #
        me.numberDisplay = $('<span></span>');
        tools.append(me.numberDisplay);

        //Arrows
        tools.append($('<img title="Last image" src="/modules/rcm-lib/images/icons/left.png" style="cursor: pointer;margin-left: 10px;">')
            .click(function () {
                --me.current;
                me.render();
            }
        ));

        tools.append($('<img title="Next image" src="/modules/rcm-lib/images/icons/right.png" style="cursor: pointer;margin-left:0">')
            .click(function () {
                ++me.current;
                me.render();
            }
        ));

        //Edit by clicking main image
        container.find('a img').dblclick(function () {
                me.showEditDialog(false);
            }
        );

        //Add right click menu
        $.contextMenu({
            selector: rcm.getPluginContainerSelector(instanceId) + ' a',

            //Here are the right click menu options
            items: {
                createNew: {
                    name: 'Add New Image',
                    icon: 'edit',
                    callback: function () {
                        instanceConfig.images.push(me.getBlankImage());
                        me.current = instanceConfig.images.length - 1;
                        me.render();
                        me.showEditDialog(true);
                    }
                },
                separator1: "-",
                deleteMe: {
                    name: 'Remove Image',
                    icon: 'delete',
                    callback: function () {
                        if (!instanceConfig.images.length) {
                            $().alert('No images to remove.');
                        } else {
                            $().confirm(
                                'Remove image #' + (me.current + 1) + '?',
                                function () {
                                    instanceConfig.images.splice(me.current, 1);
                                    if (instanceConfig.images.length == 0) {
                                        instanceConfig.images.push(
                                            me.getBlankImage()
                                        );
                                    } else {
                                        --me.current;
                                    }
                                    me.render();
                                }
                            );
                        }
                    }
                },
                separator3: "-",
                edit: {
                    name: 'Edit Image Properties',
                    icon: 'edit',
                    callback: function () {
                        me.showEditDialog();
                    }
                }

            }
        });

        //Run render to render our first image
        me.current = 0;
        me.render();
    };

    /**
     * Pops up a dialog to edit a single image and its properties
     *
     * @param {Boolean} deleteOnClose used for the cancel button on new images
     */
    me.showEditDialog = function (deleteOnClose) {

        var okClicked = false;

        //If user clicked the edit button but we have no images
        if (!instanceConfig.images.length) {
            $().alert('No images to edit.');
            return;
        }

        var src = $.dialogIn(
            'image', 'Image', instanceConfig.images[me.current].src
        );
        var alt = $.dialogIn(
            'text', 'Alt Text', instanceConfig.images[me.current].alt
        );
        var href = $.dialogIn(
            'url', 'Link Url', instanceConfig.images[me.current].href
        );

        //Show the dialog
        var form = $('<form>')
            .addClass('simple')
            .append(src, alt, href)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                close: function () {
                    if (deleteOnClose && !okClicked) {
                        //Delete image
                        instanceConfig.images.pop();
                    }
                    me.render();
                },
                buttons: {
                    Cancel: function () {

                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered instanceConfig from form
                        instanceConfig.images[me.current].alt = alt.val();
                        instanceConfig.images[me.current].href = href.val();
                        instanceConfig.images[me.current].src = src.val();

                        //Close the dialog
                        okClicked = true;
                        $(this).dialog("close");
                    }
                }
            });
    };

    /**
     * Returns instanceConfig for a blank image so we can create new ones and deal with
     * times when we have none
     *
     * @return {Object}
     */
    me.getBlankImage = function () {
        return {src: '/modules/rcm-lib/images/no-image.png', href: '', alt: ''};
    };

};