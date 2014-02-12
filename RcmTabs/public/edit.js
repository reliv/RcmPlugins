/**
 * RcmPortalAnnouncementBox
 *
 * JS for editing RcmPortalAnnouncementBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   http://www.nolicense.com None
 * @version   GIT: <git_id>
 */
var RcmTabsEdit = function (instanceId, container) {

    var me = this;

    var titleWrap = container.find('.titleWrap');
    var bodyWrap = container.find('.bodyWrap');
    var tabs = container.find('.tabs');
    var sortMode = false;

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {

        me.addRightClick(
            rcm.getPluginContainerSelector(instanceId) + ' .title', true
        );
        me.addRightClick(
            rcm.getPluginContainerSelector(instanceId) + ' .titleWrap', false
        );

        container.delegate('a', 'click', me.tabClick);

        me.refresh();
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    this.getSaveData = function () {

        var tabContainers = [];

        me.forEachTabTitle(function () {
            tabContainers.push({
                id: $(this).attr('data-tabId')
            })
        });

        return {
            containers: tabContainers
        }
    };

    this.addTab = function () {
        var newId = me.getGreatestTabId() + 1;
        var tabType = 'html';

        me.addTabTitle(newId, tabType);

        if (tabType == 'html') {
            me.addHtmlTab(newId);
        }

        me.refresh();
    };

    this.deleteTab = function () {
        container.find('[data-tabId=' + $(this).attr('data-tabId') + ']')
            .remove();
        me.refresh();
    };

    this.addTabTitle = function (newId, type) {
        titleWrap.append($(
            '<li class="title" data-tabId="' + newId + '" data-tabType="' + type + '">' +
                '<a data-textedit="tab_title_' + newId + '" href="#rcmTab_' + instanceId + '_' + newId + '">' +
                'New Tab' +
                '</a>' +
                '</li>'
        ));
    };

    this.addHtmlTab = function (newId) {
        bodyWrap.append($('<div class="body" id="rcmTab_' + instanceId + '_' + newId + '" data-richedit="tab_content_' + newId + '">' +
            '<h1>Lorem ipsum</h1>' +
            '<p>Lorem ipsum</p>' +
            '</div>'
        ));
    };

    this.tabClick = function () {
        window['rcmEdit'].refreshEditors(container);
    };

    this.refresh = function () {
        tabs.tabs('refresh');

        container.find('.title').find('div').keydown(function (event) {
            event.stopPropagation();
        });

        var tabAs = tabs.find('li a');
        try {
            tabs.find('.titleWrap').sortable('destroy');
            tabAs.attr('style', '');// Clear Draggable pointer
        } catch (err) {
            //its ok if we couldn't destroy
        }
        if (sortMode) {
            tabs.find('.titleWrap').sortable();
            tabAs.attr('style', 'cursor: move;');// Draggable pointer
        }
    };

    this.addRightClick = function (selector, addDelete) {
        var items = {
            add: {
                name: 'Add New Tab',
                icon: 'edit',
                callback: me.addTab
            },
            sort: {
                name: 'Toggle Tab Sorting vs Editing',
                icon: 'edit',
                callback: function () {
                    sortMode = !sortMode;
                    me.refresh();
                }
            }
        };

        if (addDelete) {
            items.delete = {
                name: 'Delete Tab',
                icon: 'delete',
                callback: me.deleteTab
            }
        }

        window['rcmEdit'].pluginContextMenu({
            selector: selector,
            items: items
        });
    };

    this.forEachTabTitle = function (callback) {
        container.find('.title').each(callback);
    };

    this.getGreatestTabId = function () {
        var greatestId = 0;
        me.forEachTabTitle(function () {
            var id = parseInt($(this).attr('data-tabId'));
            if (id > greatestId) {
                greatestId = id;
            }
        });
        return greatestId;
    };
};