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
    ['rcmApi', 'rcmAdminApi', 'RcmHtmlEditor']
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
 * rcmAdmin.post
 */
    .directive(
    'rcmMenuPost',
    [
        'rcmAdminService',
        function (rcmAdminService) {

            var thisLink = function (scope, elm, attrs) {

                elm.unbind();
                elm.bind(
                    'click', null, function (e) {
                        e.preventDefault();

                        var linkHref = '';

                        if (attrs.publishUrl === undefined) {
                            linkHref = elm.find('a').attr('href');
                        } else {
                            linkHref = attrs.publishUrl;
                        }

                        jQuery('body').append('<form id="stupidPostSubmit" method="post" action="' + linkHref + '">');
                        jQuery('#stupidPostSubmit').submit();

                        /* Ajax request.  Makes publish take Twice as long, and
                         * fails silently when problems arise.  Recommended not
                         * to use, but kept here to settle any disputes.
                         */
//                        jQuery.post(elm.find('a').attr('href'), function(data) {
//                            if (data.redirect != undefined) {
//                                window.location = data.redirect;
//                            }
//                        });
                    }
                );
            };

            return {
                restrict: 'C',
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
        'rcmHtmlEditorService',
        function (rcmAdminService, rcmHtmlEditorService) {

            var eventsRegistered = false;

            var safeApply = function (scope, fn) {
                var phase = scope.$root.$$phase;
                if (phase == '$apply' || phase == '$digest') {
                    if (fn && (typeof(fn) === 'function')) {
                        fn();
                    }
                } else {
                    scope.$apply(fn);
                }
            };

            var getLoading = function (scope) {
                scope.loading = (rcmAdminService.RcmLoading.isLoading() || rcmHtmlEditorService.toolbarLoading);
                safeApply(scope);
            };

            var thisLink = function (scope, elm, attrs) {

                scope.loading = (rcmAdminService.RcmLoading.isLoading() || rcmHtmlEditorService.toolbarLoading);

                if (!eventsRegistered) {

                    rcmAdminService.RcmEventManager.on(
                        'RcmAdminService.RcmLoading.start',
                        function () {
                            getLoading(scope);
                        }
                    );
                    rcmAdminService.RcmEventManager.on(
                        'RcmAdminService.RcmLoading.end',
                        function () {
                            getLoading(scope);
                        }
                    );
                    rcmHtmlEditorService.eventManager.on(
                        'rcmHtmlEditorService.loading.start',
                        function (obj) {
                            getLoading(scope);
                        }
                    );
                    rcmHtmlEditorService.eventManager.on(
                        'rcmHtmlEditorService.loading.end',
                        function (obj) {
                            getLoading(scope);
                        }
                    );

                    eventsRegistered = true;
                }

                scope.rcmAdminPage = rcmAdminService.getPage();

                var editingState = attrs.rcmAdminEditButton;

                elm.unbind();
                elm.bind(
                    'click', null, function () {

                        rcmAdminService.rcmAdminEditButtonAction(
                            editingState,
                            function () {
                                scope.$apply();
                            }
                        );
                    }
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
                compile: rcmAdminService.getHtmlEditorLink(
                    rcmHtmlEditorInit,
                    rcmHtmlEditorDestroy,
                    'richedit'
                ),
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
                compile: rcmAdminService.getHtmlEditorLink(
                    rcmHtmlEditorInit,
                    rcmHtmlEditorDestroy,
                    'textedit'
                ),
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
        saveUrl: '/rcm-admin/page/save-page',
        loadingMessages: {
            _default: {
                title: 'Loading',
                message: 'Please wait...'
            },
            save: {
                message: 'Saving page...'
            }
        },
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

    /**
     * rcmAdminEditButtonAction - Actions for links and AngularJS directives
     * @todo might require $apply
     * @param editingState
     * @param onComplete
     */
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

                    page.arrange(true);

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

    /**
     * angularCompile - only use this if you need to compile after dom change!!!!!
     * @param elm
     * @param fn
     */
    angularCompile: function (elm, fn) {

        var compile = angular.element(elm).injector().get('$compile');

        var scope = angular.element(elm).scope();

        compile(elm.contents())(scope);

        if (scope.$$phase || scope.$root.$$phase) {

            scope.$apply(fn);
        } else {

            if (typeof fn === 'function') {
                fn();
            }
        }
    },
    /**
     * getHtmlEditorLink - creates an angular friendly method
     * @param rcmHtmlEditorInit
     * @param rcmHtmlEditorDestroy
     * @returns {Function}
     */
    getHtmlEditorLink: function (rcmHtmlEditorInit, rcmHtmlEditorDestroy, directiveId) {

        return function (tElem) {

            var page = RcmAdminService.getPage();

            return function (scope, elm, attrs, ngModel) {

                var config = null;

                // global check for extra options, these will merge with the current
                // option presets
                if (typeof RcmThemeConfig == 'object' && typeof RcmThemeConfig.rcmAdminHtmlEditorOptions == 'object') {
                    config = RcmThemeConfig.rcmAdminHtmlEditorOptions;
                }

                scope.rcmAdminPage = page;

                var localId = attrs[directiveId];

                var toggleEditors = function () {

                    var pluginId = elm.attr('html-editor-plugin-id');

                    // if (pluginId)

                    if (!page.plugins[pluginId]) {
                        return;
                    }

                    if (page.plugins[pluginId].canEdit()) {

                        rcmHtmlEditorInit(
                            scope,
                            elm,
                            attrs,
                            ngModel,
                            config
                        );
                    } else {

                        rcmHtmlEditorDestroy(
                            attrs.id
                        );
                    }
                };

                page.events.on(
                    'editingStateChange',
                    toggleEditors
                );


                page.events.on(
                    'updateView',
                    toggleEditors
                );
            }
        }
    },


    /**
     * alertDisplay
     * @param alert
     */
    alertDisplay: function (alert) {

        if (alert.message.status >= 500 && alert.message.status < 600) {
            alert.message.responseText = 'An error occurred during execution; please try again later.'
        }

        if (!alert.message.statusText) {
            'An error occurred'
        }

        $().alert(alert.message.responseText, null, alert.message.statusText);
    },

    /**
     *
     */
    loadingDialog: {
        dialog: null,
        timout: null
    },

    /**
     * loadingDisplay
     * @param loadingData {loading: int, loadingMessage: {title: 'string', message: 'string'}}
     */
    loadingDisplay: function (loadingData) {

        var timout = 250;

        if (RcmAdminService.loadingDialog.timout) {

            clearTimeout(RcmAdminService.loadingDialog.timout);
        }

        if (loadingData.loading) {

            // wait a bit so we dont get flashing message
            RcmAdminService.loadingDialog.timout = setTimeout(
                function () {
                    if (RcmAdminService.loadingDialog.dialog) {

                        RcmAdminService.loadingDialog.dialog.modal('show');
                    } else {
                        RcmAdminService.loadingDialog.dialog = bootbox.dialog(
                            {
                                message: '<div class="modal-body"><p>' + loadingData.loadingMessage.message + '</p></div>',
                                title: '<h1 class="modal-title">' + loadingData.loadingMessage.title + '</h1>',
                                buttons: {}
                            }
                        );
                    }
                },
                timout
            );

        } else {

            if (RcmAdminService.loadingDialog.dialog) {

                RcmAdminService.loadingDialog.dialog.modal('hide');
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

            RcmAdminService.page.events.on(
                'alert',
                RcmAdminService.alertDisplay
            );

            RcmAdminService.page.events.on(
                'loadingStateChange',
                RcmAdminService.loadingDisplay
            );

        } else {
            if (typeof onBuilt === 'function') {
                onBuilt(RcmAdminService.page);
            }
        }

        return RcmAdminService.page
    },

    /**
     * getPlugin
     * @param id
     * @param onComplete
     * @returns {*}
     */
    getPlugin: function (id, onComplete) {

        var page = RcmAdminService.getPage(
            function (page) {
                if (typeof onComplete === 'function') {
                    onComplete(page.getPlugin(id));
                }
            }
        );

        return page.getPlugin(id);
    },

    /**
     * RcmLoading
     */
    RcmLoading: {

        loading: [],
        startLoading: function (namespace, id) {

            if (!namespace || !id) {
                // console.warn('RcmLoading requires unique namespace and id to track loading state'.);
                return;
            }
            if (!RcmAdminService.RcmLoading.loading[namespace]) {

                RcmAdminService.RcmLoading.loading[namespace] = [];
            }

            var firstLoading = false;

            if (RcmAdminService.RcmLoading.loading[namespace].length == 0) {

                firstLoading = true;
            }

            if (RcmAdminService.RcmLoading.loading[namespace].indexOf(id) < 0) {

                RcmAdminService.RcmLoading.loading[namespace].push(id);

                if (firstLoading) {
                    RcmAdminService.RcmEventManager.trigger(
                        'RcmAdminService.RcmLoading.start',
                        {
                            id: id,
                            loading: RcmAdminService.RcmLoading.loading,
                            namespace: namespace
                        }
                    );
                }

            }
        },
        endLoading: function (namespace, id) {

            if (!namespace || !id) {
                // console.warn('RcmLoading requires unique namespace and id to track loading state'.);
                return;
            }

            if (!RcmAdminService.RcmLoading.loading[namespace]) {

                RcmAdminService.RcmLoading.loading[namespace] = [];
            }

            var index = RcmAdminService.RcmLoading.loading[namespace].indexOf(id);

            if (index > -1) {

                RcmAdminService.RcmLoading.loading[namespace].splice(
                    index,
                    1
                );

                if (RcmAdminService.RcmLoading.loading[namespace].length < 1) {

                    RcmAdminService.RcmEventManager.trigger(
                        '.RcmAdminServiceRcmLoading.end',
                        {
                            id: id,
                            loading: RcmAdminService.RcmLoading.loading,
                            namespace: namespace
                        }
                    );
                }
            }
        },
        /**
         *
         * @param namespace
         * @param id
         * @returns {boolean}
         */
        isLoading: function (namespace, id) {

            if (!namespace) {

                for (var i in RcmAdminService.RcmLoading.loading) {
                    if (RcmAdminService.RcmLoading.loading[i] > 0) {
                        return true;
                    }

                    return false;
                }
            }

            if (!RcmAdminService.RcmLoading.loading[namespace]) {
                return false;
            }

            if (!id) {

                return (RcmAdminService.RcmLoading.loading[namespace].indexOf(id) > -1);
            }

            return (RcmAdminService.RcmLoading.loading[namespace].length > 0)
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
        },

        hasEvents: function (event) {

            if (!this.events[event]) {
                return false;
            }

            if (this.events[event].length > 0) {
                return true;
            }

            return false;
        }
    },

    /**
     * RcmPageModel
     */
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

            var pageInfo = JSON.parse(jQuery('meta[property="rcm:page"]').attr('content'));

            var data = {};
            data.title = jQuery(document).find("head > title").text();
            //data.url = jQuery(location).attr('href');
            //data.path = jQuery(location).attr('pathname');
            data.description = jQuery('meta[name="description"]').attr('content');
            data.keywords = jQuery('meta[name="keywords"]').attr('content');

            data.name = pageInfo.rcmPageName;
            data.type = pageInfo.rcmPageType;
            data.revision = pageInfo.rcmPageRevision;
            data.siteId = pageInfo.rcmSiteId;

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    },

    /**
     * RcmContainerModel
     */
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

        getPluginContainerSelector: function (pluginId) {

            return ('[data-rcmPluginInstanceId="' + pluginId + '"] .rcmPluginContainer');
        },

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

        deleteElm: function (containerId, pluginId, onComplete) {

            var elm = RcmAdminService.RcmPluginModel.getElm(containerId, pluginId);

            elm.remove();

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

            data.isSitewide = (elm.attr('data-rcmSiteWidePlugin') == '1' || elm.attr('data-rcmSiteWidePlugin') == 'Y');
            data.name = elm.attr('data-rcmPluginName');

            data.sitewideName = elm.attr('data-rcmPluginDisplayName');

            data.columnClass = elm.attr('data-rcmPluginColumnClass');

            data.rowNumber = elm.attr('data-rcmPluginRowNumber');

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
        },
        getInstanceConfig: function (containerId, pluginId, onComplete) {

            var elm = RcmAdminService.RcmPluginModel.getElm(containerId, pluginId);

            var url = '/api/admin/instance-configs/'
                + RcmAdminService.RcmPluginModel.getName(elm)
                + '/'
                + RcmAdminService.RcmPluginModel.getId(elm);

            //Hide while loading
            elm.hide();

            jQuery.getJSON(
                url,
                function (result) {
                    elm.show();

                    onComplete(result.instanceConfig, result.defaultInstanceConfig);
                }
            );
        }
    },

    RcmPluginViewModel: {

        /**
         * disableEdit
         * @param onComplete
         */
        disableEdit: function (elm, type, onComplete) {

            var id = RcmAdminService.RcmPluginModel.getId(elm);

            var page = RcmAdminService.getPage();

            var unlock = function () {

                jQuery().confirm(
                    RcmAdminService.config.unlockMessages[type].message,
                    function () {
                        page.setEditingOn(type);
                    },
                    null,
                    RcmAdminService.config.unlockMessages[type].title
                );
            };

            // Add CSS
            elm.addClass('rcmPluginLocked');

            // context menu and double click
            elm.dblclick(unlock);
            //elm.click(unlock);

            jQuery.contextMenu(
                {
                    selector: '[data-rcmPluginInstanceId=' + id + ']',

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
            RcmAdminService.RcmPluginViewModel.createEditableButtons(
                elm,
                function (elm) {
                    RcmAdminService.RcmPluginViewModel.disableLinks(elm, onComplete);
                }
            );
        },

        /**
         * enableEdit
         * @param onComplete
         */
        enableEdit: function (elm, onComplete) {

            var id = RcmAdminService.RcmPluginModel.getId(elm);

            elm.removeClass('rcmPluginLocked');
            elm.unbind('dblclick');

            jQuery.contextMenu('destroy', '[data-rcmPluginInstanceId=' + id + ']');

            RcmAdminService.RcmPluginViewModel.createEditableButtons(
                elm,
                function (elm) {
                    RcmAdminService.RcmPluginViewModel.disableLinks(elm, onComplete);
                }
            );
        },

        /**
         * enableEdit
         * @param onComplete
         */
        enableArrange: function (elm, onComplete) {

            var id = RcmAdminService.RcmPluginModel.getId(elm);

            var page = RcmAdminService.getPage();

            var menu = '' +
                '<div id="rcmLayoutEditHelper' + id + '">' +
                '<span class="rcmSortableHandle rcmLayoutEditHelper" title="Move Plugin"></span>' +
                '<span class="rcmContainerMenu rcmLayoutEditHelper" title="Container Menu">' +
                '<ul>' +
                '<li><a href="#"></a><ul>' +
                '<li><a href="#" class="rcmSiteWidePluginMenuItem">Mark as site-wide</a> </li>' +
                '<li><a href="#" class="rcmDeletePluginMenuItem">Delete Plugin</a> </li>' +
                '</ul>' +
                '</span>' +
                '</div>';

            elm.prepend(menu);

            elm.hover(
                function () {
                    jQuery(this).find(".rcmLayoutEditHelper").each(
                        function () {
                            jQuery(this).show();
                        }
                    );
                },
                function () {
                    jQuery(this).find(".rcmLayoutEditHelper").each(
                        function () {
                            jQuery(this).hide();
                        }
                    )
                }
            );
            elm.find(".rcmDeletePluginMenuItem").click(
                function (e) {
                    // me.layoutEditor.deleteConfirm(this);
                    page.removePlugin(id);

                    page.registerObjects();
                    e.preventDefault();
                }
            );

            var makeSiteWide = function (container) {
                var pluginName = $.dialogIn('text', 'Plugin Name', '');
                var form = $('<form></form>')
                    .append(pluginName)
                    .dialog(
                    {
                        title: 'Create Site Wide Plugin',
                        modal: true,
                        width: 620,
                        buttons: {
                            Cancel: function () {
                                $(this).dialog("close");
                            },
                            Ok: {
                                "class": "okButton",
                                text: 'Ok',
                                click: function () {

                                    //Get user-entered data from form
                                    $(container).attr('data-rcmsitewideplugin', 'Y');
                                    $(container).attr(
                                        'data-rcmplugindisplayname',
                                        pluginName.val()
                                    );

                                    $(this).dialog("close");
                                }
                            }
                        }
                    }
                );
            };

            elm.find(".rcmSiteWidePluginMenuItem").click(
                function (e) {
                    makeSiteWide(jQuery(this).parents(".rcmPlugin"));
                    e.preventDefault();
                }
            );

            RcmAdminService.RcmPluginViewModel.enableResize(elm);

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        enableResize: function (elm, onComplete) {

            try {
                elm.resizable('destroy');
            } catch (e) {
                // nothing
            }

            rcmColunmResize.addControls(elm);

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        /**
         * disableArrange
         * @param elm
         * @param onComplete
         */
        disableArrange: function (elm, onComplete) {
            //@todo - remove elements
            var id = RcmAdminService.RcmPluginModel.getId(elm);

            jQuery('[id="rcmLayoutEditHelper' + id + '"]').remove();

            elm.hover(
                function () {
                    return false;
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        /**
         * disableLinks
         */
        disableLinks: function (elm, onComplete) {
            // Disable normal events
            var donDoIt = function () {
                return false;
            };
            elm.find('button').unbind();
            elm.find('[role="button"]').unbind();
            elm.find('button').click(donDoIt);
            elm.find('a').click(donDoIt);
            elm.find('form').submit(donDoIt);
            elm.find('form').unbind();

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },
        /**
         * createEditableButtons
         * @todo This is currently one-way, if an edit is canceled, buttons are not returned to normal
         * @param elm
         * @param onComplete
         */
        createEditableButtons: function (elm, onComplete) {

            elm.find('button').each(
                function (index, element) {

                    var curElement = jQuery(element);
                    var newElm = jQuery('<div role="button"></div>');

                    var curHtml = curElement.html();
                    if (curHtml) {
                        newElm.html(curHtml);
                    }

                    var curClass = curElement.attr('class');
                    if (curClass) {
                        newElm.attr('class', curClass);
                    }

                    var curId = curElement.attr('id');
                    if (curId) {
                        newElm.attr('id', curId);
                    }

                    var curTextEdit = curElement.attr('data-textedit');
                    if (curTextEdit) {
                        newElm.attr('data-textedit', curTextEdit);
                    }

                    curElement.after(newElm);
                    curElement.remove();
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
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
        self.arrangeMode = false;

        self.containers = {};
        self.plugins = {};

        self.loading = 0;
        self.loadingMessage = null;

        /**
         * setLoading
         * @param loading
         * @todo This can possibly use the RcmLoading service
         */
        self.setLoading = function (loading, loadingMessage) {

            if (loading) {

                self.loading++;

                if (!self.loadingMessage) {
                    self.loadingMessage = RcmAdminService.config.loadingMessages._default;
                }

                if (!self.loadingMessage.title) {
                    self.loadingMessage.title = RcmAdminService.config.loadingMessages._default.title;
                }

                if (!self.loadingMessage.message) {
                    self.loadingMessage.message = RcmAdminService.config.loadingMessages._default.message;
                }

            } else {

                if (self.loading > 0) {
                    self.loading--;
                }
            }

            self.events.trigger(
                'loadingStateChange', {
                    loading: self.loading,
                    loadingMessage: self.loadingMessage
                }
            );
        };

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
                );

                self.onEditChange();
            }
        };

        /**
         * onEditChange
         */
        self.onEditChange = function () {

            self.editMode = (self.editing.length > 0);

            self.events.trigger('editingStateChange', self);
        };

        /**
         * arrange
         * @param state
         */
        self.arrange = function (state) {

            if (typeof state === 'undefined') {
                // default is on
                state = true;
            }

            self.arrangeMode = (state === true);

            self.events.trigger('arrangeStateChange', self.arrangeMode);
        };

        /**
         * save
         */
        self.save = function () {

            self.registerObjects(
                function (page) {

                    self.setLoading(
                        true,
                        RcmAdminService.config.loadingMessages.save
                    );
                    var data = self.getData();
                    // loop containers and fire saves... aggregate data and sent to server
                    data.plugins = {};

                    jQuery.each(
                        self.plugins,
                        function (key, plugin) {
                            data.plugins[key] = plugin.getSaveData();
                        }
                    );

                    jQuery.post(
                        RcmAdminService.config.saveUrl + '/' + data.type + '/' + data.name + '/' + data.revision,
                        data,
                        function (msg) {
                            self.setLoading(false);
                            //self.events.trigger('alert', {type:'success',message: 'Page saved'});
                            if (msg.redirect) {
                                window.location = msg.redirect;
                            } else {

                                self.events.trigger(
                                    'alert', {
                                        type: 'warning',
                                        message: msg
                                    }
                                );
                            }

                        },
                        'json'
                    ).fail(
                        function (msg) {
                            self.setLoading(false);
                            self.events.trigger(
                                'alert', {
                                    type: 'warning',
                                    message: msg
                                }
                            );
                        }
                    );
                }
            );
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
         * getPlugin
         * @param pluginId
         * @returns {*}
         */
        self.getPlugin = function (pluginId) {
            if (self.plugins[pluginId]) {
                return self.plugins[pluginId];
            }

            return null;
        };

        /**
         * addPlugin
         * @param containerId
         * @param pluginId
         * @param order
         */
        self.addPlugin = function (containerId, pluginId, order) {

            if (!self.plugins[pluginId]) {

                self.plugins[pluginId] = new RcmAdminService.RcmPlugin(
                    self,
                    pluginId,
                    self.containers[containerId]
                );

                self.plugins[pluginId].init();
            }

            self.plugins[pluginId].container = self.containers[containerId];

            self.plugins[pluginId].order = order;

            self.events.trigger('addPlugin', pluginId);

            return self.plugins[pluginId];
        };

        /**
         * removePlugin
         * @param containerId
         * @param pluginId
         * @param order
         */
        self.removePlugin = function (pluginId) {

            if (self.plugins[pluginId]) {

                self.plugins[pluginId].remove(
                    function (plugin) {
                        delete(self.plugins[pluginId]);
                        self.events.trigger('removePlugin', pluginId);
                    }
                );
            }
        };

        /**
         * registerObjects
         * - Update object list based on DOM state
         * - should be called after DOM update
         * @param onComplete
         */
        self.registerObjects = function (onComplete) {

            var containerElms = self.containerModel.getElms();

            var containerElm = null;
            var containerId = null;

            var pluginsRemove = [];
            var pluginElms = [];
            var pluginElm = null;
            var pluginId = null;

            jQuery.each(
                containerElms,
                function (key, value) {

                    containerElm = jQuery(value);
                    containerId = self.containerModel.getId(containerElm);

                    if (!self.containers[containerId]) {

                        self.containers[containerId] = new RcmAdminService.RcmContainer(
                            self,
                            containerId
                        );
                    }

                    pluginElms = self.pluginModel.getElms(containerId);

                    jQuery.each(
                        pluginElms,
                        function (pkey, pvalue) {

                            pluginElm = jQuery(pvalue);
                            pluginId = self.pluginModel.getId(pluginElm);

                            self.addPlugin(containerId, pluginId, pkey);

                            pluginsRemove.push(pluginId);
                        }
                    );
                }
            );

            // remove if no longer in DOM
            jQuery.each(
                self.plugins,
                function (prkey, prvalue) {
                    if (pluginsRemove.indexOf(prvalue.id) < 0) {
                        self.removePlugin(prvalue.id);
                    }
                }
            );

            self.events.trigger('registerObjects', self.plugins);

            if (typeof onComplete === 'function') {
                onComplete(self);
            }
        };


        /**
         * init
         * @param onComplete
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
        };

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
     * RcmPlugin - AKA pluginHandler
     * @param page
     * @param id
     * @param container
     * @constructor
     */
    RcmPlugin: function (page, id, container) {

        var self = this;

        self.model = RcmAdminService.RcmPluginModel;
        self.viewModel = RcmAdminService.RcmPluginViewModel;
        self.containerModel = RcmAdminService.RcmContainerModel;

        self.page = page;
        self.id = id;

        self.container = container;
        self.order = 0;
        self.editMode = null;
        self.pluginObject = null;
        self.isInitted = false;

        self.instanceConfig = null;
        self.defaultInstanceConfig = null;

        /**
         * Asks the plugin edit controller for its instance config, then has
         * the server re-render the plugin. This is useful for previewing
         * changes to plugins without having to save the page.
         */
        self.preview = function (callback) {
            var pluginElm = self.getElm();

            var name = self.model.getName(pluginElm);
            var pluginContainer = self.model.getPluginContainer(pluginElm);
            self.instanceConfig = self.getSaveData().saveData;
            $.post(
                '/rcm-admin-get-instance/' + name + '/' + id,
                {
                    previewInstanceConfig: self.instanceConfig
                },
                function (data) {
                    pluginContainer.html(data);
                    self.updateView(
                        pluginContainer, function () {
                            self.isInitted = false;
                            self.initEdit(callback);
                        }
                    );
                }
            );
        };

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
         * @returns {elm}
         */
        self.getElm = function () {

            var elm = self.model.getElm(self.container.id, self.id);

            return elm;
        };

        /**
         * getId
         * @returns {*}
         */
        self.getId = function () {

            return self.id;
        };

        /**
         * getName
         * @returns {*|string}
         */
        self.getName = function () {

            var pluginElm = self.getElm();

            return self.model.getName(pluginElm);
        };

        /**
         * getInstanceConfig
         * @param onComplete
         */
        self.getInstanceConfig = function (onComplete) {
            if (self.instanceConfig && self.defaultInstanceConfig) {
                //This path needed for preview to work
                if (typeof onComplete === 'function') {
                    onComplete(self.instanceConfig, self.defaultInstanceConfig);
                }
            } else {
                self.model.getInstanceConfig(
                    self.container.id,
                    self.id,
                    function (instanceConfig, defaultInstanceConfig) {

                        self.instanceConfig = instanceConfig;
                        self.defaultInstanceConfig = defaultInstanceConfig;

                        if (typeof onComplete === 'function') {
                            onComplete(instanceConfig, defaultInstanceConfig);
                        }
                    }
                );
            }
        };

        /**
         * getData
         * @returns {*}
         */
        self.getData = function () {

            var data = self.model.getData(self.container.id, self.id);

            data.rank = self.order;

            data.containerType = self.container.getData().type;

            return data;
        };

        /**
         * getEditorData
         * @returns {{}}
         */
        self.getEditorData = function () {

            var editors = self.getEditorElms();

            var data = {};

            jQuery.each(
                editors,
                function (key, elm) {
                    data[key] = jQuery(elm).html();
                }
            );

            return data;
        };

        /**
         * getSaveData
         * @param onComplete
         */
        self.getSaveData = function (onComplete) {

            var data = self.getData();

            var pluginObject = self.getPluginObject();

            data.saveData = {};

            if (pluginObject.getSaveData) {

                var saveData = pluginObject.getSaveData();

                jQuery.extend(data.saveData, saveData);
            }

            var editorData = self.getEditorData();

            jQuery.extend(data.saveData, editorData);

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
                    self.pluginObject = new editClass(id, pluginContainer, self);
                    return self.pluginObject;
                }
            }

            self.pluginObject = new RcmAdminService.RcmPluginEditJs(
                id,
                pluginContainer,
                self
            );

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
         * startLoading
         * @param id
         */
        self.startLoading = function (id) {

            RcmAdminService.RcmLoading.startLoading('RcmPlugin.' + self.id, id);

        };

        /**
         * endLoading
         * @param id
         */
        self.endLoading = function (id) {

            RcmAdminService.RcmLoading.endLoading('RcmPlugin.' + self.id, id);
        };

        /**
         * endLoading
         * @param id
         */
        self.isLoading = function (id) {

            RcmAdminService.RcmLoading.isLoading('RcmPlugin.' + self.id, id);
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

            self.page.events.trigger('prepareEditors', self);

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
         * remove
         * @param onComplete
         */
        self.remove = function (onComplete) {
            self.viewModel.disableArrange(
                self.getElm(),
                function () {
                    self.model.deleteElm(self.container.id, self.id);

                    if (typeof onComplete === 'function') {
                        onComplete(self);
                    }
                }
            );
        };

        /**
         * initEdit
         * @param onInitted
         */
        self.initEdit = function (onInitted) {

            self.viewModel.enableEdit(
                self.getElm(),
                function (elm) {
                    if (!self.isInitted) {

                        var pluginObject = self.getPluginObject();

                        if (pluginObject.initEdit) {
                            pluginObject.initEdit();
                        }

                        self.pluginReady();

                        self.isInitted = true;
                        if (typeof onInitted === 'function') {
                            onInitted(self);
                        }
                    }
                }
            );
        };

        /**
         * cancelEdit
         * @param onCanceled
         */
        self.cancelEdit = function (onCanceled) {

            self.viewModel.disableEdit(
                self.getElm(),
                self.getType(),
                function (elm) {
                    if (typeof onCanceled === 'function') {
                        onCanceled(self);
                    }
                }
            );
        };

        /**
         * updateView - ONLY use this if needed - will cause issues with ng-repeat and possibly other
         * @param elm
         * @param onComplete
         */
        self.updateView = function (elm, onComplete) {

            self.prepareEditors(
                function (plugin) {

                    if (!elm) {
                        elm = plugin.getElm()
                    }

                    RcmAdminService.angularCompile(
                        elm,
                        function () {
                            self.page.events.trigger('updateView', plugin);
                        }
                    );

                    if (typeof onComplete === 'function') {
                        onComplete(plugin);
                    }
                }
            );
        };

        /**
         * pluginReady - trigger post plugin ready actions/ DOM parsing
         */
        self.pluginReady = function (onComplete) {
            self.prepareEditors(
                function (plugin) {

                    self.page.events.trigger('pluginReady', plugin);

                    if (typeof onComplete === 'function') {
                        onComplete(plugin);
                    }
                }
            );

        };

        /**
         * onEditChange
         * @param page
         */
        self.onEditChange = function (page) {

            var editMode = self.canEdit(page.editing);

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
         * onArrangeStateChange
         * @param state
         */
        self.onArrangeStateChange = function (state) {

            if (state) {
                self.viewModel.enableArrange(
                    self.getElm()
                );
            } else {
                self.viewModel.disableArrange(
                    self.getElm()
                );
            }
        };

        /**
         * onInitComplete
         */
        self.onInitComplete = function (onComplete) {

            // initial state
            if (self.canEdit(self.page.editing)) {

                self.initEdit();
            }

            self.onArrangeStateChange(self.page.arrangeMode);

            if (typeof onComplete === 'function') {
                onComplete(plugin);
            }
        };

        /**
         * init
         */
        self.init = function (onComplete) {

            self.page.events.on('editingStateChange', self.onEditChange);

            self.page.events.on('arrangeStateChange', self.onArrangeStateChange);

            self.prepareEditors(
                function (plugin) {

                    self.onInitComplete(onComplete);
                }
            );
        };
    },

    /**
     * Default Edit JS - does nothing - interface
     * @param id
     * @param pluginContainer
     * @param pluginHandler
     * @constructor
     */
    RcmPluginEditJs: function (id, pluginContainer, pluginHandler) {

        var self = this;
        self.id = id;
        //self.pluginContainer = pluginContainer;

        self.initEdit = function () {
            //console.warn('initEdit: no edit js object found for '+pluginHandler.getName()+' - using default for: ' + self.id);
        };

        self.getSaveData = function () {
            //console.warn('getSaveData: no edit js object found '+pluginHandler.getName()+' - using default for: ' + self.id);
            return {};
        };
    }
};
/* </RcmAdminService> */

rcm.addAngularModule('rcmAdmin');
