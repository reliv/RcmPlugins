angular.module('rcmAdmin').controller(
    'rcmManageSites',
    function ($scope, $log, $http) {
        var siteData = RcmAdminService.RcmPageModel.getData();
        $scope.currentSiteId = siteData.siteId;
        var self = this;
        $scope.sites = [];

        $scope.disableSite = function (site) {
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
                            url: '/api/admin/sites/' + site.siteId,
                            data: site
                        }
                    ).
                        success(
                        function (data) {
                            $scope.sites = data;
                            //Refresh site list
                            self.getSites();
                        }
                    );
                }
            )
        };

        $scope.cloneSite = function (site) {
            $().confirm(
                'Duplicate this site?<br><br>' +
                '<ul>' +
                '<li>Site Id: ' + site.siteId + '</li>' +
                '<li>Domain: ' + site.domain + '</li>' +
                '</ul>',
                function () {
                    $http(
                        {
                            method: 'POST',
                            url: '/api/admin/sites/' + site.siteId,
                            data: site
                        }
                    ).
                        success(
                        function (data) {
                            console.log(data);
                            //Refresh site list
                            self.getSites();
                        }
                    );
                }
            )
        };

        self.getSites = function () {
            $http(
                {
                    method: 'GET',
                    url: '/api/admin/sites'
                }
            ).
                success(
                function (data) {
                    $scope.sites = data;
                    console.log(data);
                }
            );

        };
        self.getSites();
    }
);
