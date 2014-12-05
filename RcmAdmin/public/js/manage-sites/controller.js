angular.module('rcmAdmin').controller(
    'rcmManageSitesController',
    function ($scope, $log, $http) {
        var siteData = RcmAdminService.RcmPageModel.getData();
        $scope.currentSiteId = siteData.siteId;
        var self = this;
        $scope.sites = [];

        $scope.loading = false;
        $scope.loadings = {};
        $scope.tempSites = {};

        $scope.message = '';

        $scope.disableSite = function (site) {
            $scope.loadings[site.siteId] = true;
            $().confirm(
                'Disable this site?<br><br>' +
                '<ul>' +
                '<li>Site Id: ' + site.siteId + '</li>' +
                '<li>Domain: ' + site.domain + '</li>' +
                '</ul>',
                function () {
                    if (site.status == 'A') {
                        site.status = 'D';
                    } else {
                        site.status = 'A';
                    }
                    $http(
                        {
                            method: 'PUT',
                            url: '/api/admin/manage-sites/' + site.siteId,
                            data: site
                        }
                    )
                        .success(
                        function (data) {
                            //Refresh site list
                            self.getSites();
                            $scope.loadings[site.siteId] = false;
                        }
                    )
                        .error(
                        function (data) {
                            $scope.loadings[site.siteId] = false;
                        }
                    );
                }
            )
        };

        $scope.showClone = function (site) {

            $scope.tempSites[site.siteId] = angular.copy(site, {});
        };

        $scope.hideClone = function (site) {

            $scope.tempSites[site.siteId] = null;
        };

        $scope.hideCloneComplete = function (site) {

            $scope.tempSites[site.siteId] = null;
            self.getSites();
        };

        $scope.cloneSite = function (site) {
            $scope.loadings[site.siteId] = true;
            $().confirm(
                '<div class="confirm">' +
                '<h2>Duplicate site ' + site.siteId + '?</h2>' +
                '<div><span>New Domain: </span>' + $scope.tempSites[site.siteId].domain + '</div>' +
                '</div>',
                function () {
                    $http(
                        {
                            method: 'POST',
                            url: '/api/admin/manage-sites/' + $scope.tempSites[site.siteId].siteId,
                            data: $scope.tempSites[site.siteId]
                        }
                    )
                        .success(
                        function (data) {
                            $scope.tempSites[site.siteId] = data.data;
                            $scope.tempSites[site.siteId]['code'] = data.code;
                            $scope.tempSites[site.siteId]['message'] = data.message;
                            $scope.loadings[site.siteId] = false;
                        }
                    )
                        .error(
                        function (data) {
                            $scope.tempSites[site.siteId] = data.data;
                            $scope.tempSites[site.siteId]['code'] = data.code;
                            $scope.tempSites[site.siteId]['message'] = data.message;
                            $scope.loadings[site.siteId] = false;
                        }
                    );
                }
            )
        };

        self.getSites = function () {
            $scope.loading = true;
            $http(
                {
                    method: 'GET',
                    url: '/api/admin/manage-sites'
                }
            )
                .success(
                function (data) {
                    $scope.sites = data.data;
                    $scope.loading = false;
                }
            )
                .error(
                function (data) {
                    $scope.message = 'An error occured while loading sites.'
                    $scope.loading = false;
                }
            );

        };
        self.getSites();
    }
);
