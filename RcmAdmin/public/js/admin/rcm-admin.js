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
/**
 * rcmAdmin.rcmAdminEditButton
 */
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
/**
 * rcmAdmin.rcmplugininstanceid
 */
    .directive(
        'rcmplugininstanceid',
        [
            '$compile',
            'rcmAdminState',
            function ($compile, rcmAdminState) {

                var thisCompile = function (tElem, attrs) {

                    var link = function (scope, elm, attrs, ngModel) {

                        var id = attrs.rcmplugininstanceid;

                        scope.rcmAdminState = rcmAdminState;

                        var name = attrs.rcmpluginname;

                        if (name) {

                            var className = name + 'Edit';
                            var editClass = window[className];

                            console.log(className);

                            if (editClass) {

                                var editObj = new editClass(id, elm.find('.rcmPluginContainer')); // first child

                                console.log(name + '.initEdit()');
                                editObj.initEdit();
                            }
                        }
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
/**
 * rcmAdmin.textedit
 */
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