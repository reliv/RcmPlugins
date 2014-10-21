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
                    if (site.active == 'A') {
                        site.active = 'D';
                    } else {
                        site.active = 'A';
                    }
                    $http({
                        method: 'PUT',
                        url: '/api/admin/sites/' + site.siteId, //+ '/' + site.active
                        data: site
                    }).
                        success(function (data) {
                            $scope.sites = data;
                            //Refresh site list
                            self.getSites();
                        });
                }
            )
        };
        self.getSites = function () {
            $http({
                method: 'GET',
                url: '/api/admin/sites'
            }).
                success(function (data) {
                    $scope.sites = data;
                });

        };
        self.getSites();
    }
);
