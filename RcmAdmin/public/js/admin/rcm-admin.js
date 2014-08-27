/**
 * **************************************************************
 * Angular JS module used to show HTML editor and toolbar on a page
 * @require:
 *  AngularJS
 *  TinyMce
 *  RcmHtmlEditor
 */

angular.module(
        'rcmAdmin',
        ['RcmHtmlEditor']
    )
/**
 * rcmAdminService
 */
    .factory(
        'rcmAdminService',
        [
            function () {
                return RcmAdminService;
            }
        ]
    )
/**
 * rcmAdmin.rcmAdminMenuActions
 */
    .directive(
        'rcmAdminMenuActions',
        [
            '$compile',
            'rcmAdminService',
            function ($compile, rcmAdminService) {

                var thisLink = function (scope, elm, attrs) {
                    scope.rcmAdminPage = rcmAdminService.getPage(
                        $compile(elm.contents())(scope)
                    );
                };

                return {
                    restrict: 'A',
                    link: thisLink
                }
            }
        ]
    )
/**
 * rcmAdmin.rcmAdminEditButton
 */
    .directive(
        'rcmAdminEditButton',
        [
            'rcmAdminService',
            function (rcmAdminService) {

                var thisLink = function (scope, elm, attrs) {

                    scope.rcmAdminPage = rcmAdminService.getPage();

                    var editingState = attrs.rcmAdminEditButton;

                    elm.unbind();
                    elm.bind('click', null, function () {

                        rcmAdminService.rcmAdminEditButtonAction(
                            editingState,
                            function () {
                                scope.$apply();
                            }
                        );
                    });
                };

                return {
                    restrict: 'A',
                    link: thisLink
                }
            }
        ]
    )
/**
 * rcmAdmin.richedit
 */
    .directive(
        'richedit',
        [
            'rcmAdminService',
            'rcmHtmlEditorInit',
            'rcmHtmlEditorDestroy',
            function (rcmAdminService, rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

                return {
                    link: rcmAdminService.getHtmlEditorLink(rcmHtmlEditorInit, rcmHtmlEditorDestroy),
                    scope: {},
                    restrict: 'A',
                    require: '?ngModel'
                }
            }
        ]
    )
/**
 * rcmAdmin.textedit
 */
    .directive(
        'textedit',
        [
            'rcmAdminService',
            'rcmHtmlEditorInit',
            'rcmHtmlEditorDestroy',
            function (rcmAdminService, rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

                return {
                    link: rcmAdminService.getHtmlEditorLink(rcmHtmlEditorInit, rcmHtmlEditorDestroy),
                    scope: {},
                    restrict: 'A',
                    require: '?ngModel'
                }
            }
        ]
    );
rcm.addAngularModule('rcmAdmin');

/* <RcmAdminService> */
var RcmAdminService = {

    /**
     * page
     */
    page: null,

    /**
     * config
     */
    config: {
        unlockMessages: {
            sitewide: {
                title: "Unlock Site-Wide Plugins?",
                message: "Please Note: Any changes you make to a Site-Wide plugin will be published and made live when you save your changes."
            },
            page: {
                title: "Unlock Page Plugins?",
                message: null
            },
            layout: {
                title: "Unlock Layout Plugins?",
                message: null
            }
        }
    },

    rcmAdminEditButtonAction: function (editingState, onComplete) {

        var page = RcmAdminService.getPage();
        page.build(
            function (page) {

                if (!editingState) {
                    editingState = 'page';
                }

                if (editingState == 'arrange') {
                    //scope.rcmAdminPage.arrange();
                    page.setEditingOn('page');
                    page.setEditingOn('layout');
                    page.setEditingOn('sitewide');
                    RcmAvailablePluginsMenu.build();
                    return;
                }

                if (editingState == 'cancel') {
                    page.cancel();
                    return;
                }

                if (editingState == 'save') {
                    page.save();
                    return;
                }

                page.setEditingOn(editingState);

                if (typeof onComplete === 'function') {

                    onComplete();
                }
            }
        );
    },

    RcmElmParser: {

        getPageElm: function () {
            return jQuery('body');
        },
        getContainerElms: function (elm) {
            return elm.find('[data-containerId]');
        },
        getContainerId: function (elm) {
            return elm.attr('data-containerId');
        },
        getPluginId: function (elm) {
            return elm.attr('data-rcmPluginInstanceId');
        },

        getRichEditorElms: function (elm) {
            var editors = elm.find('[data-richEdit]');
            return editors;
        },

        getTextEditorElms: function (elm) {
            return elm.find('[data-textEdit]');
        }
    },

    getHtmlEditorLink: function (rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

        return function (scope, elm, attrs, ngModel, config) {

            scope.rcmAdminPage = RcmAdminService.getPage();

            var pluginId = elm.attr('html-editor-plugin-id');

            //if (scope.rcmAdminPage.editMode && scope.rcmAdminPage.plugins[pluginId].editMode) {
            //    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
            //} else {
            //    rcmHtmlEditorDestroy(attrs.id);
            //}

            scope.$watch(
                'rcmAdminPage.editing',
                function (newValue, oldValue) {

                    if (newValue != oldValue) {

                        if (!scope.rcmAdminPage.plugins[pluginId]) {
                            return;
                        }

                        if (scope.rcmAdminPage.editing.length > 0 && scope.rcmAdminPage.plugins[pluginId].canEdit()) {
                            rcmHtmlEditorInit(
                                scope,
                                elm,
                                attrs,
                                ngModel,
                                config,
                                function (rcmHtmlEditor, rcmHtmlEditorService) {
                                    //scope.$apply()
                                }
                            );
                        } else {
                            rcmHtmlEditorDestroy(
                                attrs.id,
                                function (rcmHtmlEditorService) {
                                    //scope.$apply()
                                }
                            );
                        }
                    }
                },
                true
            );
        }
    },

    /**
     * RcmEvents
     * @constructor
     */
    RcmEvents: function () {

        var self = this;

        self.events = {};

        self.on = function (event, method) {

            if (!self.events[event]) {
                self.events[event] = [];
            }

            self.events[event].push(method);
        };

        self.trigger = function (event, args) {

            if (self.events[event]) {
                jQuery.each(
                    self.events[event],
                    function (index, value) {
                        value(args);
                    }
                );
            }
        };
    },

    /**
     * getPage
     * @param onBuilt
     * @returns {null}
     */
    getPage: function (onBuilt) {

        if (!RcmAdminService.page) {

            RcmAdminService.page = new RcmAdminService.RcmPage(
                document,
                RcmAdminService.RcmElmParser.getPageElm(),
                onBuilt
            );
        }
        return RcmAdminService.page
    },

    RcmPageModel: {

        getElm: function (onComplete) {

            var elm = jQuery('body');

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm
        },

        getData: function (onComplete) {

            var data = {};
            data.title = jQuery(document).find("head > title").text();
            data.url = jQuery(location).attr('href');
            data.description = jQuery('meta[name="description"]').attr('content');
            data.keywords = jQuery('meta[name="keywords"]').attr('content');

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    },

    RcmContainerModel: {

        getElms: function (onComplete) {

            var pageElm = RcmAdminService.RcmPageModel.getElm();

            var elms = pageElm.find('[data-containerId]');

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms
        },

        getElm: function (id, onComplete) {

            var pageElm = RcmAdminService.RcmPageModel.getElm();

            var elm = pageElm.find("[data-containerId='" + id + "']");

            if (typeof onComplete === 'function') {
                onComplete(elm[0])
            }

            return elm[0]
        },

        getData: function (onComplete) {

            var data = {};
            data.id = self.elm.attr('data-containerId');

            data.revision = self.elm.attr('data-containerRevision');

            if (self.elm.attr('data-isPageContainer') == 'Y') {
                self.data.type = 'page';
            } else {
                self.data.type = 'layout';
            }

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    },

    /**
     * RcmPluginModel
     */
    RcmPluginModel: {

        getElm: function (containerId, id, onComplete) {

            var containerElm = RcmAdminService.RcmContainerModel.getElm(containerId);

            var elm = containerElm.find('[data-rcmPluginInstanceId="' + id + '"]');

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        getEditorElms: function (containerId, id, onComplete) {

            var elm = RcmAdminService.RcmPluginModel.getElm(containerId, id);

            var richEditors = elm.find('[data-richEdit]');
            var textEditors = elm.find('[data-textEdit]');

            var elms = {};

            richEditors.each(
                function (index) {
                    this.setAttribute('html-editor-plugin-id', self.data.instanceId);
                    elms[this.getAttribute('id')] = this;
                }
            );

            textEditors.each(
                function (index) {
                    this.setAttribute('html-editor-plugin-id', self.data.instanceId);
                    elms[this.getAttribute('id')] = this;
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },

        getData: function (containerId, id, onComplete) {

            var data = {};

            var elm = RcmAdminService.RcmPluginModel.getElm(containerId, id);

            data.containerId = containerId;

            data.instanceId = elm.attr('data-rcmPluginInstanceId');

            data.isSitewide = (elm.attr('data-rcmSiteWidePlugin') == '1');
            data.name = elm.attr('data-rcmPluginName');
            data.rank = order;

            data.sitewideName = elm.attr('data-rcmPluginDisplayName');

            var resized = (elm.attr('data-rcmPluginResized') == 'Y');

            if (resized) {
                data.size = elm.width() + ',' + elm.height();
            }

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        }
    },

    /**
     * RcmPage
     * @param document
     * @param elm
     * @param onInitted
     * @constructor
     */
    RcmPage: function (document, elm, onInitted) {

        var self = this;
        self.document = document;
        self.elm = elm;
        self.events = new RcmAdminService.RcmEvents();
        self.editing = []; // page, layout, sitewide
        self.editMode = false;

        self.data = {
            url: '',
            title: '',
            description: '',
            keywords: '',
            pluginData: []
        };

        self.containers = {};
        self.plugins = {};

        /**
         * setEditingOn
         * @param type
         * @returns viod
         */
        self.setEditingOn = function (type) {

            if (self.editing.indexOf(type) < 0) {
                self.editing.push(type);
                self.onEditChange();
            }
        };

        /**
         * setEditingOff
         * @param type
         * @returns viod
         */
        self.setEditingOff = function (type) {

            if (self.editing.indexOf(type) > -1) {

                self.editing.splice(
                    self.editing.indexOf(type),
                    1
                )

                self.onEditChange();
            }
        };

        /**
         * onEditChange
         */
        self.onEditChange = function () {

            self.editMode = (self.editing.length > 0)

            self.events.trigger('editingStateChange', {editMode: self.editMode, editing: self.editing});
        };

        /**
         * save
         */
        self.save = function (onSaved) {
            // loop containers and fire saves... aggregate data and sent to server

            self.build(
                function (page) {

                    console.log('page.save');
                }
            );

        };

        /**
         * cancel
         */
        self.cancel = function () {

            window.location = window.location.pathname;
        };

        /**
         * buildData
         * @param onBuilt
         */
        self.buildData = function (onBuilt) {

            self.data.title = jQuery(document).find("head > title").text();
            self.data.url = jQuery(location).attr('href');
            self.data.description = jQuery('meta[name="description"]').attr('content');
            self.data.keywords = jQuery('meta[name="keywords"]').attr('content');

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        /**
         * buildContainers
         * @param onBuilt
         */
        self.buildContainers = function (onBuilt) {

            var containers = RcmAdminService.RcmElmParser.getContainerElms(self.elm);

            var containerElm = null;
            var containerId = null;

            jQuery.each(
                containers,
                function (key, value) {

                    containerElm = jQuery(value);
                    containerId = RcmAdminService.RcmElmParser.getContainerId(containerElm);

                    if (!self.containers[containerId]) {

                        self.containers[containerId] = new RcmAdminService.RcmContainer(self, containerElm);
                    }
                    self.containers[containerId].build();
                }
            );

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        /**
         * build
         * @param onBuilt
         */
        self.build = function (onBuilt) {

            self.buildData(
                function (rcmPage) {
                    self.buildContainers(onBuilt);
                }
            );
        };

        /**
         * init
         * @param onInitted
         */
        self.init = function (onInitted) {

            self.build(onInitted);
        };

        self.init(onInitted);
    },

    /**
     * RcmContainer
     * @param page
     * @param elm
     * @constructor
     */
    RcmContainer: function (page, elm) {

        var self = this;

        self.page = page;
        self.id = null;
        self.elm = elm;
        self.editMode = false;

        self.data = {
            id: null,
            revision: null,
            type: null
        }

        self.plugins = {};


        /**
         * save
         * @param onSaved
         */
        self.save = function (onSaved) {
            // loop plugins and fire saves...

            if (typeof onSaved === 'function') {
                onSaved(self);
            }
        };

        /**
         * canEdit
         * @param editing
         * @returns {boolean}
         */
        self.canEdit = function (editing) {

            return (editing.indexOf(self.data.type) > -1);
        };

        /**
         * onEditChange
         * @param args
         */
        self.onEditChange = function (args) {

            self.editMode = self.canEdit(args.editing);
        };

        /**
         * buildData
         * @param onBuilt
         */
        self.buildData = function (onBuilt) {

            self.data.id = self.elm.attr('data-containerId');

            self.data.revision = self.elm.attr('data-containerRevision');

            if (self.elm.attr('data-isPageContainer') == 'Y') {
                self.data.type = 'page';
            } else {
                self.data.type = 'layout';
            }

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        self.addPlugin = function (pluginId, pluginElm, order, onAdd) {

            // @todo clean this up
            if (!self.plugins[pluginId]) {
                self.plugins[pluginId] = new RcmAdminService.RcmPlugin(self, pluginElm, order);
            }

            self.plugins[pluginId].order = order;
            self.plugins[pluginId].build();
            self.page.plugins[pluginId] = self.plugins[pluginId];

            if (typeof onAdd === 'function') {
                onAdd(self.plugins[pluginId]);
            }
        }

        self.removePlugin = function (pluginId) {

            delete(self.plugins[pluginId]);
            delete(self.page.plugins[pluginId]);
        }

        /**
         * buildPlugins
         * @param onBuilt
         */
        self.buildPlugins = function (onBuilt) {

            var plugins = self.elm.find("[data-rcmpluginname]");

            var pluginElm = null;
            var pluginId = null;

            var currentPlugins = {};
            var leftoverPlugins = {};

            jQuery.extend(leftoverPlugins, self.plugins);

            jQuery.each(
                plugins,
                function (key, value) {

                    pluginElm = jQuery(value);
                    pluginId = RcmAdminService.RcmElmParser.getPluginId(pluginElm);

                    self.addPlugin(
                        pluginId,
                        pluginElm,
                        key,
                        function (plugin) {
                            // remove if found
                            if (leftoverPlugins[pluginId]) {
                                delete(leftoverPlugins[pluginId]);
                            }
                        }
                    );
                }
            );

            // remove from this container if there are any that have been removed
            jQuery.each(
                leftoverPlugins,
                function (key, value) {
                    if (value) {
                        self.removePlugin(key);
                    }

                }
            );

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        /**
         * build
         * @param onBuilt
         */
        self.build = function (onBuilt) {

            self.buildData(
                function (rcmContainer) {

                    self.buildPlugins(
                        function (container) {

                            if (typeof onBuilt === 'function') {
                                onBuilt(self);
                            }
                        }
                    );
                }
            );
        };

        /**
         * init
         */
        self.init = function () {

            self.page.events.on('editingStateChange', self.onEditChange);
        };

        self.init();
    },

    /**
     * RcmPlugin
     * @param container
     * @param elm
     * @param index
     * @constructor
     */
    RcmPlugin: function (container, elm, index) {

        var self = this;

        self.container = container;
        self.elm = elm;
        self.order = index;
        self.editMode = null;
        self.pluginObject = null;

        self.data = {
            containerId: null,
            // sitewide name
            instanceConfig: [],
            instanceId: null,
            isSitewide: false,
            name: '',
            // order
            rank: 0,
            saveData: {},
            sitewideName: null,
            size: null

            // float
        };

        /**
         * getType
         * @returns string
         */
        self.getType = function () {

            if (self.data.isSitewide) {
                return 'sitewide';
            }

            return self.container.data.type;
        }

        /**
         * initEdit
         * @param onInitted
         */
        self.initEdit = function (onInitted) {

            var pluginObject = self.getPluginObject()

            if (self.canEdit()) {

                self.enableView(
                    function (plugin) {
                        if (pluginObject.initEdit) {

                            pluginObject.initEdit();
                        }

                        if (typeof onInitted === 'function') {
                            onInitted(self);
                        }
                    }
                );
            }
        };

        /**
         * cancelEdit
         * @param onCanceled
         */
        self.cancelEdit = function (onCanceled) {

            if (!self.canEdit()) {
                self.disableView(
                    function (plugin) {
                        if (typeof onCanceled === 'function') {
                            onCanceled(self);
                        }
                    }
                );
            }
        };

        /**
         * save
         * @param onSaved
         */
        self.save = function (onSaved) {

            var pluginObject = self.getPluginObject()

            if (self.canEdit()) {

                if (pluginObject.getSaveData) {

                    var saveData = self.getPluginObject.getSaveData();

                    // @todo - get html editor data and merge with saveData
                    self.data.saveData = saveData;
                }

                if (typeof onSaved === 'function') {
                    onSaved(self);
                }
            }
        };

        /**
         * canEdit
         * @returns boolean
         */
        self.canEdit = function () {

            var editing = self.container.page.editing;

            var type = self.getType();

            return (editing.indexOf(type) > -1);
        };

        /**
         * getPluginObject
         * @returns RcmPluginEditJs
         */
        self.getPluginObject = function () {

            if (self.pluginObject) {

                return self.pluginObject;
            }

            var name = elm.attr('data-rcmPluginName');
            var id = elm.attr('data-rcmPluginInstanceId');
            var pluginContainer = self.elm.find('.rcmPluginContainer');

            if (name && id && pluginContainer) {

                var className = name + 'Edit';
                var editClass = window[className];

                if (editClass) {
                    // first child of plugin
                    self.pluginObject = new editClass(id, pluginContainer);
                    return self.pluginObject;
                }
            }

            self.pluginObject = new RcmAdminService.RcmPluginEditJs(id, pluginContainer, name);
            return self.pluginObject;
        };

        self.unlock = function () {

            jQuery().confirm(
                RcmAdminService.config.unlockMessages[self.getType()].message,
                function () {
                    self.container.page.setEditingOn(
                        self.getType()
                    );
                },
                null,
                RcmAdminService.config.unlockMessages[self.getType()].title
            );
        };

        /**
         * disableView
         * @param onDisabled
         */
        self.disableView = function (onDisabled) {

            self.disableLinks();

            // Add CSS
            self.elm.addClass('rcmPluginLocked');

            // context menu and double click
            self.elm.dblclick(self.unlock);
            //self.elm.click(unlock);

            jQuery.contextMenu(
                {
                    selector: '[data-rcmPluginInstanceId=' + self.data.instanceId + ']',

                    //Here are the right click menu options
                    items: {
                        unlockMe: {
                            name: 'Unlock',
                            icon: 'delete',
                            callback: self.unlock
                        }
                    }
                }
            );

            if (typeof onDisabled === 'function') {
                onDisabled(self);
            }
        };

        /**
         * enableView
         * @param onEnabled
         */
        self.enableView = function (onEnabled) {

            self.disableLinks();

            self.elm.removeClass('rcmPluginLocked');
            self.elm.unbind('dblclick');

            jQuery.contextMenu('destroy', '[data-rcmPluginInstanceId=' + self.data.instanceId + ']');

            if (typeof onEnabled === 'function') {
                onEnabled(self);
            }
        };

        /**
         * disableLinks
         */
        self.disableLinks = function () {

            // Disable normal events
            self.elm.find('*').unbind();
            var donDoIt = function () {
                return false;
            };
            self.elm.find('button').click(donDoIt);
            self.elm.find('a').click(donDoIt);
            self.elm.find('form').submit(donDoIt);
        }

        /**
         * onEditChange
         * @param args
         */
        self.onEditChange = function (args) {

            var editMode = self.canEdit(args.editing);

            if (self.editMode !== editMode) {

                self.editMode = editMode;

                if (self.editMode) {

                    self.initEdit();

                } else {

                    self.cancelEdit();
                }
            }
        };

        /**
         * buildData
         * @param onBuilt
         */
        self.buildData = function (onBuilt) {

            self.data.containerId = self.container.data.id;

            self.data.instanceId = self.elm.attr('data-rcmPluginInstanceId');

            self.data.isSitewide = (self.elm.attr('data-rcmSiteWidePlugin') == '1');
            self.data.name = self.elm.attr('data-rcmPluginName');
            self.data.rank = self.order;

            self.data.sitewideName = self.elm.attr('data-rcmPluginDisplayName');

            var resized = (self.elm.attr('data-rcmPluginResized') == 'Y');

            if (resized) {
                self.data.size = self.elm.width() + ',' + self.elm.height();
            }

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        /**
         * buildEditors
         * @param onBuilt
         */
        self.buildEditors = function (onBuilt) {

            var richEditors = RcmAdminService.RcmElmParser.getRichEditorElms(self.elm);

            richEditors.each(
                function (index) {
                    this.setAttribute('html-editor-plugin-id', self.data.instanceId);
                }
            );

            var textEditors = RcmAdminService.RcmElmParser.getTextEditorElms(self.elm);

            textEditors.each(
                function (index) {
                    this.setAttribute('html-editor-plugin-id', self.data.instanceId);
                }
            );

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        }

        /**
         * build
         * @param onBuilt
         */
        self.build = function (onBuilt) {

            self.buildData(
                function (plugin) {
                    self.buildEditors(
                        function (plugin) {
                            if (typeof onBuilt === 'function') {
                                onBuilt(self);
                            }
                        }
                    )
                }
            );
        };

        /**
         * init
         */
        self.init = function () {

            self.container.page.events.on('editingStateChange', self.onEditChange);
        };

        self.init();
    },

    /**
     * Default Edit JS - does nothing - interface
     * @param id
     * @param pluginContainer
     * @constructor
     */
    RcmPluginEditJs: function (id, pluginContainer, name) {

        var self = this;
        self.id = id;
        //self.pluginContainer = pluginContainer;

        self.initEdit = function () {
            //console.warn('initEdit: no edit js object found for '+name+' - using default for: ' + self.id);
        };

        self.getSaveData = function () {
            //console.warn('getSaveData: no edit js object found '+name+' - using default for: ' + self.id);
            return {};
        };
    }
};
/* </RcmAdminService> */