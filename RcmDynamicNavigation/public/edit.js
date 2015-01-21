/**
 * RcmNavigation
 *
 * JS for editing RcmNavigation
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
var RcmDynamicNavigationEdit = function (instanceId, container, pluginHandler) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     */
    var me = this;

    /**
     * The selector to get this plugin container
     * @type {String}
     */
    var containerSelector = rcm.getPluginContainerSelector(instanceId);

    /**
     * Used for creating new links
     *
     * @type {String}
     */
    var newLinkTemplate = '<li><a href="">Untitled Link</a></li>';

    /**
     * Used for creating new links
     *
     * @type {String}
     */
    var newSubMenuTemplate = '<ul><li><a href="">Untitled Link</a></li></ul>';

    /**
     * Used for creating new links
     *
     * @type {String}
     */
    var loginLinkTemplate = '<li class="rcmDynamicNavigationLogout"><a href="/login?logout=1">Logout</a></li>';

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {
        me.addRightClickMenu();
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {


    };


    me.addItem = function(item) {
        var selectedLi = $(item);
        var newLi = $(newLinkTemplate);

        selectedLi.after(newLi);
        me.refresh();
    };

    me.addSubMenu = function(item) {
        var selectedLi = $(item);
        var newUl = $(newSubMenuTemplate);

        if (selectedLi.find('ul').length > 0 ) {
            return;
        }

        var parentATag = selectedLi.find('a');

        if (!parentATag.hasClass('sf-with-ul')) {
            parentATag.addClass('sf-with-ul');
        }

        selectedLi.append(newUl);
        me.refresh();
    };

    me.addLoginLink = function(item) {
        var menuBar = $(containerSelector).children('ul');

        if (menuBar.find(".rcmDynamicNavigationLogout").length > 0) {
            return;
        }

        newLi = $(loginLinkTemplate);

        menuBar.append(newLi);
        me.refresh();
    };

    me.getColumnCount = function(item) {
        return item.find('div').length;
    };

    me.deleteItem = function(item) {
        var li = $(item);
        var a = li.find('a');
        var itemtext = $.trim(a.html());
        var myTopParentUl = li.parents('ul:last');

        $().confirm(
            'Delete this link?<br><br>"' + itemtext + '"',
            function () {

                //Don't let them delete the last link
                if (myTopParentUl.children('li').length == 1) {
                    me.addItem(item);
                }

                li.remove();
            }
        );

        me.refresh();
    };

    /**
     * Add the elements we need for editing to the DOM
     */
    me.addRightClickMenu = function () {

        //Add right click menu
        $.contextMenu({
            selector: containerSelector + ' li',

            events: {
                hide: function () {
                    //Keep nav open for 200ms after the right click menu closes
                    //to ensure the nav stays open if the mouse is still over it
                    //setTimeout(function () {
                    //    me.popupToKeepUp.removeAttr('style')
                    //}, 200);
                }
            },

            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit Link Properties',
                    icon: 'edit',
                    callback: function () {
                        me.showEditDialog(this, false);
                    }
                },
                separator1: "-",
                createNew: {
                    name: 'Create New Link',
                    icon: 'add',
                    callback: function () {
                        me.addItem(this);
                    }
                },
                createSub: {
                    name: 'Add Sub Menu Link',
                    icon: 'add',
                    callback: function () {
                        me.addSubMenu(this);
                    }
                },
                deleteLink: {
                    name: 'Delete Link',
                    icon: 'delete',
                    callback: function() {
                        me.deleteItem(this);
                    }
                },
                separator2: "-",
                loginLink: {
                    name: 'Add Login Link',
                    icon: 'add',
                    callback: function() {
                        me.addLoginLink(this);
                    }
                },

            }
        });

        me.refresh();
    };

    /**
     * Displays a dialog box to edit or add links
     *
     * @param {Object} li the tag that we are editing
     * @param {Boolean} [deleteOnClose] will delete the link if user clicks cancel
     */
    me.showEditDialog = function (li, deleteOnClose) {

        var a = li.children('a');

        //Find out what css class this link has
        var cssClass = li.attr('class');
        if (typeof(cssClass) == 'undefined') {
            cssClass = '';
        }

        var okClicked = false;

        var text = $.dialogIn('text', 'Text', jQuery.trim(a.html()));
        var href = $.dialogIn('url', 'Link Url', jQuery.trim(a.attr('href')));
        var cssClassInput = $.dialogIn(
            'select',
            'Display Style',
            {'': 'Normal', 'heading': 'Heading', 'bold': 'Bold'},
            cssClass,
            true
        );

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .append(text, href, cssClassInput)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                close: function () {
                    if (deleteOnClose && !okClicked) {
                        // Remove the new li that was created if the user clicks
                        // cancel
                        li.remove();
                        me.refresh();
                    }
                },
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form
                        a.html(text.val());
                        a.attr('href', href.val());
                        li.attr('class', cssClassInput.val());

                        //Put this in a closure so modifySubMenu can call it
                        var button = this;
                        $(button).dialog("close");
                        me.refresh();
                    }
                }
            });

    };

    me.refresh = function() {
        $(containerSelector).find('a').click(false);

        try {
            //Prevent links from being arrangeable
            container.find('ul').sortable('destroy');
        } catch(e){
            //do nothing, getting here just means we weren't in edit mode before
        }

        //Make links arrangeable
        container.find('ul').sortable({
            connectWith: containerSelector + ' ul'
        });
    }
};