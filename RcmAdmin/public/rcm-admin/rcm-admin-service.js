/**
 * RcmAdminService
 */
var RcmAdminService = new function () {

    var self = this;

    /**
     * config
     */
    self.config = rcmAdminServiceConfig;

    /**
     * page
     */
    self.page = null;

    /**
     * RcmEventManager
     * @constructor
     */
    self.rcmEventManager = new RcmEventManager();

    /**
     * RcmLoading
     */
    self.rcmLoading = new RcmLoading(
        function (data) {
            self.rcmEventManager.trigger(
                'RcmAdminService.RcmLoading.start',
                data
            )
        },
        function (data) {

            self.rcmEventManager.trigger(
                '.RcmAdminServiceRcmLoading.end',
                data
            );
        }
    );

    /**
     * model
     */
    self.model = new RcmAdminModel();

    /**
     * viewModel
     */
    self.viewModel = null;

    /**
     * buildViewModel
     */
    self.buildViewModel = function(){

        self.viewModel = new RcmAdminViewModel(self.config, self.model, self.page);

        self.page.events.on(
            'alert',
            self.viewModel.alertDisplay
        );

        self.page.events.on(
            'loadingStateChange',
            self.viewModel.loadingDisplay
        );
    };

    /**
     * getPage
     * @param onBuilt
     * @returns {null}
     */
    self.getPage = function (onBuilt) {

        if (!self.page) {

            self.page = new RcmAdminPage(
                self.model.RcmPageModel.getElm(),
                function(page) {

                    self.page = page;

                    self.buildViewModel();

                    if(typeof onBuilt === 'function') {
                        onBuilt(page);
                    }
                },
                self
            );
        } else {
            if (typeof onBuilt === 'function') {
                onBuilt(self.page);
            }
        }

        return self.page
    };

    /**
     * getPlugin
     * @param id
     * @param onComplete
     * @returns {RcmPlugin}
     */
    self.getPlugin = function (id, onComplete) {

        var page = self.getPage(
            function (page) {
                if (typeof onComplete === 'function') {
                    onComplete(page.getPlugin(id));
                }
            }
        );

        return page.getPlugin(id);
    };

    /**
     * angularCompile - only use this if you need to compile after dom change!!!!!
     * @param elm
     * @param fn
     */
    self.angularCompile = function (elm, fn) {

        var compile = angular.element(elm).injector().get('$compile');

        var scope = angular.element(elm).scope();

        compile(elm.contents())(scope);

        if (scope.$$phase || scope.$root.$$phase) {

            scope.$apply(fn);
        } else {

            if (typeof fn === 'function') {
                fn();
            }
        }
    };
};
