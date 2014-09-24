/**
 * Created by idavis on 9/22/14.
 */
rcm.addAngularModule('pagePermissions');

angular.module('pagePermissions', [])
    .directive('rcmPagePermissions', ['$log', '$http',
        function ($log, $http) {

            var thisLink = function (scope, element, attrs) {
                //   scope.data = JSON.parse(attrs.rcmPagePermissions);

               var rolesSelected = element.find('select option:selected').text();

                scope.selected = [
                    {
                        siteId: attrs.rcmPagePermissionsSiteId,
                        pageType: attrs.rcmPagePermissionsPageType,
                        pageName: attrs.rcmPagePermissionsPageName,
                        roles: rolesSelected
                    }
                ];
                scope.savePermissions = function () {

                    $http({
                        method: 'PUT',
                        url: 'api/admin/page/permissions/' + attrs.rcmPagePermissionsPageName,
                        data: scope.selected
                    }).
                        success(function (data, status, headers, config) {
                        })
                        .error(function (data, status, headers, config) {
                           alert('Couldn\'t save list of permissions');
                        });
                }
            };


            return {link: thisLink}
        }

    ]);