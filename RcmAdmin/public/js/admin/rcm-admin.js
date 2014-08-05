/**
 * <rcmDialog>
 */
angular.module(
        'RcmDialog',
        ['ngSanitize']
    )
    .factory(
        'rcmDialogService',
        [
            function () {

                var defaultStrategy = 'rcmBlankDialog';

                var Service = function () {

                    var self = this;
                    self.loading = false;
                    self.openState = 'closed'; // open, opening, opened, close, closing, closed
                    // @todo self.dialogElm = null; // set by watcher instead of requiring dialog to trigger
                    self.strategy = {
                        loading: true,
                        name: defaultStrategy,
                        title: '',
                        url: ''
                    };

                    /**
                     *
                     * @param strategy
                     * @param scope
                     */
                    self.openDialog = function (strategy, scope) {

                        self.openState = 'open';
                        self.loading = true;
                        self.strategy = strategy;

                        console.log('openDialog' + self.strategy.name);

                        if (!strategy.name) {
                            strategy.name = defaultStrategy;
                        }

                        scope.$apply();
                    }

                    /**
                     *
                     * @param scope
                     * @param elm
                     * @param attrs
                     * @param ctrl
                     */
                    self.onOpenDialog = function (scope, elm, attrs, ctrl) {

                        console.log('onOpenDialog' + self.strategy.name);

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

                        console.log('closeDialog: ' + self.strategy.name);

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

                        console.log('onCloseDialog: ' + self.strategy.name);
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
                                    console.log('openState: opening');
                                }
                            );

                            elm.on(
                                'shown.bs.modal',
                                function (event) {
                                    self.openState = 'opened';
                                    console.log('openState: opened');
                                }
                            );

                            elm.on(
                                'hide.bs.modal',
                                function (event) {
                                    self.openState = 'closing';

                                    console.log('openState: closing');
                                }
                            );

                            elm.on(
                                'hidden.bs.modal',
                                function (event) {
                                    self.openState = 'closed';
                                    //elm.remove(); // prevent multiple instances of modal
                                    scope.$destroy()
                                    console.log('openState: closed');
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
 * rcmDialog
 */
    .directive(
        'rcmDialog',
        [
            '$compile',
            'rcmDialogService',
            function ($compile, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs) {

                    var thisLink = function (scope, elm, attrs, ctrl) {

                        self = this;

                        scope.rcmDialogService = rcmDialogService;
                        var strategyName = rcmDialogService.strategy.name;

                        scope.directive = strategyName;

                        if (strategyName) {
                            var directiveName = strategyName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();

                            elm.find(':first-child').attr(directiveName, 'rcmDialogService');
                        }

                        scope.$watch(
                            'rcmDialogService.openState',
                            function (newValue, oldValue) {

                                if (newValue == 'open') {

                                    rcmDialogService.onOpenDialog(scope, elm, attrs, ctrl);

                                    $compile(elm)(scope);
                                    $compile(elm.contents())(scope);
                                }
                            }
                        );
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
 * rcmBlankDialog
 */
    .directive(
        'rcmBlankDialog',
        [
            '$compile',
            'rcmDialogService',
            function ($compile, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {

                    var thisLink = function (scope, elm, attrs, ctrl) {

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
    .directive(
        'rcmStandardDialog',
        [
            '$compile',
            '$http',
            'rcmDialogService',
            function ($compile, $http, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {


                    var thisLink = function (scope, elm, attrs, ctrl) {

                        console.log('rcmFormDialog: LINK');
                        $http({method: 'GET', url: rcmDialogService.strategy.url}).
                            success(function (data, status, headers, config) {
                                        console.log('http');
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
    .directive(
        'rcmFormDialog',
        [
            '$compile',
            '$http',
            'rcmDialogService',
            function ($compile, $http, rcmDialogService) {

                var thisCompile = function (tElement, tAttrs, transclude) {


                    var thisLink = function (scope, elm, attrs, ctrl) {

                        console.log('rcmFormDialog: LINK');
                        $http({method: 'GET', url: rcmDialogService.strategy.url}).
                            success(function (data, status, headers, config) {
                                        console.log('http');
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
    );

/** </rcmDialog> */

////////////////////////////////////////////////
/**
 * rcmAdminMenu
 */
angular.module(
        'rcmAdminMenu',
        ['RcmDialog']
    )
    .directive(
        'RcmAdminMenu',
        [
            '$log',
            'rcmDialogService',
            function ($log, rcmDialogService) {

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

                        rcmDialogService.openDialog(strategy, scope);
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