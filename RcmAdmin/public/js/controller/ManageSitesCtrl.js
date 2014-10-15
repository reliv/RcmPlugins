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
                    //self.getSites();
                    //console.log(site.active);
                    if (site.active == 'A') {
                        site.active = 'D';
                    } else {
                        site.active = 'A';
                    }
                    console.log(RcmAdminService.RcmPageModel.getData());
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
//            $scope.loading = true;
            $http({
                method: 'GET',
                url: '/api/admin/sites'
            }).
                success(function (data) {
                    $scope.sites = data;
//                    $scope.loading = false;
                });//).
//                error(function () ee{
//                    $scope.loading = false;
//                });
        };
        self.getSites();
    }
);