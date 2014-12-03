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
                sourcePages: '/api/admin/sites', //ID/pages'
                pageTypes: '/api/admin/pagetypes',
                copyPage: '/api/admin/sites'
            };

            $scope.errorMessage = null;

            $scope.saveMessages = [];

            $scope.loadings = {
                sourceSite: false,
                destinationSites: false,
                sourcePages: false,
                pageTypes: false
            };

            $scope.step = 1;

            $scope.sourceSite = {};
            $scope.destinationSites = [];
            $scope.sourcePages = [];
            $scope.pageTypes = {};

            $scope.destinationSite = null;

            $scope.selectedPages = [];
            $scope.selectedPageType = null;

            $scope.toggleSelectPage = function (page) {
                var index = $scope.selectedPages.indexOf(page);
                if (index < 0) {
                    $scope.selectedPages.push(page);
                } else {
                    $scope.selectedPages.splice(
                        index,
                        1
                    );
                }
            };

            $scope.clearSelectedPages = function () {
                $scope.selectedPages = [];
            };

            $scope.copySelectedPages = function () {
                $scope.copyMessages = [];

                angular.forEach(
                    $scope.selectedPages,
                    self.copyPage
                )
            };

            self.copyPage = function (page) {

                page.copyToSiteId = $scope.destinationSite.siteId;

                console.log(page);

                $http(
                    {
                        method: 'POST',
                        url: self.url.copyPage + '/' + $scope.sourceSite.siteId + '/pages/' + page.pageId,
                        data: page
                    }
                )
                    .success(
                    function (data) {
                        console.log(data);
                        self.parseCopyMessage(data, page);
                    }
                )
                    .error(
                    function (data) {
                        console.error(data);
                        data.code = 1;
                        self.parseCopyMessage(data, page);
                    }
                );
            };

            self.parseCopyMessage = function (result, page) {

                if (result.code == 1) {
                    $scope.copyMessages.push(
                        {
                            code: 1,
                            message: 'An Error Occured while saving ' + page.pageTitle + '(' + page.name + '): ' + result.message
                        }
                    );
                } else {
                    $scope.copyMessages.push(
                        {
                            code: 0,
                            message: 'Saved ' + page.pageTitle + '(' + page.name + '): ' + result.message
                        }
                    );
                }
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
                        url: self.url.sourcePages + '/' + $scope.sourceSite.siteId + '/pages'
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