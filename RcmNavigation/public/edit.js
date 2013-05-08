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
 * @package   RcmPlugins\RcmNavigation
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmNavigationEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmNavigationEdit}
     */
    var me = this;

    /**
     * Whether we are drag-drop arranging or one-click editing
     *
     * @type {Boolean}
     */
    var arrangeMode = false;

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
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {
        container.delegate('li', 'dblclick', function (event) {
            event.stopPropagation();
            me.showEditDialog($(this));
        });
        me.addEditElements();
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        //Remove the editing elements so we can get clean HTML to save.
        me.removeEditElements();

        //Save our html to the hidden input box
        return {
            'html':container.children('ul').html()
        };
    };

    /**
     * Ensure everything is editable, especially after recent changes
     */
    me.refresh = function () {
        me.removeEditElements();
        me.addEditElements();
    };

    /**
     * Add the elements we need for editing to the DOM
     */
    me.addEditElements = function () {

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:containerSelector + ' li',

            //Make nav stay popped up when right click menu opens
            build:function (target) {
                me.popupToKeepUp = $(target).closest('div.popup');
                me.popupToKeepUp.attr('style', 'left:auto')
            },

            events:{
                hide:function () {
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
                    name:'Create New Link',
                    icon:'edit',
                    callback:function () {
                        var newLi = $(newLinkTemplate);
                        $(this).after(newLi);
                        me.showEditDialog(newLi, true);
                    }
                },
//Disabled direct edits because copying and pasting links breaks the html
//                separator1:"-",
//                arrangeMode:{
//                    name:'Links are Movable via Drag and Drop'
//                        + (arrangeMode ? ' (on)' : ''),
//                    disabled:arrangeMode,
//                    callback:function () {
//                        me.removeEditElements();
//                        arrangeMode = true;
//                        me.addEditElements();
//                    }
//                },
//                editMode:{
//                    name:'Links are Editable'
//                        + (!arrangeMode ? ' (on)' : ''),
//                    disabled:!arrangeMode,
//                    callback:function () {
//                        me.removeEditElements();
//                        arrangeMode = false;
//                        me.addEditElements();
//                    }
//                },
                separator2:"-",
                deleteLink:{
                    name:'Delete Link',
                    icon:'delete',
                    callback:function () {
                        var li = $(this);
                        var a = $(this).children('a');
                        var ul = li.parent();
                        $().confirm(
                            'Delete this link?<br><br>"' + a.html() + '"',
                            function () {
                                li.remove();
                                //Don't let them delete the last link
                                if (ul.children('li').length == 0) {
                                    ul.append(newLinkTemplate);
                                }
                            }
                        );
                    }
                },
                separator3:"-",
                edit:{
                    name:'Edit Link Properties',
                    icon:'edit',
                    callback:function () {
                        me.showEditDialog(this, false);
                    }
                }

            }
        });

//Disabled direct edits because copying and pasting links breaks the html
//        if (arrangeMode) {
            //Make links arrangeable
            container.find('ul').sortable(
                {
                    update:function () {
                        me.refresh()
                    },
                    connectWith:containerSelector + ' ul'
                }
            );
//        } else {
//            //Make links directly editable
//            container.find('li a').attr('contenteditable', 'true');
//        }
    };

    /**
     * Remove the elements we need for editing from the DOM
     */
    me.removeEditElements = function () {

        //Remove right click menu
        rcmEdit.pluginContextMenu(
            'destroy',
            containerSelector + ' li'
        );

        //Prevent links from being directly editable
        container.find('li a').removeAttr('contenteditable');

        //Prevent links from being arrangeable
        if (arrangeMode) {
            container.find('ul').sortable('destroy');
        }
    };

    /**
     * Displays a dialog box to edit or add links
     *
     * @param {Object} li the tag that we are editing
     * @param {Boolean} [deleteOnClose] will delete the link if user clicks cancel
     */
    me.showEditDialog = function (li, deleteOnClose) {

        var a = li.children('a');

        //Find out what kind of subMenu this link has
        var subMenu = 'none';
        if (li.children('div.columnCount_1').length > 0) {
            subMenu = 'oneCol';
        } else if (li.children('div.columnCount_2').length > 0) {
            subMenu = 'twoCol';
        }

        //Find out what css class this link has
        var cssClass = li.attr('class');
        if (typeof(cssClass) == 'undefined') {
            cssClass = '';
        }

        var okClicked = false;

        var text = $.dialogIn('text', 'Text', a.html());
        var href = $.dialogIn('text', 'Link Url', a.attr('href'));
        var cssClassInput = $.dialogIn(
            'select',
            'Display Style',
            {'':'Normal', 'heading':'Heading', 'bold':'Bold'},
            cssClass
        );
        var subMenuInput = $.dialogIn(
            'select',
            'SubMenu',
            {
                'none':'None',
                'oneCol':'Single column',
                'twoCol':'Double column'
            },
            subMenu
        );

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .append(text, href, cssClassInput, subMenuInput)
            .dialog({
                title:'Properties',
                modal:true,
                width:620,
                close:function () {
                    if (deleteOnClose && !okClicked) {
                        // Remove the new li that was created if the user clicks
                        // cancel
                        li.remove();
                        me.refresh();
                    }
                },
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {

                        //Get user-entered data from form
                        a.html(text.val());
                        a.attr('href',href.val());
                        li.attr('class', cssClassInput.val());

                        //Put this in a closure so modifySubMenu can call it
                        var button = this;
                        var continueOkClick = function () {
                            okClicked = true;
                            $(button).dialog("close");
                            me.refresh();
                        };

                        //Modify the subMenu if needed

                        if (subMenu != subMenuInput.val()) {
                            me.modifySubMenu(li, subMenu, subMenuInput.val(),
                                continueOkClick);
                        } else {
                            continueOkClick();
                        }
                    }
                }
            });

    };

    /**
     * Adds subMenus, removes subMenus, and changes subMenu column count
     *
     * @param {Object} li subMenu's parent li
     * @param {String} subMenu current subMenu setting
     * @param {String} newSubMenu new subMenu setting
     * @param {Function} successCallBack call this if all is well
     */
    me.modifySubMenu = function (li, subMenu, newSubMenu, successCallBack) {

        console.log(subMenu);
        console.log(newSubMenu);

        //Save our column-one contents in case we switch column counts
        var colOneContents = newLinkTemplate;
        if (subMenu != 'none') {
            colOneContents = li.find('ul.column_1').html();
        }

        //Change to one column
        if (newSubMenu == 'oneCol') {
            var modify = function () {
                li.children('div').remove();
                li.append(
                    '<div class="popup columnCount_1">'
                        + '<ul class="column column_1">'
                        + colOneContents
                        + '</ul>'
                        + '</div>'
                );
                successCallBack();
            };
            if (subMenu != 'twoCol') {
                modify();
            } else {
                $().confirm('By changing the subMenu from double column to '
                    + 'single column, you will be removing a column '
                    + 'which may contain links. Proceed?',
                    function () {
                        modify();
                    }
                );
            }
        }

        //Change to two column
        else if (newSubMenu == 'twoCol') {
            li.children('div').remove();
            li.append(
                '<div class="popup columnCount_2">'
                    + '<ul class="column column_1">'
                    + colOneContents
                    + '</ul>'
                    + '<ul class="column column_2">'
                    + newLinkTemplate
                    + '</ul>'
                    + '</div>'
            );
            successCallBack();
        }

        //Remove child menu
        else if (newSubMenu == 'none') {
            $().confirm('You are removing a subMenu which may contain '
                + 'links. Proceed?',
                function () {
                    li.children('div.popup').remove();
                    successCallBack();
                }
            );
        }

    }
};