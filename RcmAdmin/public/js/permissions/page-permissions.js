/**
 * Created by idavis on 9/22/14.
 */
rcm.addAngularModule('pagePermissions');

angular.module('pagePermissions',[])
    .directive('rcmPagePermissions', ['$log', '$http',
        function ($log, $http) {

            var thisLink = function (scope, element, attrs) {
                scope.initialRolesData = JSON.parse(attrs.rcmPagePermissionsData);
                console.log(scope.initialRolesData);
              // var rolesSelected = element.find('select option:selected').text();

//                scope.selected = [];
//                    console.log(scope.selected);
//                    var permissions = [
//                        {
//                            siteId: attrs.rcmPagePermissionsSiteId,
//                            pageType: attrs.rcmPagePermissionsPageType,
//                            pageName: attrs.rcmPagePermissionsPageName,
//                            roles: scope.selected
//                        }
//                    ];

                scope.savePermissions = function () {

                    $http({
                        method: 'PUT',
                        url: 'api/admin/page/permissions/' + attrs.rcmPagePermissionsPageName,
                        data: 'hi'
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