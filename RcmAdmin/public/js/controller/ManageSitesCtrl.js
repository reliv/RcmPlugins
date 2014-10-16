angular.module('rcmAdmin').controller(
    'rcmManageSites',
    function ($scope, $log, $http) {
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
                        url: '/api/admin/sites/' + site.siteId + '/' + site.active
                    }).
                        success(function (data) {
                            //alert(data);
                            $scope.sites = data;
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