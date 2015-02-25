/**
 * RcmActionButtonEdit
 *
 * @param instanceId
 * @param container
 * @param RcmAdminPlugin pluginHandler
 * @constructor
 */
var RcmActionButtonEdit = function (instanceId, container, pluginHandler) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmCallToActionBoxEdit}
     */
    var me = this;

    /**
     * jQuery object for the links
     *
     * @type {Object}
     */
    var aTags = container.find('a');

    /**
     *
     */
    var buttonBox = container.find('.button-box');

    /**
     *
     */
    var buttonContent = container.find('.button-content');


    /**
     * getColorList
     * @returns {*}
     */
    me.getColorList = function() {

        var jsonList = buttonBox.attr('data-color-list');

        return JSON.parse(jsonList);

    };

    /**
     * setButtonColor
     * @param color
     */
    me.setButtonColor = function (color) {

        buttonBox.attr('data-button-color', color);
        buttonBox.attr('style', 'background-color: ' + color);
    };

    /**
     * getButtonColor
     * @returns {*}
     */
    me.getButtonColor = function () {

        return buttonBox.attr('data-button-color');
    };

    /**
     * setLinkColor
     * @param color
     */
    me.setLinkColor = function (color) {

        buttonBox.attr('data-link-color', color);
        buttonContent.attr('style', 'color: ' + color);
    };

    /**
     * getLinkColor
     * @returns {*}
     */
    me.getLinkColor = function () {

        return buttonBox.attr('data-link-color');
    };

    /**
     * setHref
     * @param href
     */
    me.setHref = function (href) {

        aTags.attr('href', href);
    };

    /**
     * getHref
     * @returns {*}
     */
    me.getHref = function () {

        return aTags.attr('href');
    };

    /**
     * getBool
     * @param value
     * @returns {boolean}
     */
    me.getBool = function (value) {

        return (value == 1 || value == 'true' || value === true);
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return {
            'buttonColor': me.getButtonColor(),
            'linkColor': me.getLinkColor(),
            //'class': 'col-md-3 col-sm-12 col-xs-12',
            'href': me.getHref()
        };
    };

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {

        //Double clicking will show properties dialog
        container.dblclick(me.showEditDialog);

        //Add right click menu
        $.contextMenu(
            {
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
            }
        );
    };

    /**
     * Displays a dialog box to edit
     */
    me.showEditDialog = function () {

        var fields = {
            buttonColor: jQuery.dialogIn('select', 'Button Color', me.getColorList(), me.getButtonColor()),
            linkColor: jQuery.dialogIn('select', 'Link Color', me.getColorList(), me.getLinkColor()),
            href: jQuery.dialogIn('url', 'Link Url', me.getHref())
        };

        var form = $('<form></form>')
            .addClass('simple')
            .append(fields.buttonColor, fields.linkColor, fields.href)
            .dialog(
            {
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form
                        me.setButtonColor(fields.buttonColor.val());
                        me.setLinkColor(fields.linkColor.val());
                        me.setHref(fields.href.val());

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};