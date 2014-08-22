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
            'rcmAdminService',
            function (rcmAdminService) {

                var thisLink = function (scope, elm, attrs) {

                };

                var controller = function ($scope, $element) {

                    $scope.rcmAdminPage = rcmAdminService.getPage();
                };

                return {
                    restrict: 'A',
                    link: thisLink,
                    controller: controller
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
            '$compile',
            'rcmAdminService',
            function ($compile, rcmAdminService) {

                var thisLink = function (scope, elm, attrs) {

                    scope.rcmAdminPage = rcmAdminService.getPage();

                    elm.on('click', null, null, function () {

                        scope.rcmAdminPage.build(
                            function (page) {

                                var editingState = attrs.rcmAdminEditButton;

                                if (!editingState) {
                                    editingState = 'page';
                                }

                                if (editingState == 'cancel') {
                                    scope.rcmAdminPage.cancel();
                                    scope.$apply();
                                    return;
                                }

                                if (editingState == 'save') {
                                    scope.rcmAdminPage.save();
                                    scope.$apply();
                                    return;
                                }

                                scope.rcmAdminPage.setEditingOn(editingState);
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

                var config = {};

                var thisCompile = function (tElem, attrs) {

                    var thisLink = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminPage = rcmAdminService.getPage();

                        if (scope.rcmAdminPage.editMode) {
                            rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                        } else {
                            rcmHtmlEditorDestroy(attrs.id);
                        }

                        scope.$watch(
                            'rcmAdminPage.editing',
                            function (newValue, oldValue) {

                                if (newValue != oldValue) {
                                    if (scope.rcmAdminPage.editing.length > 0) {
                                        rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                    } else {
                                        rcmHtmlEditorDestroy(attrs.id);
                                    }
                                }
                            },
                            true
                        );
                    };
                    return thisLink;
                }

                return {
                    compile: thisCompile,
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

                var config = {};

                var thisCompile = function (tElem, attrs) {

                    var thisLink = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminPage = rcmAdminService.getPage();

                        if (scope.rcmAdminPage.editMode) {
                            rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                        } else {
                            rcmHtmlEditorDestroy(attrs.id);
                        }

                        scope.$watch(
                            'rcmAdminPage.editing',
                            function (newValue, oldValue) {

                                if (newValue != oldValue) {
                                    if (scope.rcmAdminPage.editing.length > 0) {
                                        rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                    } else {
                                        rcmHtmlEditorDestroy(attrs.id);
                                    }
                                }
                            },
                            true
                        );
                    };

                    return thisLink;
                }
                return {
                    compile: thisCompile,
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
            sitewide: "Unlock Site-Wide Plugins?\n\nPlease Note: Any changes you make to a Site-Wide plugin will be published and made live when you save your changes.",
            page: "Unlock Page Plugins?",
            layout: "Unlock Layout Plugins?"
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
            console.log('-get ||||||||||||||||||||||||||||||||||||||');
            RcmAdminService.page = new RcmAdminService.RcmPage(
                document,
                jQuery('body').find('#sitewrapper'),
                onBuilt
            );
        }
        return RcmAdminService.page
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
        self.containerAttr = 'data-containerId';
        self.data = {
            url: '',
            title: '',
            description: '',
            keywords: '',
            pluginData: []
        };

        self.containers = {};

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
        self.save = function () {
            // loop containers and fire saves... aggregate data and sent to server

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

            var containers = self.elm.find("[" + self.containerAttr + "]");

            self.containers = {};

            jQuery.each(
                containers,
                function (key, value) {
                    delete self.containers[key];
                    self.containers[key] = new RcmAdminService.RcmContainer(self, jQuery(value));
                    self.containers[key].build();
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
            console.log('-RcmPage.build ||||||||||||||||||||||||||||||||||||||');
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

    RcmContainer: function (page, elm) {

        var self = this;

        self.page = page;
        self.elm = elm;
        self.editMode = false;

        self.data = {
            id: null,
            revision: null,
            type: null
        }

        self.plugins = [];

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
            console.log(self.data.id+'  -RcmContainer.onEditChange**********');
            self.editMode = self.canEdit(args.editing);

            // @debug - testing
            // if (self.editMode) {
            //    self.elm.prepend('<div style="position: relative; top: 0px; left: 0px; border: #FF0000 solid 1px;">EDITING CONTAINER:'+self.data.type+'</div>');
            // }

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

        /**
         * buildPlugins
         * @param onBuilt
         */
        self.buildPlugins = function (onBuilt) {

            delete self.plugins;
            self.plugins = [];

            var plugins = self.elm.find("[data-rcmpluginname]");

            jQuery.each(
                plugins,
                function (key, value) {

                    delete self.plugins[key];
                    self.plugins[key] = new RcmAdminService.RcmPlugin(self, jQuery(value), key);
                    self.plugins[key].build();
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

        /**
         * disableView
         * @param onDisabled
         */
        self.disableView = function (onDisabled) {

            console.log('RcmPlugin.disableView:' + self.data.name);

            // Add CSS
            self.elm.addClass('rcmPluginLocked');

            // Disable normal events
            self.elm.find('*').unbind();
            var donDoIt = function () {
                return false;
            };
            self.elm.find('button').click(donDoIt);
            self.elm.find('a').click(donDoIt);
            self.elm.find('form').submit(donDoIt);

            // unlock
            var unlock = function () {
                console.log('unlock');
                var r = confirm(RcmAdminService.config.unlockMessages[self.getType()]);
                if (r == true) {
                    self.container.page.setEditingOn(
                        self.getType()
                    );
                }
            }

            // context menu and double click
            self.elm.dblclick(unlock);
            self.elm.click(unlock);

            jQuery.contextMenu(
                {
                    selector: '[data-rcmPluginInstanceId=' + self.data.instanceId + ']',

                    //Here are the right click menu options
                    items: {
                        unlockMe: {
                            name: 'Unlock',
                            icon: 'delete',
                            callback: unlock
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

            console.log('RcmPlugin.enableView:' + self.data.name);

            self.elm.removeClass('rcmPluginLocked');
            self.container.elm.dblclick(function () {
                return false;
            });
            jQuery.contextMenu('destroy', '[data-rcmPluginInstanceId=' + self.data.instanceId + ']');

            // @debug - testing
            //self.elm.prepend('<div style="position: relative; top: 0px; left: 0px; border: #ffff00 solid 1px;">EDITING:'+self.data.name+'</div>');

            if (typeof onEnabled === 'function') {
                onEnabled(self);
            }
        };

        /**
         * onEditChange
         * @param args
         */
        self.onEditChange = function (args) {

            var editMode = self.canEdit(args.editing);

            console.log('.onEditChange+'+self.editMode +'!=='+ editMode);

            //if (self.editMode !== editMode) {

                self.editMode = editMode;

                if (self.editMode) {

                    self.initEdit();

                } else {

                    self.cancelEdit();
                }
            //}
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
         * build
         * @param onBuilt
         */
        self.build = function (onBuilt) {

            self.buildData(
                function (plugin) {
                    if (typeof onBuilt === 'function') {
                        onBuilt(self);
                    }
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
            //console.log('initEdit: no edit js object found for '+name+' - using default for: ' + self.id);
        };

        self.getSaveData = function () {
            //console.log('getSaveData: no edit js object found '+name+' - using default for: ' + self.id);
            return {};
        };
    },

    RcmPluginHtmlEditor: function () {

        var self = this;

        /**
         * @type RcmPlugin
         */
        self.plugin;

    }
};
/* </RcmAdminService> */