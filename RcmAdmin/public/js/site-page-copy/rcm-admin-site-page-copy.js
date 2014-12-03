angular.module('rcmAdmin')
    .controller(
    'rcmAdminSitePageCopyController',
    [
        '$scope', '$http',
        function ($scope, $http) {

            var self = this;

            self.url = {
                sourceSite: '/api/admin/manage-sites/current',
                destinationSites: '/api/admin/manage-sites',
                sourcePages: '/api/admin/sites/', //ID/pages'
                pageTypes: '/api/admin/pagetypes'
            };

            $scope.errorMessage = null;

            $scope.loadings = {
                sourceSite: false,
                destinationSites: false,
                sourcePages: false,
                pageTypes: false
            };

            $scope.step = 1;

            $scope.sourceSite = {};
            $scope.destinationSites = [];
            $scope.sourcePages = {};
            $scope.pageTypes = {};

            $scope.destinationSite = null;

            $scope.selectedPages = {};
            $scope.selectedPageType = null;

            $scope.toggleSelectPage = function (page) {
                if ($scope.selectedPages[page.pageId]) {
                    $scope.selectedPages[page.pageId] = null;
                } else {
                    $scope.selectedPages[page.pageId] = page;
                }
            };

            $scope.clearSelectedPages = function () {
                $scope.selectedPages = {};
            };

            self.parseMessage = function (result) {

                if (result.code == 1) {
                    $scope.errorMessage = $scope.errorMessage + ' ' + result.message;
                }
            };

            self.getSourceSite = function () {
                $scope.loadings.sourceSite = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.sourceSite
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);
                        $scope.sourceSite = data.data;
                        self.getSourcePages();
                        $scope.loadings.sourceSite = false;
                    }
                )
                    .error(
                    function (data) {
                        data.code = 1;
                        data.message = 'An Error Occured: ' + data.message;
                        self.parseMessage(data);
                        $scope.loadings.sourceSite = false;
                    }
                );
            };

            self.getSourcePages = function () {
                $scope.loadings.sourcePages = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.sourcePages + $scope.sourceSite.siteId + '/pages'
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);
                        $scope.sourcePages = data.data;
                        $scope.loadings.sourcePages = false;
                    }
                )
                    .error(
                    function (data) {
                        data.code = 1;
                        data.message = 'An Error Occured: ' + data.message;
                        self.parseMessage(data);
                        $scope.loadings.sourcePages = false;
                    }
                );
            };

            self.prepareSourcePages = function(data) {
                angular.forEach(
                    data,
                    function(value, key) {
                        this.push(key + ': ' + value);
                    }
                )
            }

            self.getDestinationSites = function () {
                $scope.loadings.destinationSites = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.destinationSites
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);
                        $scope.destinationSites = data.data;
                        $scope.loadings.destinationSites = false;
                    }
                )
                    .error(
                    function (data) {
                        console.error(data);
                        data.code = 1;
                        data.message = 'An Error Occured: ' + data.message;
                        self.parseMessage(data);
                        $scope.loadings.destinationSites = false;
                    }
                );
            };

            self.getPageTypes = function () {
                $scope.loadings.pageTypes = true;
                $http(
                    {
                        method: 'GET',
                        url: self.url.pageTypes
                    }
                )
                    .success(
                    function (data) {
                        self.parseMessage(data);
                        $scope.pageTypes = data.data;
                        $scope.loadings.pageTypes = false;
                    }
                )
                    .error(
                    function (data) {
                        console.error(data);
                        data.code = 1;
                        data.message = 'An Error Occured: ' + data.message;
                        self.parseMessage(data);
                        $scope.loadings.pageTypes = false;
                    }
                );
            };


            self.getSourceSite();

            self.getPageTypes();

            self.getDestinationSites();
        }
    ]
).filter(
    'rcmAdminPageTypeFilter',
    function () {

        var compareStr = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return stra.indexOf(strb) !== -1;
        }

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = [];

            angular.forEach(
                input, function (page) {
                    if (compareStr(page.pageType, query)) {
                        result.push(page);
                    }
                }
            );

            return result;
        };
    }
);