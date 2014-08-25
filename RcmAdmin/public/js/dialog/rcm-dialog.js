/**
 * <RcmDialog>
 */
angular.module(
        'RcmDialog',
        []
    )
    .factory(
        'rcmDialogService',
        [
            '$compile',
            function ($compile) {

                var defaultStrategy = 'rcmBlankDialog';

                var Service = function () {

                    var self = this;
                    self.loading = false;
                    self.openState = 'closed'; // open, opening, opened, close, closing, closed
                    // @todo
                    self.dialogElm = null; // set by watcher instead of requiring dialog to trigger
                    self.dialogScope = null;
                    self.strategy = {
                        loading: true,
                        name: defaultStrategy,
                        title: '',
                        url: ''
                    };

                    /**
                     *
                     * @param onInitComplete
                     */
                    self.init = function (onInitComplete) {

                        self.openState = 'init';

                        if (typeof onInitComplete === 'function') {

                            onInitComplete();
                        }
                    }

                    /**
                     *
                     * @param strategy
                     * @param scope
                     */
                    self.openDialog = function (strategy, scope) {

                        var open = function () {

                            self.openState = 'open';
                            self.loading = true;
                            self.strategy = strategy;

                            if (!strategy.name) {
                                strategy.name = defaultStrategy;
                            }
                            $compile(self.dialogElm)(self.dialogScope);

                            $compile(self.dialogElm.contents())(self.dialogScope);

                            setTimeout(function () {
                                self.dialogScope.$apply(
                                    self.onOpenDialog(self.dialogScope, self.dialogElm)
                                );

                            });
                        }

                        if (!self.dialogScope || !self.dialogElm) {

                            self.init(open)
                        } else {

                            open();
                        }
                    }

                    /**
                     *
                     * @param scope
                     * @param elm
                     * @param attrs
                     * @param ctrl
                     */
                    self.onOpenDialog = function (scope, elm, attrs, ctrl) {

                        self.openState = 'opening';

                        /* jQuery IU Modal */
                        self.syncEvents(scope, elm);
                        elm.modal('show');

                        scope.$broadcast('rcmDialogOpen');
                    }

                    /**
                     *
                     * @param scope
                     */
                    self.closeDialog = function (scope) {

                        self.openState = 'close';
                    }

                    /**
                     *
                     * @param scope
                     * @param elm
                     * @param attrs
                     * @param ctrl
                     */
                    self.onCloseDialog = function (scope, elm, attrs, ctrl) {

                        self.openState = 'closing';

                        /* jQuery IU Modal */
                        //self.syncEvents(scope, elm);
                        elm.modal('hide');

                        scope.$broadcast('rcmDialogClose');
                    }

                    self.syncEvents = function (scope, elm) {

                        if (elm.modal) {

                            elm.on(
                                'show.bs.modal',
                                function (event) {
                                    self.openState = 'opening';
                                }
                            );

                            elm.on(
                                'shown.bs.modal',
                                function (event) {
                                    self.openState = 'opened';
                                }
                            );

                            elm.on(
                                'hide.bs.modal',
                                function (event) {
                                    self.openState = 'closing';
                                }
                            );

                            elm.on(
                                'hidden.bs.modal',
                                function (event) {
                                    self.openState = 'closed';
                                    elm.remove(); // prevent multiple instances of modal
                                    scope.$destroy()// prevent multiple instances of modal
                                }
                            );
                        }
                    }
                }

                var service = new Service();

                return service;
            }
        ]
    )
/**
 * RcmDialog.rcmDialog
 */
    .directive(
        'rcmDialog',
        [
            '$compile',
            'rcmDialogService',
            function ($compile, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs) {

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        rcmDialogService.dialogElm = elm;
                        rcmDialogService.dialogScope = scope;

                        scope.rcmDialogService = rcmDialogService;
                        var strategyName = rcmDialogService.strategy.name;

                        scope.directive = strategyName;

                        if (strategyName) {

                            var directiveName = strategyName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();

                            elm.find(':first-child').attr(directiveName, 'rcmDialogService');
                        }
                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    controller: function ($scope) {
                        this.scope = $scope;
                    },
                    template: '<div>-{{directive}}-</div>'
                }
            }
        ]
    )

/**
 * RcmDialog.rcmBlankDialog
 */
    .directive(
        'rcmBlankDialog',
        [
            '$compile',
            'rcmDialogService',
            function ($compile, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        scope.rcmDialogService = rcmDialogService;
                        scope.dialogTemplate = rcmDialogService.strategy.url;
                        scope.loading = false;
                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    template: '<div ng-include="dialogTemplate">--{{dialogTemplate}}--</div>'
                }
            }
        ]
    )

/**
 * RcmDialog.rcmBlankIframeDialog
 */
    .directive(
        'rcmBlankIframeDialog',
        [
            '$compile',
            '$parse',
            'rcmDialogService',
            function ($compile, $parse, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        scope.rcmDialogService = rcmDialogService;
                        scope.url = rcmDialogService.strategy.url;
                        scope.title = rcmDialogService.strategy.title;
                        if(rcmDialogService.strategy.save){
                            scope.save = $parse(rcmDialogService.strategy.save);
                        }
                        scope.loading = false;
                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    template: '' +
                    '<div id="RcmStandardDialogTemplateIn" style="display: block;" ng-hide="loading">' +
                    '<div class="modal-dialog">' +
                    '    <div class="modal-content">' +
                    '        <div class="modal-header">' +
                    '            <button type="button" class="close" data-dismiss="modal"' +
                    '            aria-hidden="true">&times;</button>' +
                    '            <h1 class="modal-title" id="myModalLabel">{{title}}</h1>' +
                    '        </div>' +
                    '        <div class="modal-body" style="height: 400px"><iframe src="{{url}}" style="width: 100%; height: 400px"></iframe>' +
                    '        </div>' +
                    '        <div class="modal-footer">' +
                    '            <button' +
                    '            type="button"' +
                    '            class="btn btn-default"' +
                    '            data-dismiss="modal"' +
                    '            >' +
                    '            Close' +
                    '            </button>' +
                    '            <button type="button" class="btn btn-primary saveBtn" ng-click="save()" ng-show="save">Save' +
                    '            </button>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>' +
                    '</div>'

                }
            }
        ]
    )
/**
 * RcmDialog.rcmBlankSyncDialog.failed
 *  Use this for loading modules with dependencies
 *  - Use script tags in html, not the oc-lazy-loader files array in the oc-lazy-loader directive
 *  - oc-lazy-loader takes time to process dependencies
 */
    .directive(
        'rcmBlankSyncDialog',
        [
            '$log',
            '$compile',
            '$http',
            'rcmDialogService',
            function ($log, $compile, $http, rcmDialogService) {

                var startTime = new Date().getTime();
                var self = this;

                self.restrict = 'A';

                self.compile = function (elm, attrs) {

                    startTime = new Date().getTime();

                    var content = jQuery.ajax(
                        {
                            async: false,
                            //cache: false,
                            url: rcmDialogService.strategy.url,
                            dataType: 'html',
                            success: function () {

                            }
                            //data : { r: Math.random() } // prevent caching
                        }
                    ).responseText

                    elm.html(content);


                    // hide for late compile
                    var orgStyle = elm.attr('style');
                    if (!orgStyle) {
                        orgStyle = '';
                    }
                    elm.attr('style', 'visibility: hidden');

                    return {

                        pre: function (scope, elm, attrs, controller, transcludeFn) {

                            // @todo this is a hack to wait for any dependencies to load and then re-compile
                            var totalTime = (new Date().getTime() - startTime) * 2;

                            setTimeout(
                                function () {
                                    elm.attr('style', orgStyle);
                                    $compile(elm.contents())(scope);
                                    scope.$apply();
                                },
                                totalTime
                            );
                        },
                        post: function (scope, elm, attrs, controller, transcludeFn) {

                        }
                    }
                };

                self.controller = function ($scope, $element) {

                };

                self.template = '<div></div>';

                return self;
            }
        ]
    )
/**
 * RcmDialog.rcmStandardDialog
 */
    .directive(
        'rcmStandardDialog',
        [
            '$compile',
            '$http',
            'rcmDialogService',
            function ($compile, $http, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        $http({method: 'GET', url: rcmDialogService.strategy.url}).
                            success(function (data, status, headers, config) {
                                        var contentBody = elm.find(".modal-body");
                                        contentBody.html(data);
                                        $compile(contentBody)(scope);
                                    }).
                            error(function (data, status, headers, config) {

                                  });

                        scope.dialogTemplate = 'RcmStandardDialogTemplate';
                        scope.title = rcmDialogService.strategy.title;
                        scope.loading = false;
                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    template: '<div ng-include="dialogTemplate">--{{dialogTemplate}}--</div>'
                }
            }
        ]
    )
/**
 * RcmDialog.rcmFormDialog
 */
    .directive(
        'rcmFormDialog',
        [
            '$compile',
            '$http',
            'rcmDialogService',
            function ($compile, $http, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {


                    var thisLink = function (scope, elm, attrs, ctrl) {

                        $http({method: 'GET', url: rcmDialogService.strategy.url}).
                            success(function (data, status, headers, config) {

                                        var contentBody = elm.find(".modal-body");
                                        contentBody.html(data);
                                        $compile(contentBody)(scope);

                                        elm.find(".saveBtn").click(function (event) {
                                            var form = elm.find('form');
                                            var data = form.serializeArray();
                                            var actionUrl = form.attr('action');
                                            contentBody.load(actionUrl, data);
                                        })
                                    }).
                            error(function (data, status, headers, config) {

                                  });


                        scope.dialogTemplate = 'RcmStandardDialogTemplate';
                        scope.title = rcmDialogService.strategy.title;
                        scope.loading = false;
                    };

                    return thisLink;
                }

                return {
                    restrict: 'A',
                    compile: thisCompile,
                    template: '<div ng-include="dialogTemplate">--{{dialogTemplate}}--</div>'
                }
            }
        ]
    )

/* TEST ALT PATTERN: WORKS! /////*
.directive(
    'rcmDialogTest',
    [
        '$compile',
        '$http',
        'rcmDialogService',
        function ($compile, $http, rcmDialogService) {

            return {
                restrict: 'A',
                controller: function ($scope, $element) {

                    var clicker = '<a ng-click="click(1)" href="#">Click me</a>';

                    var cnt = 0;

                    $scope.click = function (arg) {

                        //var startTime = new Date().getTime();

                        $http.get('/modules/rcm-shopping-cart/list-categories.html')
                            .success(
                            function (data) {
                                cnt++;
                                var html = clicker + cnt + data;

                                $scope.html = html;
                            }
                        )
                            .error(
                            function (data) {
                                $scope.html = 'ERROR';
                            }
                        );
                    }
                    $scope.html = clicker;
                },
                template: '<div>' +
                    '<textarea ng-model="html"></textarea>' +
                    '<div rcm-dialog-content="html"></div>' +
                    '</div>'
            }
        }
    ]
)
    .directive(
        'rcmDialogContent',
        [
            '$compile',
            '$ocLazyLoad',
            function ($compile, $ocLazyLoad) {
                return {
                    restrict: 'A',
                    replace: true,
                    link: function (scope, elm, attrs) {

                        scope.$watch(attrs.rcmDialogContent, function (html) {
                            elm.html(html);

                            $compile(elm.contents())(scope);
                        });
                    }
                };
            }
        ]
    )
//* //////////////////////////// */
/** </RcmDialog> */
rcm.addAngularModule('RcmDialog');