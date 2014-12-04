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



            $scope.loadings = {
                sourceSite: false,
                destinationSites: false,
                sourcePages: false,
                pageTypes: false,
                copyPage: false
            };

            $scope.step = 1;

            $scope.sourceSite = {};
            $scope.destinationSites = [];
            $scope.sourcePages = [];
            $scope.pageTypes = {};

            $scope.destinationSite = null;

            $scope.selectedPages = [];
            $scope.selectedPageType = null;
            $scope.filteredPages = [];

            /**
             * toggleSelectPage
             * @param page
             */
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

            /**
             * clearSelectedPages
             */
            $scope.clearCopyMessages = function () {
                $scope.copyMessages = {
                    count: 0,
                    error: [],
                    success: []
                };

                $scope.showCopyErrors = false;
                $scope.showCopySuccesses = false;
            };
            $scope.clearCopyMessages();

            /**
             * clearSelectedPages
             */
            $scope.clearSelectedPages = function () {
                $scope.selectedPages = [];
            };

            /**
             * selectFilteredPages - Select all of the filtered pages
             */
            $scope.selectFilteredPages = function () {

                if ($scope.filteredPages) {
                    $scope.selectedPages = $scope.filteredPages;
                }
            };

            /**
             * copySelectedPages - Go thur the selected and do the copy
             */
            $scope.copySelectedPages = function () {
                $scope.loadings.copyPage = true;
                $scope.clearCopyMessages();

                angular.forEach(
                    $scope.selectedPages,
                    self.copyPage
                );

                $scope.loadings.copyPage = false;
            };

            /**
             * copyPage - Do the page copying
             * @param page
             */
            self.copyPage = function (page) {

                page.copyToSiteId = $scope.destinationSite.siteId;

                $http(
                    {
                        method: 'POST',
                        url: self.url.copyPage + '/' + $scope.sourceSite.siteId + '/pages/' + page.pageId,
                        data: page
                    }
                )
                    .success(
                    function (data) {
                        self.parseCopyMessage(data, page);
                    }
                )
                    .error(
                    function (data) {
                        data.code = 1;
                        self.parseCopyMessage(data, page);
                    }
                );
            };

            /**
             *
             * @param result
             * @param page
             */
            self.parseCopyMessage = function (result, page) {

                result.page = page;

                $scope.copyMessages.count ++;

                if (result.code == 1) {
                    $scope.copyMessages.error.push(
                        result
                    );
                } else {
                    $scope.copyMessages.success.push(
                        result
                    );
                }

            };

            /**
             * parseMessage - Parse ans set standard error
             * @param result
             */
            self.parseMessage = function (result) {

                if (result.code == 1) {
                    $scope.errorMessage = $scope.errorMessage + ' ' + result.message;
                }
            };

            /**
             * getSourceSite
             */
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

            /**
             * getSourcePages
             */
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
                        self.setSourcePages(data.data);
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

            /**
             * Prepare Source Pages as array so they can be filtered
             * @param sourcePages
             */
            self.setSourcePages = function (sourcePages) {

                $scope.sourcePages = [];

                angular.forEach(
                    sourcePages,
                    function (page) {
                        $scope.sourcePages.push(page);
                    }
                );
            }

            /**
             * getDestinationSites
             */
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
                        data.code = 1;
                        data.message = 'An Error Occured: ' + data.message;
                        self.parseMessage(data);
                        $scope.loadings.destinationSites = false;
                    }
                );
            };

            /**
             * getPageTypes
             */
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
                        data.code = 1;
                        data.message = 'An Error Occured: ' + data.message;
                        self.parseMessage(data);
                        $scope.loadings.pageTypes = false;
                    }
                );
            };


            /**
             * init
             */
            self.init = function () {

                self.getSourceSite();

                self.getPageTypes();

                self.getDestinationSites();
            }

            self.init();
        }
    ]
)

    .filter(
    'rcmAdminPageTypeFilter',
    function () {

        var compareStrDirect = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return (stra == strb);
        }

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = [];

            angular.forEach(
                input, function (page) {
                    if (compareStrDirect(page.pageType, query)) {
                        result.push(page);
                    }
                }
            );

            return result;
        };
    }
)
    .filter(
    'rcmAdminPageNameFilter',
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
                    if (compareStr(page.name, query) || compareStr(
                            page.pageTitle,
                            query
                        )) {
                        result.push(page);
                    }
                }
            );

            return result;
        };
    }
);