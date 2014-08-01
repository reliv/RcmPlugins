///////////////////////////////////////////////

/**
 * <rcmDialog>
 */
angular.module(
        'rcmDialog',
        ['ngSanitize']
    )
    .factory(
        'rcmDialogState',
        [
            function () {

                var State = function () {

                    var self = this;
                    self.loading = false;
                    self.open = false;
                    self.strategy = {
                        loading: true,
                        name: '',
                        title: '',
                        url: ''
                    };
                }

                var state = new State();

                return state;
            }
        ]
    )
    .directive(
        'rcmDialog',
        [
            '$compile',
            'rcmDialogState',
            function ($compile, rcmDialogState) {

                var thisCompile = function (tElement, tAttrs) {

                    console.log('rcmDialog COMPILEPREP');

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        console.log('rcmDialog LINKPREP');
                        self = this;

                        scope.rcmDialogState = rcmDialogState;
                        var strategyName = rcmDialogState.strategy.name;

                        scope.directive = strategyName;
                        console.log(strategyName);
                        if(strategyName){
                            var directiveName = strategyName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();

                            elm.find(':first-child').attr(directiveName, 'rcmDialogState');

                            console.log(elm.find(':first-child'));
                        }

                        scope.$watch(
                            'rcmDialogState.open',
                            function (newValue, oldValue) {

                                if (newValue) {
                                    console.log('change');

                                    rcmDialogState.open = false;

                                    $compile(elm)(scope);
                                    $compile(elm.contents())(scope);

//                                    elm.modal('show');
//
//                                    elm.on(
//                                        'shown.bs.modal',
//                                        function (event) {
//                                            // @todo
//                                        }
//                                    );
                                }
                            }
                        );
                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    controller: angular.noop,
                    template: '<div>-{{directive}}-</div>'
                }
            }
        ]
    )
    .directive(
        'rcmBlankDialog',
        [
            '$compile',
            'rcmDialogState',
            function ($compile, rcmDialogState) {

                var thisCompile = function (tElement, tAttrs, transclude) {

                    console.log('rcmBlankDialog COMPILEPREP');

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        console.log('rcmBlankDialog LINKPREP')

                        scope.rcmDialogState = rcmDialogState;
                        scope.template = rcmDialogState.url;
                        scope.loading = false;

                        scope.$on('$destroy', function () {
                            console.log('DESTROY');
                            scope.rcmDialogState.open = false;
                        });

                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    template: '' +
                        '<div XXXng-hide="loading" >' +
                        '    <div XXXng-include="template">BLANK</div>' +
                        '</div>'
                }
            }
        ]
    )
    //
    //
    //
    /* @deprecated */
    .factory(
        'RcmStandardDialogStrategy',
        [
            '$http',
            '$compile',
            '$sce',
            function ($http, $compile, $sce) {

                /**
                 var XXXBaseStrategy = function ($http, $compile) {

                    var self = this;
                    self.params = {
                        url: '',
                        title: '',
                        loading: false
                    }

                    self.template = "RcmStandardDialogTemplate"

                    self.getTemplate = function () {

                        return self.template;
                    };

                    self.setParams = function (params) {

                        self.params = params;
                    };


                    var self = this;


                    self.content = '';

                    self.error = [];

                    self.data = {};

                    self.loading = false;


                    self.onShow = function (scope, elm, event) {

                    }

                    self.load = function (url, elm, scope, ctrl, callback) {

                        if (url) {
                            self.loading = true;

                            scope.content = url;


                            /* jQuery
                             var contentBody = elm.find(".modal-body");
                             console.log('LOAD1');
                             jQuery(contentBody).load(url, {}, function (response, status, xhr) {

                             if (status == "error") {
                             jQuery(contentBody).html(xhr.responseText);
                             }
                             console.log(response);
                             var contentType = xhr.getResponseHeader('Content-Type');

                             if (contentType.indexOf('application/json') > -1) {
                             var jsonResponse = jQuery.parseJSON(xhr.responseText);

                             if (jsonResponse.redirect !== undefined) {
                             window.location.replace(jsonResponse.redirect);
                             }
                             }

                             self.loadCallback(response, elm, scope, callback);
                             });
                             */

                /* angular get
                 $http.get(url, {}).success(function (response) {
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
                 }).error(function (response) {

                 });
                 */
                /*
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

                 self.loadCallback(data, elm, scope, callback);
                 }
                 ).
                 error(
                 function (data, status, headers, config) {
                 // @todo - rules inject for non 200 status?
                 scope.content = data;
                 self.loadCallback(data, elm, scope, callback);
                 }
                 );
                 */
                /*
                 }
                 };

                 self.loadCallback = function (data, elm, scope, callback) {

                 jQuery('.modal-dialog').draggable({handle: '.modal-header'});

                 if (typeof callback === 'function') {
                 callback(data);
                 }

                 self.loading = false;
                 };
                 };

                 var baseStrategy = new BaseStrategy($http, $compile);

                 return baseStrategy;
                 */
            }
        ]
    )

    /* @deprecated */
    .service(
        'RcmBlankDialogStrategy',
        [
            function () {

                var BlankStrategy = function ($http, $compile) {

                    var self = this;
                    self.params = {
                        url: '',
                        title: '',
                        loading: false
                    }

                    self.template = "RcmStandardDialogTemplate"

                    self.getTemplate = function () {

                        return self.template;
                    };

                    self.setParams = function (params) {

                        self.params = params;
                    };


                    var self = this;
                }
            }
        ]
    )
    /* @deprecated */
    .factory(
        'rcmAdminStrategyFactory',
        [
            '$injector',
            'rcmDialogState',
            'RcmStandardDialogStrategy',
            function ($injector, rcmDialogState, RcmStandardDialogStrategy) {

                var self = this;
                var defaultStrategyId = 'RcmBlankDialogStrategy';

                self.getStrategy = function (params) {

                    var strategyId = params.name + 'Strategy';

                    var injector = $injector;

                    if (!injector.has(strategyId)) {

                        strategyId = defaultStrategyId;
                    }

                    var strategy = injector.get(strategyId);
                    strategy.setParams(params);
                }

                return self;
            }
        ]
    );
/** </rcmDialog> */

////////////////////////////////////////////////
/**
 * rcmAdminMenu
 */
angular.module(
        'rcmAdminMenu',
        ['rcmDialog']
    )
    .directive(
        'RcmAdminMenu',
        [
            '$log',
            'rcmDialogState',
            function ($log, rcmDialogState) {

                var thisLink = function (scope, elm, attrs) {

                    var htlmLink = elm.find("a");

                    htlmLink.on('click', null, null, function (event) {

                        event.preventDefault();

                        // get strategyName
                        var strategyName = 'DEFAULT';

                        if (elm[0].classList[1]) {
                            strategyName = elm[0].classList[1];
                        }

                        var strategy = {
                            loading: true,
                            name: strategyName,
                            title: htlmLink.attr('title'),
                            url: htlmLink.attr('href')
                        }

                        console.log('RcmAdminMenu');

                        scope.$apply(
                            function () {
                                rcmDialogState.open = true;
                                rcmDialogState.loading = true;
                                rcmDialogState.strategy = strategy;
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