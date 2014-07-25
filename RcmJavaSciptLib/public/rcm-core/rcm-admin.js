/**
 * Angular JS module used to show HTML editor and toolbar on a page
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


var RcmLayout = function () {

    var self = this;

    /**
     * Layout type
     * page, layout
     * @type {string}
     */
    self.type = 'page';
}

var RcmContainer = function () {

    var self = this;

    /**
     * @type RcmLayout
     */
    self.layout;
}


var RcmPlugin = function () {

    var self = this;

    /**
     * @type RcmContainer
     */
    self.container;

    self.data = {};

    self.state = {

        editMode: false
    }

    /**
     *
     */
    self.init = function (scope, elm, attrs, ngModel) {

        // populate data from attributes
    }

    /**
     *
     */
    self.lockPlugin = function () {

    }

    /**
     *
     */
    self.unlockPlugin = function () {

    }

    /**
     *
     */
    self.getContainerData = function () {

    }
}

var RcmPluginHtmlEditor = function () {

    var self = this;

    /**
     * @type RcmPlugin
     */
    self.plugin;

}