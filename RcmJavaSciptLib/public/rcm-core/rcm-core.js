var rcm = new function () {

    var self = this;

    self.moduleDepenencies = ['oc.lazyLoad'];

    self.app = null;

    self.ocLazyLoad;
    self.compile;
    self.scope;

    /**
     *
     * @param moduleName AngularJS Module name
     * @param ezloadConfig
     * EXAMPLE:
     * {
     *   name: 'e',
     *   files: ['/modules/my/script.js']
     * }
     */
    self.addAngularModule = function (moduleName, lazyloadConfig) {

        if (self.hasModule(moduleName)){

            //self.console.log('Module (' + moduleName + ') already registered.');
            return;
        }

        if (self.ocLazyLoad) {

            self.console.log('rcm.addAngularModule.ocLazyLoad: '+moduleName);

            if (!lazyloadConfig) {
                lazyloadConfig = {};
            }

            lazyloadConfig.name = moduleName;

            self.ocLazyLoad.load(lazyloadConfig)
                .then(
                function () {
                    self.pushModuleName(moduleName);
                    self.scope.safeApply(
                        function(){
                            //console.log(self.ocLazyLoad.getModules());
                        }
                    );
                }
            );

            return;
        }

        if (!self.app) {
            self.console.log('rcm.addAngularModule.pushModuleName: '+moduleName);
            self.pushModuleName(moduleName);
            //self.console.log('Module (' + moduleName + ') registered.');
            //self.console.log(self.moduleDepenencies);
        }
    }

    /**
     *
     * @param moduleConfigs
     * EXAMPLE: [name]: [lazyLoadConfig]
     * {
     *  'myModuleName': {files: ['/modules/my/script.js']}
     * }
     */
    self.addAngularModules = function (moduleConfigs) {

        for (var moduleName in moduleConfigs) {

            self.addAngularModule(moduleName, moduleConfigs[moduleName]);
        }
    }

    /**
     *
     * @param moduleName
     */
    self.pushModuleName = function (moduleName) {

        if (!self.hasModule(moduleName)){

            self.moduleDepenencies.push(moduleName);
        }
    }

    /**
     *
     * @param moduleName
     * @returns {boolean}
     */
    self.hasModule = function(moduleName){

        if (self.moduleDepenencies.indexOf(moduleName) < 0) {
            return false;
        }

        return true;
    }

    /**
     *
     * @param document
     */
    self.init = function (document) {

        var angularModule = angular.module('rcm', self.moduleDepenencies)
            .config(
                [
                    '$ocLazyLoadProvider',
                    function ($ocLazyLoadProvider) {
                        $ocLazyLoadProvider.config(
                            {
                                //asyncLoader: requirejs,
                                debug: true,
                                events: true,
                                loadedModules: ['rcm']
                            }
                        );
                    }
                ]
            );

        angular.element(document).ready(
            function () {

                angular.bootstrap(
                    document,
                    ['rcm']
                );

                self.app = angularModule;

                self.ocLazyLoad = angular.element(document).injector().get('$ocLazyLoad');

                self.compile = angular.element(document).injector().get('$compile');

                self.scope = angular.element(document).scope();

                self.scope.safeApply = function(fn) {
                    var phase = self.scope.$root.$$phase;
                    if(phase == '$apply' || phase == '$digest') {
                        if(fn && (typeof(fn) === 'function')) {
                            fn();
                        }
                    } else {
                        self.scope.$apply(fn);
                    }
                };
            }
        )
        ;
    }

    /**
     * Browser safe console replacement
     */
    self.console = function () {
    };

    /**
     * Initialize the console
     */
    self.initConsole = function () {
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

            window.console = self.console;
        }
    }

    // construct
    self.initConsole();
    self.init(document);
};

//angular.element(document).ready(
//    function () {
//        rcm.init(document);
//    }
//);



