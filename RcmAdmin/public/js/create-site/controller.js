angular.module('rcmAdmin').controller(
    'rcmAdminCreateSiteController',
    [
        '$scope', '$http',
        function ($scope, $http) {

            var self = this;

            self.url = {
                defaultSite: '/api/admin/manage-sites/default',
                themes: '/api/admin/theme',
                languages: '/api/admin/language',
                countries: '/api/admin/country',
                createSite: '/api/admin/manage-sites'
            };

            $scope.loadings = {
                defaultSite: false,
                themes: false,
                languages: false,
                countries: false,
                createSite: false
            };

            $scope.site = {};
            $scope.themes = {};
            $scope.languages = {};
            $scope.countries = {};

            $scope.done = false;

            $scope.code = 0;
            $scope.message = '';
            $scope.errorMessage = '';

            self.parseMessage = function (result) {

                if (result.code == 1) {
                    $scope.errorMessage = $scope.errorMessage + ' ' + result.message;
                }
            };

            self.resetMessage = function (result) {

                $scope.code = 0;
                $scope.message = '';
                $scope.errorMessage = '';
            };

            $scope.reset = function () {

                self.resetMessage();
                $scope.done = false;
            };

            self.getDefaultSite = function () {
                $scope.loadings.defaultSite = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.defaultSite
                    }
                )
                    .success(
                    function (data) {

                        self.parseMessage(data);

                        $scope.site = data.data;
                        $scope.loadings.defaultSite = false;
                    }
                )
                    .error(
                    function (data) {
                        self.parseMessage(data);

                        $scope.site = data.data;
                        $scope.loadings.defaultSite = false;
                    }
                );
            };

            self.getThemes = function () {
                $scope.loadings.themes = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.themes
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);

                        $scope.themes = data.data;
                        $scope.loadings.themes = false;
                    }
                )
                    .error(
                    function (data) {
                        self.parseMessage(data);

                        $scope.themes = data.data;
                        $scope.loadings.themes = false;
                    }
                );
            };

            self.getLanguages = function () {
                $scope.loadings.languages = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.languages
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);

                        $scope.languages = data.data;
                        $scope.loadings.languages = false;
                    }
                )
                    .error(
                    function (data) {
                        self.parseMessage(data);

                        $scope.languages = data.data;
                        $scope.loadings.languages = false;
                    }
                );
            };

            self.getCountries = function () {
                $scope.loadings.countries = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.countries
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);

                        $scope.countries = data.data;
                        $scope.loadings.countries = false;
                    }
                )
                    .error(
                    function (data) {
                        self.parseMessage(data);

                        $scope.countries = data.data;
                        $scope.loadings.countries = false;
                    }
                );
            };

            $scope.createSite = function () {
                $scope.loadings.createSite = true;
                self.resetMessage();

                var site = self.prepareData($scope.site);

                $http(
                    {
                        method: 'POST',
                        url: self.url.createSite,
                        data: site
                    }
                )
                    .success(
                    function (data) {
                        self.parseCreateResult(data);
                        $scope.loadings.createSite = false;
                    }
                )
                    .error(
                    function (data) {
                        self.parseCreateResult(data);
                        $scope.loadings.createSite = false;
                    }
                );
            };

            self.prepareData = function (site) {

                // make sure we don't sent and Id
                site.siteId = null;
                // force default site layout
                site.siteLayout = 'default';
                // force empty favicon
                site.favIcon = null;

                return site;
            }

            self.parseCreateResult = function (data) {

                self.parseMessage(data);

                $scope.site.siteId = null;

                // Success check
                if (data.code == 0) {
                    $scope.site = data.data;
                    $scope.message = data.message;
                    $scope.done = true;
                }

                // prepare errors for display
                if (data.errors) {
                    angular.forEach(
                        data.errors,
                        function (value, key) {
                            angular.forEach(
                                value,
                                function (evalue, ekey) {
                                    data.errors[key] = evalue + ' ';
                                }
                            );
                        }
                    );
                }

                $scope.createResult = data;
            };

            self.getDefaultSite();

            self.getThemes();

            self.getLanguages();

            self.getCountries();
        }
    ]
);
