/**
 * RcmNavResponsive
 *
 * JS for editing RcmNavResponsive
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
var RcmNavResponsiveEdit = function (instanceId, container) {
    var self = this;

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
    var newMenuTemplate = '<ul><li><a href="">Untitled Link</a></li><ul>';

    var mainUl = container.find('ul.sf-menu');

    /**
     * Called by content management system to make this plugin user-editable
     */
    self.initEdit = function () {
        container.delegate('li', 'dblclick', function (event) {
            event.stopPropagation();
            self.showEditDialog($(this));
        });
        self.addEditElements();
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    self.getSaveData = function () {
        var children = [];
        $.each(mainUl.children(), function () {
            var bigLi = $(this);
            var bigA = bigLi.find('a');
            var bigLink = {
                text: bigA.html(),
                href: bigA.attr('href'),
                children: []
            };
            $.each(bigLi.find('ul').children(), function () {
                var littleA = $(this).find('a');
                bigLink.children.push(
                    {
                        text: littleA.html(),
                        href: littleA.attr('href')
                    }
                )
            });
            children.push(bigLink);
        });
        return {children: children};
    };

    /**
     * Add the elements we need for editing to the DOM
     */
    self.addEditElements = function () {

        //Remove right click menu
        $.contextMenu(
            'destroy',
            containerSelector + ' li'
        );

        //Add right click menu
        $.contextMenu({
            selector: containerSelector + ' li',

            //Make nav stay popped up when right click menu opens
            build: function (target) {
                self.popupToKeepUp = $(target).closest('div.popup');
                self.popupToKeepUp.attr('style', 'left:auto')
            },

            events: {
                hide: function () {
                    //Keep nav open for 200ms after the right click menu closes
                    //to ensure the nav stays open if the mouse is still over it
                    setTimeout(function () {
                        self.popupToKeepUp.removeAttr('style')
                    }, 200);
                }
            },


            //Here are the right click menu options
            items: {
                createNew: {
                    name: 'Create New Link',
                    icon: 'edit',
                    callback: function () {
                        var newLi = $(newLinkTemplate);
                        $(this).after(newLi);
                        if(newLi.parent().parent().prop('tagName')!='LI'){
                            //If not a sub-link, add a sub-menu
                            newLi.append($(newMenuTemplate));
                        }
                        self.showEditDialog(newLi, true);
                    }
                },
                separator2: "-",
                deleteLink: {
                    name: 'Delete Link',
                    icon: 'delete',
                    callback: function () {
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
                separator3: "-",
                edit: {
                    name: 'Edit Link Properties',
                    icon: 'edit',
                    callback: function () {
                        self.showEditDialog(this, false);
                    }
                }

            }
        });


        try {
            //Prevent links from being arrangeable
            container.find('ul').sortable('destroy');
        } catch(e){
            //do nothing, getting here just means we weren't in edit mode before
        }
        //Make links arrangeable
        container.find('ul').sortable(
            {
                update: function () {
                    self.addEditElements()
                },
                connectWith: containerSelector + ' ul'
            }
        );
    };

    /**
     * Displays a dialog box to edit or add links
     *
     * @param {Object} li the tag that we are editing
     * @param {Boolean} [deleteOnClose] will delete the link if user clicks cancel
     */
    self.showEditDialog = function (li, deleteOnClose) {

        var a = li.children('a');
        var okClicked = false;
        var text = $.dialogIn('text', 'Text', a.html());
        var href = $.dialogIn('url', 'Link Url', a.attr('href'));

        //Create and show our edit dialog
        var form = $('<form></form>')
            .addClass('simple')
            .append(text, href)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                close: function () {
                    if (deleteOnClose && !okClicked) {
                        // Remove the new li that was created if the user clicks
                        // cancel
                        li.remove();
                        self.addEditElements();
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
                        okClicked = true;
                        $(this).dialog("close");
                        self.addEditElements();
                    }
                }
            });

    };
};