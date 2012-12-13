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
     * @type {RcmNavigation}
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
    me.newLinkTemplate = '<li><a href="">Untitled Link</a></li>';

    /**
     * Called by content management system to make this plugin user-editable
     *
     * @return {Null}
     */
    me.initEdit = function(){
        container.delegate('li', 'dblclick', function(event){
            event.stopPropagation();
            me.showEditDialog($(this));
        });
        me.addEditElements();
    }

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
    }

    /**
     * Ensure everything is editable, especially after recent changes
     *
     * @return {Null}
     */
    me.refresh = function () {
        me.removeEditElements();
        me.addEditElements();
    }

    /**
     * Add the elements we need for editing to the DOM
     *
     * @return {Null}
     */
    me.addEditElements = function () {

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:containerSelector+' li',

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
                    name:'Create New Link',
                    icon:'edit',
                    callback:function () {
                        var newLi=$(me.newLinkTemplate);
                        $(this).after(newLi);
                        me.showEditDialog(newLi, true);
                    }
                },
                separator1:"-",
                arrangeMode:{
                    name:'Links are Movable via Drag and Drop'
                        +(arrangeMode?' (on)':''),
                    disabled:arrangeMode,
                    callback:function(){
                        me.removeEditElements();
                        arrangeMode = true;
                        me.addEditElements();
                    }
                },
                editMode:{
                    name:'Links are Editable'
                        +(!arrangeMode?' (on)':''),
                    disabled:!arrangeMode,
                    callback:function(){
                        me.removeEditElements();
                        arrangeMode = false;
                        me.addEditElements();
                    }
                },
                separator2:"-",
                deleteLink:{
                    name:'Delete Link',
                    icon:'delete',
                    callback:function () {
                        var li = $(this);
                        var a = $(this).children('a');
                        var ul = li.parent();
                        if(ul.children('li').length==1){
                            var msg='Cannot delete the last link in a menu.';
                            if(ul.parent().is('div.popup')){
                                msg += ' If you are trying to delete this ' +
                                    'submenu, you can do so from the properties' +
                                    ' window of this submenu\'s parent link.';
                            }
                            alert(msg);
                        }else{
                            $().confirm(
                                'Delete this link?<br><br>"' + a.html() + '"',
                                function () {
                                    li.remove();
                                }
                            );
                        }
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

        if (arrangeMode) {
            //Make links arrangeable
            container.find('ul').sortable(
                {
                    update:function () {
                        me.refresh()
                    },
                    connectWith:containerSelector + ' ul'
                }
            );
        } else {
            //Make links directly editable
            container.find('li a').attr('contenteditable', 'true');
        }
    }

    /**
     * Remove the elements we need for editing from the DOM
     *
     * @return {Null}
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
    }

    /**
     * Displays a dialog box to edit or add links
     *
     * @param {Object} a the a tag that we are editing
     * @param {Boolean} deleteOnClose will delete the link if user clicks cancel
     *
     * @return {Null}
     */
    me.showEditDialog = function (li, deleteOnClose) {

        var a = li.children('a');

        //Find out what kind of submenu this link has
        var submenu = 'none';
        if (li.children('div.columnCount_1').length > 0) {
            submenu = 'oneCol';
        } else if (li.children('div.columnCount_2').length > 0) {
            submenu = 'twoCol';
        }

        //Find out what css class this link has
        var cssClass = li.attr('class');
        if (typeof(cssClass) == 'undefined') {
            cssClass = '';
        }

        var okClicked = false;

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .addInput('text', 'Text', a.html())
            .addInput('href', 'Link Url', a.attr('href'))
            .addSelect(
                'cssClass', 'Display Style',
                {'':'Normal', 'heading':'Heading', 'bold':'Bold'},
                cssClass,
                true
            )
            .addSelect(
                'submenu', 'Submenu',
                {
                    'none':'None',
                    'oneCol':'Single column',
                    'twoCol':'Double column'
                },
                submenu
            )
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
                        a.html(form.find('[name=text]').val());
                        a.attr('href', form.find('[name=href]').val());
                        li.attr('class', form.find('[name=cssClass]').val());
                        var newSubmenu = form.find('[name=submenu]').val();

                        //Put this in a closure so modifySubMenu can call it
                        var button = this;
                        var continueOkClick = function () {
                            okClicked = true;
                            $(button).dialog("close");
                            me.refresh();
                        }

                        //Modify the submenu if needed

                        if (submenu != newSubmenu) {
                            me.modifySubmenu(li, submenu, newSubmenu,
                                continueOkClick);
                        } else {
                            continueOkClick();
                        }
                    }
                }
            });

    }

    /**
     * Adds submenus, removes submenus, and changes submenu column count
     *
     * @param {Object} li submenu's parent li
     * @param {String} submenu current submenu setting
     * @param {String} newSubmenu new submenu setting
     * @param {Function} successCallBack call this if all is well
     *
     * @return {Boolean} success
     */
    me.modifySubmenu = function (li, submenu, newSubmenu, successCallBack) {

        //Save our column-one contents in case we switch column counts
        var colOneContents = me.newLinkTemplate;
        if (submenu != 'none') {
            colOneContents = li.find('ul.column_1').html();
        }

        //Change to one column
        if (newSubmenu == 'oneCol') {
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
            }
            if (submenu != 'twoCol') {
                modify();
            } else {
                $().confirm('By changing the submenu from double column to '
                    + 'single column, you will be removing a column '
                    + 'which may contain links. Proceed?',
                    function () {
                        modify();
                    }
                );
            }
        }

        //Change to two column
        else if (newSubmenu == 'twoCol') {
            li.children('div').remove();
            li.append(
                '<div class="popup columnCount_2">'
                    + '<ul class="column column_1">'
                    + colOneContents
                    + '</ul>'
                    + '<ul class="column column_2">'
                    + me.newLinkTemplate
                    + '</ul>'
                    + '</div>'
            );
            successCallBack();
        }

        //Remove child menu
        else if (newSubmenu == 'none') {
            $().confirm('You are removing a submenu which may contain '
                + 'links. Proceed?',
                function () {
                    li.children('div.popup').remove();
                    successCallBack();
                }
            );
        }

    }
}