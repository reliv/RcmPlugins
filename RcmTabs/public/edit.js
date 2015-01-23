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

    var titleWrapSelector = '.nav.nav-tabs'; // titleWrap
    var bodySelector = '.tab-content'; // bodyWrap
    var titleSelector = '.title'// .

    var titleWrap = container.find(titleWrapSelector);
    var bodyWrap = container.find(bodySelector);
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
        me.buildTabs();

        me.addRightClick(
            pluginHandler.model.getPluginContainerSelector(instanceId) + ' ' + titleSelector,
            true
        );
        me.addRightClick(
            pluginHandler.model.getPluginContainerSelector(instanceId) + '' + titleWrapSelector,
            false
        );

        //Convert rawHtml divs to text areas
        me.forEachTab(
            function (tabId, tabType, title, body) {

                title.find('a').click(function (e) {
                    e.preventDefault();
                    $(this).tab('show')
                });

                if (tabType == 'rawHtml') {
                    body.html(
                        embedMsg +
                        '<textarea class="rawHtmlWrap">' +
                        me.getInstanceConfigRawHtml(tabId, instanceConfig) +
                        '</textarea>'
                    );
                }
            }
        );

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

        me.forEachTab(
            function (tabId, tabType, title, body) {
                var tabData = {id: tabId, type: tabType};
                if (tabType == 'rawHtml') {
                    tabData['rawHtml'] = body.find('textarea.rawHtmlWrap').val();
                }
                tabContainers.push(tabData);
            }
        );

        return {
            containers: tabContainers
        }
    };

    this.addTab = function (type) {
        var newId = parseInt(me.getGreatestTabId()) + 1;

        me.addTabTitle(newId, type);

        switch (type) {
            case 'html':
                me.addHtmlTab(newId);
                break;
            case 'rawHtml':
                me.addRawHtmlTab(newId);
                break;
        }
        me.pluginHandler.updateView();
        me.refresh();
    };

    this.deleteTab = function () {
        container.find('[data-tabId=' + $(this).attr('data-tabId') + ']')
            .remove();
        me.refresh();
    };

    this.addTabTitle = function (newId, type) {

        titleWrap.append(
            $(
                '<li role="presentation" ' +
                'class="title" ' +
                'data-tabId="' + newId + '" data-tabType="' + type + '">' +
                '<a href="#rcmTab_' + instanceId + '_' + newId + '" ' +
                'aria-controls="rcmTab_<?= $this->instanceId ?>_' + newId + '" ' +
                'role="tab" ' +
                'data-toggle="tab">' +
                '<div data-textedit="tab_title_' + newId + '">' +
                'New Tab' +
                '</div>' +
                '</a>' +
                '</li>'
            )
        );
    };

    this.addHtmlTab = function (newId) {

        bodyWrap.append(
            $(
                '<div role="tabpanel" ' +
                'class="tab-pane" ' +
                'id="rcmTab_' + instanceId + '_' + newId + '">' +
                '<div data-richedit="tab_content_' + newId + '">' +
                '<h1>Lorem ipsum</h1>' +
                '<p>Lorem ipsum</p>' +
                '</div>' +
                '</div>'
            )
        );
    };

    this.addRawHtmlTab = function (newId) {

        bodyWrap.append(
            $(
                '<div role="tabpanel" ' +
                'class="tab-pane" ' +
                'id="rcmTab_' + instanceId + '_' + newId + '">' +
                '<div class="rawHtmlWrap">' +
                embedMsg +
                '<textarea class="rawHtmlWrap"></textarea>' +
                '</div>' +
                '</div>'
            )
        );
    };

    this.buildTabs = function(){
        console.log('buildTabs');
        tabs.tab();
        //tabs.tabs();//needed for dragging new plugin on page
    };

    this.refresh = function () {
        console.log('refresh');
        tabs.tab('show');
        //tabs.tabs('refresh');

        container.find(titleSelector).find('div').keydown(
            function (event) {
                event.stopPropagation();
            }
        );

        var tabAs = tabs.find('li a');
        try {
            tabs.find(titleWrapSelector).sortable('destroy');
            tabAs.attr('style', '');// Clear Draggable pointer
        } catch (err) {
            //its ok if we couldn't destroy
        }
        if (sortMode) {
            tabs.find(titleWrapSelector).sortable();
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

        $.contextMenu(
            {
                selector: selector,
                items: items
            }
        );
    };

    this.forEachTab = function (callback) {
        container.find(titleSelector).each(
            function () {
                var title = $(this);
                var tabId = title.attr('data-tabId');
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
            }
        );


    };

    this.getGreatestTabId = function () {
        var greatestId = 0;
        me.forEachTab(
            function (tabId) {
                if (tabId > greatestId) {
                    greatestId = tabId;
                }
            }
        );
        return greatestId;
    };
};