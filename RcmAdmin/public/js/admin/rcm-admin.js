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

                var Service = function () {

                    var self = this;
                    self.editMode = false;
                    self.editing = []; // page, layout, site-wide
                    self.editingState = {
                        page: false,
                        layout: false,
                        sitewide: false
                    };

                    /*
                     * @todo disable click and other content events
                     * @todo check for attributes to see if edit should be enabled
                     */
                    self.initEdit = function(elm){

                        self.doInitEdit(elm);
                    }

                    self.getEditType = function(elm) {

                        var isPagePlugin = (elm.attr('data-isPageContainer') == 'Y');

                        var isLayoutPlugin = (elm.attr('data-isPageContainer') != 'Y');

                        var isLayoutPlugin = (elm.attr('data-isPageContainer') != 'Y');
                    }

                    self.initEditPage = function(elm) {

                        if(self.editingState.page){

                            self.doInitEdit(elm);
                        }
                    }

                    self.initEditLayout = function(elm) {

                        var isLayoutPlugin = (elm.attr('data-isPageContainer') != 'Y');

                        if(self.editingState.layout && isLayoutPlugin){

                            self.doInitEdit(elm);
                        }
                    }

                    self.initEditSitewide = function(elm) {

                        var isSitewidePlugin = (elm.attr('data-rcmSiteWidePlugin') == '1');

                        if(self.editingState.sitewide && isSitewidePlugin){

                            self.doInitEdit(elm);
                        }
                    }

                    self.doInitEdit = function(elm) {

                        var name = elm.attr('data-rcmPluginName');
                        var id = elm.attr('data-rcmPluginInstanceId');

                        if (name) {

                            var className = name + 'Edit';
                            var editClass = window[className];

                            if (editClass) {

                                var editObj = new editClass(id, elm.find('.rcmPluginContainer')); // first child

                                editObj.initEdit();
                            }
                        }
                    }

                    self.cancelEdit = function(elm){

                        window.location = window.location.pathname;
                    }
                }

                var service = new Service();

                return service;
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

                    elm.on('click', null, null, function () {
                        scope.$apply(
                            function () {
                                rcmAdminService.editMode = !rcmAdminService.editMode;
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
 * rcmAdmin.rcmplugininstanceid
 */
    .directive(
        'rcmplugininstanceid',
        [
            '$compile',
            'rcmAdminService',
            function ($compile, rcmAdminService) {

                var thisCompile = function (tElem, attrs) {

                    var link = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminService = rcmAdminService;

                        scope.$watch(
                            'rcmAdminService.editMode',
                            function (newValue, oldValue) {

                                if (newValue) {
                                    rcmAdminService.initEdit(elm);
                                } else {
                                    //rcmAdminService.cancelEdit(elm);
                                }
                            }
                        );

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

                    //attrs.$set('richedit', '{{rcmAdminService.editMode}}');

                    var thisLink = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminService = rcmAdminService;

                        scope.$watch(
                            'rcmAdminService.editMode',
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

                    //attrs.$set('textedit', '{{rcmAdminService.editMode}}');

                    var thisLink = function (scope, elm, attrs, ngModel) {

                        scope.rcmAdminService = rcmAdminService;

                        attrs.$set('htmlEditorType', 'text');

                        scope.$watch(
                            'rcmAdminService.editMode',
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