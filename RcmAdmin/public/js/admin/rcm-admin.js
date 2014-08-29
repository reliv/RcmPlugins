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
        page.refresh(
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
                    RcmPluginDrag.initDrag();
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

            if (pluginId) {

                var toggleEditors = function () {

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
                            }
                        );
                    } else {
                        rcmHtmlEditorDestroy(
                            attrs.id,
                            function (rcmHtmlEditorService) {
                            }
                        );
                    }
                };

                scope.rcmAdminPage.events.on(
                    'disableLinks:' + pluginId,
                    function (data) {
                        toggleEditors();
                        scope.$apply();

                    }
                );

                //scope.$watch(
                //    'rcmAdminPage.editing',
                //    function (newValue, oldValue) {
                //
                //        if (newValue != oldValue) {
                //
                //            toggleEditors();
                //        }
                //    },
                //    true
                //);
            }
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

            elm = jQuery(elm[0]);

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

        getData: function (containerId, id, onComplete) {

            var data = {};

            var elm = RcmAdminService.RcmPluginModel.getElm(containerId, id);

            data.containerId = containerId;

            data.instanceId = elm.attr('data-rcmPluginInstanceId');

            data.isSitewide = (elm.attr('data-rcmSiteWidePlugin') == '1');
            data.name = elm.attr('data-rcmPluginName');

            data.sitewideName = elm.attr('data-rcmPluginDisplayName');

            var resized = (elm.attr('data-rcmPluginResized') == 'Y');

            if (resized) {
                data.size = elm.width() + ',' + elm.height();
            }

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
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
                    elms[jQuery(this).attr('data-richEdit')] = this;
                }
            );

            textEditors.each(
                function (index) {
                    elms[jQuery(this).attr('data-textEdit')] = this;
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
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

            self.editMode = (self.editing.length > 0);

            self.events.trigger('editingStateChange', {editMode: self.editMode, editing: self.editing});
        };

        /**
         * save
         */
        self.save = function (onSaved) {

            var data = self.getData();
            // loop containers and fire saves... aggregate data and sent to server
            data.plugins = {};

            jQuery.each(
                self.plugins,
                function (key, plugin) {
                    data.plugins[key] = plugin.getSaveData();
                }
            );
            console.log(data);
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
        self.refresh = function (onComplete) {

            self.registerObjects(
                function (page) {
                    self.events.trigger('refresh', {page: page});
                    if (typeof onComplete === 'function') {
                        onComplete(self);
                    }
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
                            pluginId = self.pluginModel.getId(pluginElm);

                            if (!self.plugins[pluginId]) {

                                self.plugins[pluginId] = new RcmAdminService.RcmPlugin(self, pluginId, self.containers[containerId]);
                            }

                            self.plugins[pluginId].container = self.containers[containerId];

                            self.plugins[pluginId].order = pkey;
                        }
                    );
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
        }

        /**
         * init
         * @param onInitted
         */
        self.init = function (onComplete) {

            self.registerObjects(
                function (page) {

                    if (typeof onComplete === 'function') {
                        onComplete(self);
                    }
                }
            );
        };

        self.init(onInitted);
    },

    /**
     * RcmContainer
     * @param page
     * @param elm
     * @constructor
     */
    RcmContainer: function (page, id, onInitted) {

        var self = this;

        self.model = RcmAdminService.RcmContainerModel;

        self.page = page;
        self.id = id;
        self.editMode = false;

        /**
         * getData
         * @returns {*}
         */
        self.getData = function () {

            return self.model.getData(self.id);
        }

        /**
         * canEdit
         * @param editing
         * @returns {boolean}
         */
        self.canEdit = function (editing) {

            return (editing.indexOf(self.getData().type) > -1);
        };

        /**
         * onEditChange
         * @param args
         */
        self.onEditChange = function (args) {

            self.editMode = self.canEdit(args.editing);
        };

        /**
         * init
         */
        self.init = function (onComplete) {

            self.page.events.on('editingStateChange', self.onEditChange);

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
        };

        self.init(onInitted);
    },

    /**
     * RcmPlugin
     * @param page
     * @param id
     * @constructor
     */
    RcmPlugin: function (page, id, container, onInitted) {

        var self = this;

        self.model = RcmAdminService.RcmPluginModel;
        self.containerModel = RcmAdminService.RcmContainerModel;

        self.page = page;
        self.id = id;

        self.container = container;
        self.order = 0;
        self.editMode = null;
        self.pluginObject = null;

        /**
         * getType
         * @returns string
         */
        self.getType = function () {

            if (self.getData().isSitewide) {
                return 'sitewide';
            }

            return self.container.getData().type;
        };

        /**
         * getElm
         * @returns {*}
         */
        self.getElm = function () {

            var elm = self.model.getElm(self.container.id, self.id);

            return elm;
        };

        /**
         * getData
         * @returns {*}
         */
        self.getData = function () {

            var data = self.model.getData(self.container.id, self.id);

            data.rank = self.order;

            return data;
        };

        self.getEditorData = function () {

            var editors = self.getEditorElms();

            var data = {};

            jQuery.each(
                editors,
                function(key, elm){
                    data[key] = jQuery(elm).html();
                }
            );

            return data;
        };

        /**
         * getSaveData
         * @param onSaved
         */
        self.getSaveData = function (onComplete) {

            var data = self.getData();

            var pluginObject = self.getPluginObject();

            if (pluginObject.getSaveData) {

                var saveData = pluginObject.getSaveData();

                // @todo - get html editor data and merge with saveData
                data.saveData = saveData;
            }

            data.editorData = self.getEditorData();

            if (typeof onComplete === 'function') {
                onComplete(self);
            }

            return data;
        };

        /**
         * getPluginObject
         * @returns RcmPluginEditJs
         */
        self.getPluginObject = function () {

            if (self.pluginObject) {

                return self.pluginObject;
            }

            var pluginElm = self.getElm();

            var name = self.model.getName(pluginElm);

            var id = self.model.getId(pluginElm);
            var pluginContainer = self.model.getPluginContainer(pluginElm);

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

        /**
         * getEditorElms
         * @returns {*}
         */
        self.getEditorElms = function () {

            return self.model.getEditorElms(self.container.id, self.id);
        };

        /**
         * prepareEditors
         * @param onComplete
         */
        self.prepareEditors = function (onComplete) {

            var editors = self.getEditorElms();

            jQuery.each(
                editors,
                function (index, value) {
                    value.setAttribute('html-editor-plugin-id', self.id);
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
        };

        /**
         * canEdit
         * @returns boolean
         */
        self.canEdit = function () {

            var editing = self.page.editing;

            var type = self.getType();

            return (editing.indexOf(type) > -1);
        };

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
         * unlock
         */
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
        self.disableView = function (onComplete) {

            var elm = self.getElm();

            // Add CSS
            elm.addClass('rcmPluginLocked');

            // context menu and double click
            elm.dblclick(self.unlock);
            //elm.click(unlock);

            jQuery.contextMenu(
                {
                    selector: '[data-rcmPluginInstanceId=' + self.id + ']',

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

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
            self.disableLinks();
        };

        /**
         * enableView
         * @param onEnabled
         */
        self.enableView = function (onComplete) {

            var elm = self.getElm();

            elm.removeClass('rcmPluginLocked');
            elm.unbind('dblclick');

            jQuery.contextMenu('destroy', '[data-rcmPluginInstanceId=' + self.id + ']');

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
            self.disableLinks();
        };

        /**
         * disableLinks
         */
        self.disableLinks = function (onComplete) {

            var elm = self.getElm();

            // Disable normal events
            elm.find('*').unbind();
            var donDoIt = function () {
                return false;
            };
            elm.find('button').click(donDoIt);
            elm.find('a').click(donDoIt);
            elm.find('form').submit(donDoIt);

            self.page.events.trigger('disableLinks:' + self.id, {plugin: self});

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
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
         * init
         */
        self.init = function (onComplete) {

            self.container.page.events.on('editingStateChange', self.onEditChange);

            self.prepareEditors(
                function (plugin) {
                    if (typeof onComplete === 'function') {
                        onComplete(plugin);
                    }
                }
            );
        };

        self.init(onInitted);
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

rcm.addAngularModule('rcmAdmin');
