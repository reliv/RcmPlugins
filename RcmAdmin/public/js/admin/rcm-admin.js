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

    page: null,
    config: {

    },

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

    getPage: function (onBuilt) {

        if (!RcmAdminService.page) {

            RcmAdminService.page = new RcmAdminService.RcmPage(document, jQuery('body').find('#sitewrapper'));

            RcmAdminService.page.build(onBuilt);
        }
        return RcmAdminService.page
    },

    RcmPage: function (document, elm) {

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

        self.setEditingOn = function (type) {

            if (self.editing.indexOf(type) < 0) {
                self.editing.push(type);
            }

            return self.isEditing();
        };

        self.setEditingOff = function (type) {

            if (self.editing.indexOf(type) > -1) {

                self.editing.splice(
                    self.editing.indexOf(type),
                    1
                )
            }

            return self.isEditing();
        };

        self.isEditing = function () {

            self.editMode = (self.editing.length > 0)

            self.events.trigger('editingStateChange', {editMode: self.editMode, editing: self.editing});

            console.log('EditMode change:',self.editing);
            return self.editMode;
        };

        self.save = function () {
            // loop containers and fire saves... aggregate data and sent to server

        };

        self.cancel = function () {

            window.location = window.location.pathname;
        };

        self.buildData = function (onBuilt) {

            self.data.title = jQuery(document).find("head > title").text();
            self.data.url = jQuery(location).attr('href');
            self.data.description = jQuery('meta[name="description"]').attr('content');
            self.data.keywords = jQuery('meta[name="keywords"]').attr('content');

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        self.buildContainers = function (onBuilt) {

            var containers = self.elm.find("[" + self.containerAttr + "]");

            self.containers = {};

            jQuery.each(
                containers,
                function (key, value) {
                    var tempContainer = new RcmAdminService.RcmContainer(self, jQuery(value));
                    tempContainer.build(
                        function (container) {
                            self.containers[key] = container;
                        }
                    );
                }
            );

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        self.build = function (onBuilt) {
            self.buildData(
                function (rcmPage) {
                    self.buildContainers(onBuilt);
                }
            );
        };
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

        self.save = function (onSaved) {
            // loop plugins and fire saves...

            if (typeof onSaved === 'function') {
                onSaved(self);
            }
        };

        self.canEdit = function (editing) {

            return (editing.indexOf(self.data.type) > -1);
        };

        self.onEditChange = function (args) {

            self.editMode = self.canEdit(args.editing);

            // @debug - testing
            // if (self.editMode) {
            //    self.elm.prepend('<div style="position: relative; top: 0px; left: 0px; border: #FF0000 solid 1px;">EDITING CONTAINER:'+self.data.type+'</div>');
            // }

        };

        self.onBuilt = function () {

            self.page.events.on('editingStateChange', self.onEditChange);
        };

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

        self.buildPlugins = function (onBuilt) {

            // @todo will this require more garbage collection?
            delete self.plugins;
            self.plugins = [];

            var plugins = self.elm.find("[data-rcmpluginname]");

            jQuery.each(
                plugins,
                function (key, value) {
                    var tempPlugin = new RcmAdminService.RcmPlugin(self, jQuery(value), key);
                    tempPlugin.build(
                        function (plugin) {
                            self.plugins[key] = plugin;
                        }
                    );
                }
            );

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        self.build = function (onBuilt) {

            self.buildData(
                function (rcmContainer) {
                    self.buildPlugins(
                        function (container) {

                            if (typeof onBuilt === 'function') {
                                onBuilt(self);
                            }

                            self.onBuilt();
                        }
                    );
                }
            );
        };
    },

    /**
     *
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
        //self.editMode = false;
        self.viewEnabled = true;

        /* Good browsers */

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

        self.setOrginalStyle = function (style) {
            if (typeof style !== 'string') {
                style = '';
            }

            self.orginalStyle = style;
        };

        self.getType = function(){

            if (self.data.isSitewide) {
                return 'sitewide';
            }

            return self.container.data.type;
        }

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

        self.cancelEdit = function (onCanceled) {

            if (typeof onCanceled === 'function') {
                onCanceled(self);
            }
        };

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

        self.canEdit = function () {

            var editing = self.container.page.editing;

            var type = self.getType();

            return (editing.indexOf(type) > -1);
        };

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

        self.disableView = function (onDisabled) {

            if (!self.viewEnabled) {

                if (typeof onDisabled === 'function') {
                    onDisabled(self);
                }

                return;
            }

            self.elm.addClass('rcmPluginLocked');

            //Disable normal events
            self.elm.find('*').unbind();
            var donDoIt = function () {
                return false;
            };
            self.elm.find('button').click(donDoIt);
            self.elm.find('a').click(donDoIt);
            self.elm.find('form').submit(donDoIt);

            var unlock = function () {
                console.log('unlock');
                self.container.page.setEditingOn(
                    self.getType()
                );
            }

            self.elm.dblclick(unlock);
            jQuery.contextMenu(
                {
                    selector: '.rcmLockOverlay',

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

            self.viewEnabled = false;

            if (typeof onDisabled === 'function') {
                onDisabled(self);
            }
        };

        self.enableView = function (onEnabled) {

            self.elm.removeClass('rcmPluginLocked');
            self.container.elm.dblclick(function () {
                return false;
            });

            // @debug - testing
            //self.elm.prepend('<div style="position: relative; top: 0px; left: 0px; border: #ffff00 solid 1px;">EDITING:'+self.data.name+'</div>');

            if (typeof onEnabled === 'function') {
                onEnabled(self);
            }
        };

        self.onEditChange = function (args) {

            self.editMode = self.canEdit(args.editing);

            self.disableView(
                function (plugin) {
                    self.initEdit();
                }
            );
        };

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

            self.container.page.events.on('editingStateChange', self.onEditChange);

            if (typeof onBuilt === 'function') {
                onBuilt(self);
            }
        };

        self.build = function (onBuilt) {

            self.buildData(
                function (plugin) {
                    if (typeof onBuilt === 'function') {
                        onBuilt(self);
                    }
                }
            );
        };
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