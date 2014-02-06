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
 * @package   RcmPlugins\RcmPortalAnnouncementBox
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   http://www.nolicense.com None
 * @version   GIT: <git_id>
 */
var RcmTabsEdit = function (instanceId, container) {

    var me = this;

    var titleWrap = container.find('.titleWrap');
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

        container.delegate('a', 'click', me.tabChanged);

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
                id: $(this).attr('data-tabId'),
                type: $(this).attr('data-containerType')
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
        var newLi = $('<li class="title" data-tabId="' + newId + '" data-containerType="' + type + '"></li>');
        var newA = $('<a href="#tab_' + instanceId + '_' + newId + '"></a>');
        var newDiv = $('<div data-textedit="tab_title_' + newId + '">New Tab</div>');

        $(newA).append(newDiv);
        $(newLi).append(newA);
        titleWrap.append(newLi);
    };

    this.addHtmlTab = function (newId) {
        var newDiv = $('<div class="body" id="tab_' + instanceId + '_' + newId + '"></div>');
        var newHtmlContainer = $('<div data-richedit="tab_content_' + newId + '"></div>');
        var newDummyData = '<h1>Lorem ipsum</h1><p>Lorem ipsum</p>';

        $(newHtmlContainer).html(newDummyData);
        $(newDiv).append(newHtmlContainer);
        tabs.append(newDiv);
    };

    this.tabChanged = function () {
        window['rcmEdit'].refreshEditors(container);
    };

    this.refresh = function () {
        tabs.tabs('refresh');

        container.find('.title').find('div').keydown(function (event) {
            event.stopPropagation();
        });

        try {
            tabs.find('.titleWrap').sortable('destroy');
        } catch (err) {
            //its ok if we couldn't destroy
        }
        if (sortMode) {
            tabs.find('.titleWrap').sortable();
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