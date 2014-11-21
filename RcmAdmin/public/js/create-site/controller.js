angular.module('rcmAdmin').controller(
    'rcmAdminCreateSiteController',
    function ($scope, $log, $http) {

        var self = this;

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
                    url: '/api/admin/manage-sites/-1'
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
                    url: '/api/admin/theme'
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
                    url: '/api/admin/language'
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
                    url: '/api/admin/country'
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
            console.log($scope.site);
            $http(
                {
                    method: 'POST',
                    url: '/api/admin/manage-sites',
                    data: $scope.site
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

        self.parseCreateResult = function (data) {

            self.parseMessage(data);

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
);
