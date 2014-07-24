/**
 * Angular JS module used to shoe HTML editor and toolbar on a page
 * @require:
 *  AngularJS
 *  TinyMce
 */
angular.module(
        'RcmAdmin',
        ['RcmHtmlEditor']
    )
/**
 *
 */
    .factory(
        'rcmAdminState',
        [
            function () {
                var state = {
                    editMode: false,
                    editModeSite: false
                }

                return state;
            }
        ]
    )
/**
 *
 */
    .factory(
        'rcmHtmlEditByState',
        [
            'rcmAdminState',
            'rcmHtmlEdit',
            function (rcmAdminState, rcmHtmlEdit) {

                var editing = [];

                return function () {

                    if (editMode) {

                        editing.push(id);
                    } else {

                        if (editing.indexOf(id) > -1) {

                            editing.splice(
                                editing.indexOf(id),
                                1
                            )
                        }
                    }

                    rcmAdminState.editMode = editing.length > 0;
                }
            }
        ]
    )
    /**
     * TEMP
     */
    .factory(
        'rcmAdminEditMode',
        [
            'rcmAdminState',
            function (rcmAdminState) {

                var editing = [];

                return function (id, editMode) {

                    if (editMode) {

                        editing.push(id);
                    } else {

                        if (editing.indexOf(id) > -1) {

                            editing.splice(
                                editing.indexOf(id),
                                1
                            )
                        }
                    }

                    rcmAdminState.editMode = editing.length > 0;
                }
            }
        ]
    )

    .directive(
        'richedit',
        [
            '$compile',
            'rcmAdminState',
            'rcmHtmlEditorInit',
            function ($compile, rcmAdminState, rcmHtmlEditorInit) {

                var config = {};

                var thisLink = function (scope, elm, attrs, ngModel) {

                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                };

                return {
                    priority: 10,
                    restrict: 'A',
                    require: '?ngModel',
                    link: thisLink
                }
            }
        ]
    )
    .directive(
        'textedit',
        [
            '$compile',
            'rcmAdminState',
            'rcmHtmlEditorInit',
            function ($compile, rcmAdminState, rcmHtmlEditorInit) {

                var config = {};

                var thisLink = function (scope, elm, attrs, ngModel) {

                    attrs.$set('htmlEditorType', 'text');

                    rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                };

                return {
                    priority: 10,
                    restrict: 'A',
                    require: '?ngModel',
                    link: thisLink
                }
            }
        ]
    );
