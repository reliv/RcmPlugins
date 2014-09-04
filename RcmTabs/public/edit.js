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
var RcmTabsEdit = function (instanceId, container, pluginHandler) {

    var me = this;
    me.pluginHandler = pluginHandler;

    var titleWrap = container.find('.titleWrap');
    var bodyWrap = container.find('.bodyWrap');
    var tabs = container.find('.tabs');
    var sortMode = false;
    var embedMsg = 'Place Embed Code Below:<br>';
    var ajaxEditHelper = new AjaxPluginEditHelper(
        instanceId, container, pluginHandler
    );

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {
        ajaxEditHelper.ajaxGetInstanceConfigs(me.completeInitEdit);
    };

    /**
     * Completes edit init after we make ajax call to get the un-altered embed
     * html
     * @param instanceConfig
     */
    this.completeInitEdit = function (instanceConfig) {
        tabs.tabs();//needed for dragging new plugin on page

        me.addRightClick(
            rcm.getPluginContainerSelector(instanceId) + ' .title', true
        );
        me.addRightClick(
            rcm.getPluginContainerSelector(instanceId) + ' .titleWrap', false
        );

        container.delegate('a', 'click', me.tabClick);

        //Convert rawHtml divs to text areas
        me.forEachTab(function (tabId, tabType, title, body) {
            if (tabType == 'rawHtml') {
                body.html(
                    embedMsg +
                        '<textarea class="rawHtmlWrap">' +
                        me.getInstanceConfigRawHtml(tabId, instanceConfig) +
                        '</textarea>'
                );
            }
        });

        me.refresh();
    };

    /**
     * Gets the raw html for a given tab id
     * @param tabId nt tab id
     * @param instanceConfig {Object} instance config from db
     */
    this.getInstanceConfigRawHtml = function (tabId, instanceConfig) {
        var matches = $.grep(
            instanceConfig['containers'],
            function (e) {
                return e.id == tabId;
            }
        );
        return matches[0].rawHtml;
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    this.getSaveData = function () {

        var tabContainers = [];

        me.forEachTab(function (tabId, tabType, title, body) {
            var tabData = {id: tabId, type: tabType};
            if (tabType == 'rawHtml') {
                tabData['rawHtml'] = body.find('textarea.rawHtmlWrap').val();
            }
            tabContainers.push(tabData);
        });

        return {
            containers: tabContainers
        }
    };

    this.addTab = function (type) {
        var newId = me.getGreatestTabId() + 1;

        me.addTabTitle(newId, type);

        switch (type) {
            case 'html':
                me.addHtmlTab(newId);
                break;
            case 'rawHtml':
                me.addRawHtmlTab(newId);
                break;
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
        me.pluginHandler.updateView();
    };

    this.addRawHtmlTab = function (newId) {
        bodyWrap.append($('<div class="body rawHtml" id="rcmTab_' + instanceId + '_' + newId + '" >' +
            embedMsg +
            '<textarea class="rawHtmlWrap"></textarea>' +
            '</div>'
        ));
    };

    this.tabClick = function () {
        //window['rcmEdit'].refreshEditors(container);

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
                callback: function () {
                    me.addTab('html')
                }
            },
            addRaw: {
                name: 'Add New Video Embed Tab',
                icon: 'edit',
                callback: function () {
                    me.addTab('rawHtml')
                }
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
            items['delete'] = {
                name: 'Delete Tab',
                icon: 'delete',
                callback: me.deleteTab
            }
        }

        $.contextMenu({
            selector: selector,
            items: items
        });
    };

    this.forEachTab = function (callback) {
        container.find('.title').each(
            function () {
                var title = $(this)
                var tabId = title.attr('data-tabId')
                callback(
                    //tabId
                    tabId,
                    //tabType
                    title.attr('data-tabType'),
                    //Title Ele
                    title,
                    //Body Ele
                    container.find('#rcmTab_' + instanceId + '_' + tabId)
                );
            });


    };

    this.getGreatestTabId = function () {
        var greatestId = 0;
        me.forEachTab(function (tabId) {
            if (tabId > greatestId) {
                greatestId = tabId;
            }
        });
        return greatestId;
    };
};