var rcmCore =  angular.module('rcmCore');

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
    var angularBootstrap = function (moduleName, controllerName) {
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



