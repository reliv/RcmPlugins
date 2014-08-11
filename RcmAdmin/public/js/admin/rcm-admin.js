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

                    self.editing = []; // page, layout, sitewide
                    self.editMode = false;

                    self.setEditing = function(type, val, callback){

                        if (val) {

                            if (self.editing.indexOf(type) < 0) {
                                self.editing.push(type);
                            }
                        } else {

                            if (self.editing.indexOf(type) > -1) {

                                self.editing.splice(
                                    self.editing.indexOf(type),
                                    1
                                )
                            }
                        }

                        self.editMode = (self.editing.length > 0);

                        if(callback){
                            callback(self);
                        }
                    }

                    self.isEditing = function(type) {

                        if(type){

                            return (self.editing.indexOf(type) > -1);
                        }

                        self.editMode = (self.editing.length > 0);

                        return self.editMode;
                    }

                    /*
                     * @todo disable click and other content events
                     * @todo check for attributes to see if edit should be enabled
                     */
                    self.initEdit = function (elm) {

                        if (self.canEdit(elm)) {
                            self.doInitEdit(elm);
                        }
                    }

                    self.canEdit = function (elm) {

                        var isPagePlugin = (elm.attr('data-isPageContainer') == 'Y');

                        var isLayoutPlugin = (elm.attr('data-isPageContainer') != 'Y');

                        var isSitewidePlugin = (elm.attr('data-rcmSiteWidePlugin') == '1');

                        // must be in page edit mode
                        if (!self.isEditing('page')) {

                            return false;
                        }

                        if (self.isEditing('sitewide') && isSitewidePlugin) {

                            return true;
                        }

                        if (self.isEditing('layout') && isLayoutPlugin) {

                            return true;
                        }

                        if (self.isEditing('page') && isPagePlugin) {

                            return true;
                        }

                        return false;
                    }

                    self.doInitEdit = function (elm) {

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

                    self.cancelEdit = function (elm) {

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

                    scope.editing = rcmAdminService.editing;

                    elm.on('click', null, null, function () {

                        var editingState = attrs.rcmAdminEditButton;

                        if(!editingState) {
                            editingState = 'page';
                        }

                        rcmAdminService.setEditing(
                            editingState,
                            !rcmAdminService.isEditing(editingState),
                            function(){
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
                            'rcmAdminService.editing',
                            function (newValue, oldValue) {

                                if (rcmAdminService.isEditing()) {

                                    rcmAdminService.initEdit(elm);
                                }
                            },
                            true
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
                            'rcmAdminService.editing',
                            function (newValue, oldValue) {

                                if (rcmAdminService.isEditing()) {
                                    // @todo disable click and other content events
                                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                } else {
                                    // @todo enable click and other content events + prepare
                                    rcmHtmlEditorDestroy(attrs.id);
                                }
                            },
                            true
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
                            'rcmAdminService.editing',
                            function (newValue, oldValue) {

                                if (rcmAdminService.isEditing()) {
                                    // @todo disable click and other content events
                                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                                } else {
                                    // @todo enable click and other content events + prepare
                                    rcmHtmlEditorDestroy(attrs.id);
                                }
                            },
                            true
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