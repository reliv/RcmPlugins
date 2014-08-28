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
     * RcmEventManager
     * @constructor
     */
    RcmEventManager: {

        events: {},

        on: function (event, method) {

            if (!this.events[event]) {
                this.events[event] = [];
            }

            this.events[event].push(method);
        },

        trigger: function (event, args) {

            if (this.events[event]) {
                jQuery.each(
                    this.events[event],
                    function (index, value) {
                        value(args);
                    }
                );
            }
        }
    },

    /**
     * getPage
     * @param onBuilt
     * @returns {null}
     */
    getPage: function (onBuilt) {

        if (!RcmAdminService.page) {

            RcmAdminService.page = new RcmAdminService.RcmPage(
                RcmAdminService.RcmPageModel.getElm(),
                onBuilt
            );
        }
        return RcmAdminService.page
    },

    RcmPageModel: {

        getDocument: function (onComplete) {

            var doc = jQuery(document);

            if (typeof onComplete === 'function') {
                onComplete(doc)
            }

            return doc;
        },

        getElm: function (onComplete) {

            var elm = jQuery('body');

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
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

            return elms;
        },

        getElm: function (containerId, onComplete) {

            var pageElm = RcmAdminService.RcmPageModel.getElm();

            var elm = pageElm.find("[data-containerId='" + containerId + "']");

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        getId: function (containerElm, onComplete) {

            var id = containerElm.attr('data-containerId');

            if (typeof onComplete === 'function') {
                onComplete(id)
            }

            return id;
        },

        getData: function (containerId, onComplete) {

            var data = {};

            var elm = RcmAdminService.RcmContainerModel.getElm(containerId);

            data.id = containerId;

            data.revision = elm.attr('data-containerRevision');

            if (elm.attr('data-isPageContainer') == 'Y') {
                data.type = 'page';
            } else {
                data.type = 'layout';
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

        getElms: function (containerId, onComplete) {

            var containerElm = RcmAdminService.RcmContainerModel.getElm(containerId);

            var elms = containerElm.find('[data-rcmPluginInstanceId]');

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },

        getElm: function (containerId, pluginId, onComplete) {

            var containerElm = RcmAdminService.RcmContainerModel.getElm(containerId);

            var elm = containerElm.find('[data-rcmPluginInstanceId="' + pluginId + '"]');

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        getId: function (pluginElm, onComplete) {

            var id = pluginElm.attr('data-rcmPluginInstanceId');

            if (typeof onComplete === 'function') {
                onComplete(id)
            }

            return id;
        },

        getName: function (pluginElm, onComplete) {

            var name = pluginElm.attr('data-rcmPluginName');

            if (typeof onComplete === 'function') {
                onComplete(name)
            }

            return name;
        },

        getPluginContainer: function (pluginElm, onComplete) {

            var pluginContainerElm = pluginElm.find('.rcmPluginContainer');

            if (typeof onComplete === 'function') {
                onComplete(pluginContainerElm)
            }

            return pluginContainerElm;
        },

        getEditorElms: function (containerId, pluginId, onComplete) {

            var elm = RcmAdminService.RcmPluginModel.getElm(containerId, pluginId);

            var richEditors = elm.find('[data-richEdit]');
            var textEditors = elm.find('[data-textEdit]');

            var elms = {};

            richEditors.each(
                function (index) {
                    this.setAttribute('html-editor-plugin-id', pluginId);
                    elms[this.getAttribute('id')] = this;
                }
            );

            textEditors.each(
                function (index) {
                    this.setAttribute('html-editor-plugin-id', pluginId);
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
    RcmPage: function (elm, onInitted) {

        var self = this;
        self.model = RcmAdminService.RcmPageModel;
        self.containerModel = RcmAdminService.RcmContainerModel;
        self.pluginModel = RcmAdminService.RcmPluginModel;
        //
        self.document = document;
        self.elm = elm;
        //
        self.events = RcmAdminService.RcmEventManager;
        self.editing = []; // page, layout, sitewide
        self.editMode = false;

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

            self.events.trigger('save', {page: self});

            console.log('page.save');
        };

        /**
         * cancel
         */
        self.cancel = function () {

            self.events.trigger('cancel', {page: self});

            window.location = window.location.pathname;
        };

        /**
         * refresh
         */
        self.refresh = function () {

            self.registerObjects(
                function(page){
                    self.events.trigger('refresh', {page: page});
                }
            )
        };

        /**
         * getData
         * @returns {*}
         */
        self.getData = function () {

            return self.model.getData();
        };

        /**
         * registerObjects
         * @param onComplete
         */
        self.registerObjects = function (onComplete) {

            var containerElms = self.containerModel.getElms();

            var containerElm = null;
            var containerId = null;

            var pluginElms = [];
            var pluginElm = null;
            var pluginId = null;

            jQuery.each(
                containerElms,
                function (key, value) {

                    containerElm = jQuery(value);
                    containerId = self.containerModel.getId(containerElm);

                    if (!self.containers[containerId]) {

                        self.containers[containerId] = new RcmAdminService.RcmContainer(self, containerId);
                    }

                    pluginElms = self.pluginModel.getElms(containerId);

                    jQuery.each(
                        pluginElms,
                        function (pkey, pvalue) {

                            pluginElm = jQuery(pvalue);
                            pluginId = self.pluginModel.getId(pluginElm);;

                            if (!self.plugins[pluginId]) {

                                self.plugins[pluginId] = new RcmAdminService.RcmPlugin(self, pluginId);
                            }

                            self.plugins[pluginId].containerId = containerId;

                            self.plugins[pluginId].order = pkey;
                        }
                    );
                }
            );


            if (typeof onComplete === 'function') {
                onComplete(self);
            }
        }


        ///////////////////////////////////////////////////////////
        /**
         * buildData
         * @param onBuilt
         */
        self.buildData = function (onBuilt) {

            self.model.getData();
            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        /**
         * buildContainers
         * @param onBuilt
         */
        self.buildContainers = function (onBuilt) {

            var containers = RcmAdminService.RcmContainerModel.getElms();

            var containerElm = null;
            var containerId = null;

            jQuery.each(
                containers,
                function (key, value) {

                    containerElm = jQuery(value);
                    containerId = RcmAdminService.RcmContainerModel.getId(containerElm);

                    if (!self.containers[containerId]) {

                        self.containers[containerId] = new RcmAdminService.RcmContainer(self, containerId);
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

            self.registerObjects();
        };

        self.init(onInitted);
    },

    /**
     * RcmContainer
     * @param page
     * @param elm
     * @constructor
     */
    RcmContainer: function (page, id) {

        var self = this;

        self.model = RcmAdminService.RcmContainerModel;

        self.page = page;
        self.id = id;
        self.editMode = false;

        self.data = {}

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


        //////////////////////////////////////////////////////

        /**
         * buildData
         * @param onBuilt
         */
        self.buildData = function (onBuilt) {

            self.data = self.model.getData();

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

            var elm = self.model.getElm(self.id);
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
                    pluginId = RcmAdminService.RcmPluginModel.getId(pluginElm);

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
    RcmPlugin: function (page, pluginId, containerId, index) {

        var self = this;

        self.container = container;
        self.elm = elm;
        self.order = index;
        self.editMode = null;
        self.pluginObject = null;

        self.data = {};

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

            var name = RcmAdminService.RcmPluginModel.getName(pluginElm);
            var id = RcmAdminService.RcmPluginModel.getId(pluginElm);
            var pluginContainer = RcmAdminService.RcmPluginModel.getPluginContainer(pluginElm);

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

            RcmAdminService.RcmPluginModel.getEditorElms(containerId, pluginId);

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