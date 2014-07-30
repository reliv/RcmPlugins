var rcm = new function () {

    var self = this;

    self.moduleDepenencies = ['oc.lazyLoad'];

    self.app = null;

    self.ocLazyLoad;

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

        if (self.app) {

            if (self.ocLazyLoad && lazyloadConfig) {

                lazyloadConfig.name = moduleName

                self.ocLazyLoad.load(lazyloadConfig);

                self.pushModuleName(moduleName);
                self.console.log('Module ' + moduleName + ' Registered with lazy loader.');
                return;
            }

            self.console.info('Module ' + moduleName + ' Could not be register.');
            return;
        }

        self.pushModuleName(moduleName);
        self.console.log('Module ' + moduleName + ' Registered.');
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
     * @returns boolean
     */
    self.pushModuleName = function (moduleName) {

        if (self.moduleDepenencies.indexOf(moduleName) < 0) {

            self.moduleDepenencies.push(moduleName);

            return true;
        }

        return false;
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
                self.app = angularModule;
                self.console.info('Bootstrap angular module with: ');
                self.console.info(self.app.requires);
                angular.bootstrap(
                    document,
                    ['rcm']
                );

                try {
                    self.ocLazyLoad = angular.element(document).injector().get('$ocLazyLoad');
                } catch (e) {
                }
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



