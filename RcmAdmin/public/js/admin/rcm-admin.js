///////////////////////////////////////////////

/**
 *
 */
angular.module(
        'rcmAdminDialog',
        ['ngSanitize']
    )
    .factory(
        'rcmAdminDialogState',
        [
            function () {

                var State = function () {

                    var self = this;
                    self.url = '';
                    self.title = '';
                    self.loading = false;
                    self.strategy;
                }

                var state = new State();

                return state;
            }
        ]
    )
    .factory(
        'rcmAdminDialogBaseStrategy',
        [
            '$http',
            '$compile',
            '$sce',
            function ($http, $compile, $sce) {

                var BaseStrategy = function ($http, $compile) {

                    var me = this;

                    me.template = "RcmStandardDialogTemplate"

                    me.content = '';

                    me.error = [];

                    me.data = {};

                    me.loading = false;
                    /**
                     *
                     * @param scope
                     * @param elm
                     * @param event
                     */
                    me.onShow = function (scope, elm, event) {

                    }

                    /**
                     *
                     * @param url
                     * @param elm
                     * @param scope
                     * @param callback
                     */
                    me.load = function (url, elm, scope, ctrl, callback) {

                        if (url) {
                            me.loading = true;
                            var contentBody = elm.find(".modal-body");
                            contentBody.load(url, null, function (response, status, xhr) {

                                if (status == "error") {
                                    jQuery(contentBody).html(xhr.responseText);
                                }

                                var contentType = xhr.getResponseHeader('Content-Type');

                                if (contentType.indexOf('application/json') > -1) {
                                    var jsonResponse = jQuery.parseJSON(xhr.responseText);

                                    if (jsonResponse.redirect !== undefined) {
                                        window.location.replace(jsonResponse.redirect);
                                    }
                                }

                                me.loadCallback(response, elm, scope, callback);
                            });
                            /*
                             $http.get(url, {}).success(function(response) {
                             console.log('SUCCESS');
                             //scope.content = response;
                             scope.content = $sce.trustAsHtml(response);
                             //var html = $sce.trustAsHtml(response);
                             //var element = $compile(html)(scope);
                             //console.log(element.html());
                             //
                             //scope.content = element.html();
                             //    //scope.content = angular.element(response).html();
                             ////scope.content = $compile(r)(scope);
                             //scope.$apply(function() {
                             //    scope.content = r;
                             //});
                             }).error(function(response) {

                             });
                             $http({method: 'GET', url: url}).
                             success(
                             function (data, status, headers, config) {

                             console.log('SUCCESS');
                             console.log(headers['Content-Type']);

                             // @todo - rules inject for non standard return?
                             if (headers['Content-Type'] == 'application/json') {

                             if (data.redirect !== undefined) {
                             window.location.replace(data.redirect);
                             }
                             } else {
                             scope.content = $sce.trustAsHtml(data);
                             }

                             me.loadCallback(data, elm, scope, callback);
                             }
                             ).
                             error(
                             function (data, status, headers, config) {
                             // @todo - rules inject for non 200 status?
                             scope.content = data;
                             me.loadCallback(data, elm, scope, callback);
                             }
                             );
                             */
                        }
                    };

                    /**
                     *
                     * @param data
                     * @param elm
                     * @param scope
                     * @param callback
                     */
                    me.loadCallback = function (data, elm, scope, callback) {

                        jQuery('.modal-dialog').draggable({handle: '.modal-header'});

                        if (callback) {
                            callback(data);
                        }

                        me.loading = false;
                    };
                };

                var baseStrategy = new BaseStrategy($http, $compile);

                return baseStrategy;
            }
        ]
    )

    .factory(
        'rcmAdminDialogBlankStrategy',
        [
            'rcmAdminDialogBaseStrategy',
            function (rcmAdminDialogBaseStrategy) {

                // extend BaseStrategy

                var rcmAdminDialogBlankStrategy = rcmAdminDialogBaseStrategy;

                rcmAdminDialogBlankStrategy.template = "RcmStandardDialogTemplate"
                rcmAdminDialogBlankStrategy.load = function (url, elm, scope, callback) {

                    me.loadCallback(null, elm, scope, callback);

                }

                return rcmAdminDialogBlankStrategy;
            }
        ]
    )
    .factory(
        'rcmAdminStrategyFactory',
        [
            '$injector',
            'rcmAdminDialogState',
            'rcmAdminDialogBaseStrategy',
            function ($injector, rcmAdminDialogState, rcmAdminDialogBaseStrategy) {

                var self = this;

                self.getStrategy = function (id) {

                    $injector = angular.injector();

                    var strategy = {};

                    if ($injector.has(id)) {

                        return $injector.get(id);
                    }

                    return rcmAdminDialogBaseStrategy;
                }

                return self;
            }
        ]
    )
    .directive(
        'rcmAdminDialog',
        [
            '$log',
            'rcmAdminDialogState',
            'rcmAdminStrategyFactory',
            function ($log, rcmAdminDialogState, rcmAdminStrategyFactory) {

                var thisLink = function (scope, elm, attrs, ctrl) {

                    self = this;

                    scope.rcmAdminDialogState = rcmAdminDialogState;
                    scope.template = '';

                    scope.$watch(
                        'rcmAdminDialogState.loading',
                        function (newValue, oldValue) {

                            if (newValue) {

                                var strategy = rcmAdminStrategyFactory.getStrategy(
                                    rcmAdminDialogState.strategy
                                );

                                scope.template = strategy.template;

                                elm.modal('show');

                                strategy.load(rcmAdminDialogState.url, elm, scope, ctrl);

                                elm.on(
                                    'shown.bs.modal',
                                    function (event) {
                                        console.log('shown.bs.modal');
                                        if (strategy.onShow) {
                                            strategy.onShow(scope, elm, event)
                                        }

                                    }
                                );
                                scope.rcmAdminDialogState.loading = false;
                            }
                        }
                    );
                };

                return {
                    restrict: 'A',
                    link: thisLink,
                    controller: angular.noop,
                    template: '<div ng-include="template">TEMPLATE</div>'
                }
            }
        ]
    );
/**
 * rcmAdminMenu
 */
angular.module(
        'rcmAdminMenu',
        ['rcmAdminDialog']
    )

    .directive(
        'RcmAdminMenu',
        [
            '$log',
            'rcmAdminDialogState',
            function ($log, rcmAdminDialogState) {

                var thisLink = function (scope, elm, attrs) {

                    var htlmLink = elm.find("a");

                    htlmLink.on('click', null, null, function (event) {

                        event.preventDefault();

                        scope.$apply(
                            function () {
                                rcmAdminDialogState.url = htlmLink.attr('href');
                                rcmAdminDialogState.title = htlmLink.attr('title');
                                rcmAdminDialogState.strategy = elm[0].classList[1];
                                rcmAdminDialogState.loading = true;
                            }
                        );
                    });

                };

                return {
                    restrict: 'C',
                    link: thisLink
                }
            }

        ]
    );

/**
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
 * rcmAdminState
 */
    .factory(
        'rcmAdminState',
        [
            function () {

                var State = function () {

                    var self = this;
                    self.editMode = false;
                    self.editModeSite = false;
                }

                var state = new State();

                return state;
            }
        ]
    )
    .directive(
        'rcmAdminEditButton',
        [
            '$compile',
            'rcmAdminState',
            function ($compile, rcmAdminState) {

                var thisLink = function (scope, elm, attrs) {

                    elm.on('click', null, null, function () {
                        scope.$apply(
                            function () {
                                rcmAdminState.editMode = !rcmAdminState.editMode;
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
    .directive(
        'rcmpluginname',
        [
            '$compile',
            'rcmAdminState',
            function ($compile, rcmAdminState) {

                var thisCompile = function (tElem, attrs) {

                    var link = function (scope, elm, attrs, ngModel) {
                        scope.rcmAdminState = rcmAdminState;
                    };

                    return link
                }

                return {
                    restrict: 'A',
                    compile: thisCompile
                }
            }

        ]
    )

    .directive(
        'richedit',
        [
            'rcmAdminState',
            'rcmHtmlEditorInit',
            'rcmHtmlEditorDestroy',
            function (rcmAdminState, rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

                var config = {};

                var thisCompile = function (tElem, attrs) {

                    attrs.$set('richedit', '{{rcmAdminState.editMode}}');

                    var thisLink = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminState = rcmAdminState;

                        scope.$watch(
                            'rcmAdminState.editMode',
                            function (newValue, oldValue) {

                                if (newValue) {
                                    // @todo disable click and other content events
                                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                } else {
                                    // @todo enable click and other content events + prepare
                                    rcmHtmlEditorDestroy(attrs.id);
                                }
                            }
                        );
                    };
                    return thisLink;
                }

                return {
                    compile: thisCompile,
                    priority: 10,
                    restrict: 'A',
                    require: '?ngModel'
                }
            }
        ]
    )
    .directive(
        'textedit',
        [
            'rcmAdminState',
            'rcmHtmlEditorInit',
            'rcmHtmlEditorDestroy',
            function (rcmAdminState, rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

                var config = {};

                var thisCompile = function (tElem, attrs) {

                    attrs.$set('textedit', '{{rcmAdminState.editMode}}');

                    var thisLink = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminState = rcmAdminState;

                        attrs.$set('htmlEditorType', 'text');

                        scope.$watch(
                            'rcmAdminState.editMode',
                            function (newValue, oldValue) {

                                if (newValue) {
                                    // @todo disable click and other content events
                                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                } else {
                                    // @todo enable click and other content events + prepare
                                    rcmHtmlEditorDestroy(attrs.id);
                                }
                            }
                        );
                    };
                    return thisLink;
                }
                return {
                    compile: thisCompile,
                    priority: 10,
                    restrict: 'A',
                    require: '?ngModel'
                }
            }
        ]
    );


rcm.addAngularModule('rcmAdmin');
rcm.addAngularModule('rcmAdminMenu');
/*
 var RcmLayout = function () {

 var self = this;

 /**
 * Layout type
 * page, layout
 * @type {string}
 /
 self.type = 'page';
 }

 var RcmContainer = function () {

 var self = this;

 /**
 * @type RcmLayout
 /
 self.layout;
 }


 var RcmPlugin = function () {

 var self = this;

 /**
 * @type RcmContainer
 /
 self.container;

 self.data = {};

 self.state = {

 editMode: false
 }


 self.init = function (scope, elm, attrs, ngModel) {

 // populate data from attributes
 }

 self.lockPlugin = function () {

 }

 self.unlockPlugin = function () {

 }

 self.getContainerData = function () {

 }
 }

 var RcmPluginHtmlEditor = function () {

 var self = this;

 /**
 * @type RcmPlugin
 /
 self.plugin;

 }
 */