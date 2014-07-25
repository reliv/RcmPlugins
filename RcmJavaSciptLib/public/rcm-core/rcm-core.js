
var rcm = new function () {

    self = this;

    self.moduleDepenencies = [];

    self.angularModule = null;

    self.addModule = function (moduleName) {

        if (self.angularModule) {

            self.console.error(
                'Module '+moduleName+' cannot be registered after init is called.'
            );
        }

        if (self.moduleDepenencies.indexOf(moduleName) < 0) {

            self.moduleDepenencies.push(moduleName);
            self.console.log('Module '+moduleName+' registered.');
        }
    }

    self.addModules = function (moduleNames) {

        for(key in moduleNames){

            self.addModule(moduleNames[key]);
        }
    }

    self.init = function (document) {

        self.angularModule = angular.module('rcm', self.moduleDepenencies)
    }

    if (window.console) {

        self.console = window.console;
    } else {

        /* keep older browsers from blowing up */
        self.console = function () {

            self = this;

            self.log = function (msg) {
            };

            self.info = function (msg) {
            };

            self.warn = function (msg) {
            };

            self.error = function (msg) {
            };

            /* there are more methods, but this covers the basics */
        }
    }
};


