angular.module('rcmAdmin').controller(
    'rcmManageSites',
    function ($scope, $log, $http) {
        var self = this;
        $scope.sites = [];
//        $scope.loading = false;
        $scope.disableSite = function (site) {
            $().confirm(
                'Disable this site?<br><br>' +
                    '<ul>' +
                    '<li>Site Id: ' + site.siteId + '</li>' +
                    '<li>Domain: ' + site.domain + '</li>' +
                    '</ul>',
                function () {
//                    $scope.loading = true;
                    //Refresh site list
                    self.getSites();
                }
            )
        };
        self.getSites = function () {
//            $scope.loading = true;
            $http({method: 'GET', url: '/api/admin/sites'}).
                success(function (data) {
                    $scope.sites = data;
//                    $scope.loading = false;
                });//).
//                error(function () {
//                    $scope.loading = false;
//                });
        };
        self.getSites();
    }
);