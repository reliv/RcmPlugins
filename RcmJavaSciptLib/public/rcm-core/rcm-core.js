var rcmHelpers = function () {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {Rcm}
     */
    var self = this;

    /**
     * Allows your angular controllers and modules to play nice with the other
     * plugins' angular controllers and modules. Call this above each angular
     * controller your create. The directive 'ng-app' is NOT needed in the view.
     * @param moduleName string usually same as plugin name
     * @param controllerName the angular controller name
     */
    self.angularBootstrap = function (moduleName, controllerName) {
        angular.element(document).ready(function () {
            $.each(
                $('[ng-controller=' + controllerName + ']'),
                function (key, element) {
                    angular.bootstrap(element, [moduleName]);
                }
            );
        });
    };
}

var RcmLayout = function() {

    var self = this;

    /**
     * Layout type
     * page, layout
     * @type {string}
     */
    self.type = 'page';

}

var RcmContainer = function() {

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
    self.getContainerData = function() {

    }
}

var RcmPluginHtmlEditor = function () {

    var self = this;

    /**
     * @type RcmPlugin
     */
    self.plugin;

}


