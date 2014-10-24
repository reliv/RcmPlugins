/**
 * RcmRecommendedProducts
 *
 * JS for editing RcmRecommendedProducts
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmRecommendedProductsEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmRecommendedProductsEdit}
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
     *  Gets background image url
     *
     * @returns {String}
     */
    var getBackgroundImageUrl = function () {
        return imgTag.attr('src').replace('url(', '')
            .replace(')', '');
    };
    console.log(alert(imgTag.attr('src')));

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {

        //Double clicking will show properties dialog
        container.dblclick(me.showEditDialog);

        //Add right click menu
        $.contextMenu({
            selector: rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit Properties',
                    icon: 'edit',
                    callback: function () {
                        me.showEditDialog();
                    }
                }

            }
        });
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return {
            'href': aTags.attr('href'),
            'imageSrc': getBackgroundImageUrl()
        }
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    me.showEditDialog = function () {
        var srcInput = $.dialogIn('image', 'Image', getBackgroundImageUrl());
        var hrefInput = $.dialogIn('url', 'Link Url', aTags.attr('href'));

        var form = $('<form></form>')
            .addClass('simple')
            .append(srcInput, hrefInput)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form
                        imgTag.css('background-image', 'url(' + srcInput.val() + ')');
                        aTags.attr('href', hrefInput.val());

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};