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

    me.liClassesToFilter = [
        'dropdown',
        'open',
        'context-menu-active',
        'HiddenLink'
    ];

    me.liClassesForLogin = [
        'rcmDynamicNavigationLogout',
        'rcmDynamicNavigationLogin',
        'rcmDynamicNavigationAuthMenuItem'
    ];

    me.aClassesToFilter = [
        'dropdown-toggle'
    ];

    me.seperatorCount = 0;

    /**
     * The selector to get this plugin container
     * @type {String}
     */
    me.containerSelector = rcm.getPluginContainerSelector(instanceId);

    /**
     * Used for creating new links
     *
     * @type {String}
     */
    me.newLinkTemplate = '<li><a href=""><span class="linkText">Untitled Link</span></a></li>';

    /**
     * Used for creating new links
     *
     * @type {String}
     */
    me.newSubMenuTemplate = '<ul class="dropdown-menu" role="menu"><li><a href=""><span class="linkText">Untitled Link</span></a></li></ul>';

    /**
     * Used for creating new links
     *
     * @type {String}
     */
    me.loginLinkTemplate = '<li class="rcmDynamicNavigationLogout rcmDynamicNavigationAuthMenuItem"><a href="/login?logout=1">Logout</a></li>';
    me.loginLinkTemplate = me.loginLinkTemplate + '<li class="rcmDynamicNavigationLogin rcmDynamicNavigationAuthMenuItem"><a href="/login">Login</a></li>';

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {

        var links = jQuery(me.containerSelector).find("li");
        links.removeClass('HiddenLink');
        me.refresh();
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        var mainLinks = jQuery(me.containerSelector).find("#RcmDynamicNavigation_"+instanceId).children("ul:first").children("li").toArray();

        var data = [];

        jQuery.each(mainLinks, function(i, link){
            data.push(me.getLinkData(i, link));
        });

        var saveData = {
            links : data
        };

        return saveData;
    };

    me.getLinkData = function(myIndex, link) {

        var a = jQuery(link).children("a:first");

        var myLinkData = {
            "myIndex" : myIndex,
            'display' : a.find('span.linkText').text().trim(),
            'href' : a.attr('href'),
            'target': a.attr("target"),
            'class' : me.getLiClasses(link, true),
            'permissions' : jQuery(link).attr('data-permissions')
        };

        var linksArray = [];

        var subLinks = jQuery(link).children("ul:first").children("li");

        jQuery.each(subLinks, function(i, subLink){
            linksArray.push(me.getLinkData(i, subLink));
        });

        myLinkData.links = linksArray;

        return myLinkData;
    };


    me.addItem = function(item) {
        var selectedLi = jQuery(item);
        var newLi = jQuery(me.newLinkTemplate);

        selectedLi.after(newLi);
        me.refresh();
    };

    me.addSubMenu = function(item) {
        var selectedLi = jQuery(item);
        var newUl = jQuery(me.newSubMenuTemplate);

        if (selectedLi.find('ul').length > 0 ) {
            return;
        }

        selectedLi.addClass('dropdown');

        var parentATag = selectedLi.find('a');

        if (!parentATag.hasClass('dropdown-toggle')) {
            parentATag.addClass('dropdown-toggle');
        }

        var dataToggle = parentATag.attr('data-toggle');

        if (dataToggle === undefined || dataToggle === false) {
            parentATag.attr('data-toggle',"dropdown" );
        }

        var roleAttr = parentATag.attr('role');

        if (roleAttr === undefined || roleAttr === false) {
            parentATag.attr('role',"button" );
        }

        var ariaExpanded = parentATag.attr('aria-expanded');

        if (ariaExpanded === undefined || ariaExpanded === false) {
            parentATag.attr('aria-expanded',"false" );
        }

        parentATag.append('<span class="caret"></span>');

        selectedLi.append(newUl);
        me.refresh();
    };

    me.addLoginLink = function(item) {
        var menuBar = jQuery(me.containerSelector).find('nav').children('ul');

        if (menuBar.find(".rcmDynamicNavigationLogout").length > 0) {
            return;
        }

        newLi = jQuery(me.loginLinkTemplate);

        menuBar.append(newLi);
        me.refresh();
    };

    me.getColumnCount = function(item) {
        return item.find('div').length;
    };

    me.deleteItem = function(item) {
        var li = jQuery(item);
        var a = li.find('a');
        var itemtext = $.trim(a.html());
        var myTopParentUl = li.parents('ul:last');

        jQuery().confirm(
            'Delete this link?<br><br>"' + itemtext + '"',
            function () {

                //Don't let them delete the last link
                if (myTopParentUl.children('li').length == 1) {
                    me.addItem(item);
                }

                li.remove();
                me.refresh();
            }
        );
    };

    /**
     * Add the elements we need for editing to the DOM
     */
    me.addRightClickMenu = function () {
        var mainMenuItems = me.getMenuItems('main');
        me.addRightClickMenuDialog(me.containerSelector + ' ul:first > li', mainMenuItems);

        var subMenuItems = me.getMenuItems('sub');
        me.addRightClickMenuDialog(me.containerSelector + ' ul:first > li li', subMenuItems);
    };

    me.addRightClickMenuDialog = function(selector, items)
    {
        jQuery.contextMenu('destroy', selector);

        //Add right click menu
        jQuery.contextMenu({
            selector: selector,

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
            items: items
        });
    };

    me.getMenuItems = function(type)
    {

        me.seperatorCount = 0;

        var showAddLoginLinkMenu = {};

        if (jQuery(me.containerSelector + " .rcmDynamicNavigationAuthMenuItem").length < 1 && type == 'main') {
            showAddLoginLinkMenu = {
                loginLink: {
                    name: 'Add Login Link',
                    icon: 'add',
                    callback: function () {
                        me.addLoginLink(this);
                    }
                }
            }
        }

        var createSubMenuItem = {};

        if (type == 'main') {
            createSubMenuItem = {
                createSub: {
                    name: 'Add Sub Menu Link',
                    icon: 'add',
                    callback: function () {
                        me.addSubMenu(this);
                    }
                },
            };
        }

        var editLinkPropertiesMenuItem = {
            edit: {
                name: 'Edit Link Properties',
                icon: 'edit',
                callback: function () {
                    me.showEditDialog(this, false);
                }
            }
        };

        var permissionMenuItem = {
            permissions: {
                name: 'Change Link View Permissions',
                icon: 'edit',
                callback: function () {
                    me.showPermissionsDialog(this);
                }
            }
        };

        var createNewLinkMenuItem = {
            createNew: {
                name: 'Create New Link',
                icon: 'add',
                callback: function () {
                    me.addItem(this);
                }
            },
        };

        var deleteLinkMenuItem = {
            deleteLink: {
                name: 'Delete Link',
                icon: 'delete',
                callback: function() {
                    me.deleteItem(this);
                }
            }
        };

        var items = {};

        jQuery.extend(items, editLinkPropertiesMenuItem, me.getSeperator(), editLinkPropertiesMenuItem, permissionMenuItem, createNewLinkMenuItem, createSubMenuItem, deleteLinkMenuItem, me.getSeperator(), showAddLoginLinkMenu);

        return items;
    };

    me.getSeperator = function()
    {
        var seperatorId = 'seperator'+me.seperatorCount;
        var seperator = {};
        seperator[seperatorId] = '-';

        me.seperatorCount++;
        return seperator;
    };

    me.isLoginLink = function(item) {

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
        var currentClasses = me.getLiClasses(li, false);

        if (typeof(currentClasses) == 'undefined') {
            currentClasses = '';
        }

        var okClicked = false;

        var text = $.dialogIn('text', 'Text', jQuery.trim(a.find("span.linkText").text()));
        var href = $.dialogIn('url', 'Link Url', jQuery.trim(a.attr('href')));

        var aTarget = $.dialogIn(
            'select',
            'Open in new window',
            {'': 'No', '_blank': 'Yes'},
            jQuery.trim(a.attr('target')),
            true
        );

        var cssClassInput = $.dialogIn(
            'select',
            'Display Style',
            {'': 'Normal', 'heading': 'Heading', 'bold': 'Bold'},
            currentClasses,
            true
        );

        //Create and show our edit dialog
        var form = jQuery('<form></form>')
            .addClass('simple')
            .append(text, href, aTarget, cssClassInput)
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
                        jQuery(this).dialog("close");
                    },
                    Ok: function() {
                        //Get user-entered data from form
                        a.find("span.linkText").text(text.val());
                        a.attr('href', href.val());
                        a.attr('target', aTarget.val());

                        li.removeClass(currentClasses);
                        li.addClass(cssClassInput.val());

                        //Put this in a closure so modifySubMenu can call it
                        var button = this;
                        jQuery(button).dialog("close");
                        me.refresh();
                    }
                }
            });

    };

    me.showPermissionsDialog = function (li) {

        var permissions = li.attr('data-permissions');
        var selectedRoles = permissions.split(",");

        var selected = {};

        $.each(selectedRoles, function(i,v) {
            selected[v] = v;
        });

        rcmShowPermissions(selected, function(roles){
            if (roles.length > 1) {
                li.attr('data-permissions', roles.join(','));
            } else {
                li.attr('data-permissions', roles[0]);
            }
        });
    };

    me.getLiClasses = function(li, save) {

        var cloneLi = jQuery(li).clone();

        jQuery.each(me.liClassesToFilter, function(i, v) {
            jQuery(cloneLi).removeClass(v);
        });

        if (!save) {
            jQuery.each(me.liClassesForLogin, function(i, v) {
                jQuery(cloneLi).removeClass(v);
            });
        }

        return cloneLi.attr('class');
    };

    me.getAClasses = function(a) {

        var cloneA = jquery(a).clone();

        jQuery.each(me.aClassesToFilter, function(i, v) {
            jQuery(cloneA).removeClass(v);
        });

        return cloneA.attr('class');
    };

    me.refresh = function() {
        me.addRightClickMenu();
        jQuery(me.containerSelector).find('a').click(false);

        try {
            //Prevent links from being arrangeable
            container.find('ul').sortable('destroy');
        } catch(e){
            //do nothing, getting here just means we weren't in edit mode before
        }

        //Make links arrangeable
        container.find('ul').sortable({
            connectWith: me.containerSelector + ' ul'
        });

        jQuery(me.containerSelector).find("a.dropdown-toggle").unbind('click');

        jQuery(me.containerSelector).find('li').dblclick(function(){me.showEditDialog(jQuery(this),false)})
    }
};


