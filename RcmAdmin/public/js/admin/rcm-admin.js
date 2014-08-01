///////////////////////////////////////////////

/**
 *
 */
angular.module(
        'rcmAdminDialog',
        []
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
            function ($http, $compile) {

                var BaseStrategy = function ($http, $compile) {

                    var me = this;

                    me.template = "RcmStandardDialogTemplate"

                    me.content = '';

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
                    me.load = function (url, elm, scope, callback) {

                        if (url) {
                            me.loading = true;

                            $http({method: 'GET', url: url}).
                                success(
                                function (data, status, headers, config) {

                                    console.log('SUCCESS');
                                    console.log(data);
                                    console.log(headers);

                                    if (headers['Content-Type'] == 'application/json') {

                                        if (data.redirect !== undefined) {
                                            window.location.replace(data.redirect);
                                        }
                                    } else {

                                        scope.content = data;
                                    }

                                    me.loadCallback(data, elm, scope, callback);
                                }
                            ).
                                error(
                                function (data, status, headers, config) {
                                    scope.content = data;
                                    me.loadCallback(data, elm, scope, callback);

                                }
                            );
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

                        $compile(data)(scope);

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

                var thisLink = function (scope, elm, attrs, ngModel) {

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

                                elm.on(
                                    'shown.bs.modal',
                                    function (event) {

                                        if (strategy.onShow) {
                                            strategy.onShow(scope, elm, event)
                                        }
                                        scope.rcmAdminDialogState.loading = false;
                                    }
                                );
                            }
                        }
                    );
                };

                return {
                    restrict: 'A',
                    link: thisLink,
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
    .factory(
        'rcmEditorCompile',
        [
            function () {

                return '@todo';
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

                                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                } else {
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

                                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                } else {
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