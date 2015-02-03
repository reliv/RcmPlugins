/**
 * Created by idavis on 9/22/14.
 */
rcm.addAngularModule('pagePermissions');

angular.module('pagePermissions', ['rcmUserRoleSelector'])
    .directive(
    'rcmPagePermissions', [
        '$log', '$http', 'rcmUserRolesService',
        function ($log, $http, rcmUserRolesService) {
            var thisLink = function (scope, element, attrs) {

                var data = JSON.parse(attrs.rcmPagePermissionsData);

                // @todo this bypasses the regular service and could cause issues if someone else set them
                rcmUserRolesService.setRoles(data.roles);

                var valueNamespace = "pagePermissions";

                rcmUserRolesService.setSelectedRoles(valueNamespace, data.selectedRoles);

                rcmUser.eventManager.on(
                    'rcmUserRolesService.onRolesReady',
                    function(roles){
                        scope.roles = roles;
                        //scope.$apply();
                    }
                );

                self.setLockDisplay = function(){

                    var selectedRoles = rcmUserRolesService.getSelectedRoles(valueNamespace);

                    var hasRoles = rcmUserRolesService.hasSelectedRoles(valueNamespace);

                    var selectedAll = rcmUserRolesService.hasAllRoles(selectedRoles);

                    if (hasRoles && !selectedAll) {

                        $("#unlockPermissionsNonEdit").hide();
                        $("#lockPermissionsNonEdit").show();
                        $("#unlockPermissionsEditMode").hide();
                        $("#lockPermissionsEditMode").show();

                    } else if (selectedAll) {

                        $("#lockPermissionsNonEdit").hide();
                        $("#unlockPermissionsNonEdit").show();
                        $("#lockPermissionsEditMode").hide();
                        $("#unlockPermissionsEditMode").show();

                    } else {

                        $("#lockPermissionsNonEdit").hide();
                        $("#unlockPermissionsNonEdit").show();
                        $("#lockPermissionsEditMode").hide();
                        $("#unlockPermissionsEditMode").show();
                    }

                };

                scope.savePermissions = function () {

                    data.selectedRoles = rcmUserRolesService.getSelectedRoles(valueNamespace);

                    element.find("[rcm-page-permissions-data]").val(JSON.stringify(data));

                    $http(
                        {
                            method: 'PUT',
                            url: 'api/admin/page/permissions/' + data.pageName,
                            data: data
                        }
                    ).
                        success(
                        function (data, status, headers, config) {
                            self.setLockDisplay();
                        }
                    )
                        .error(
                        function (data, status, headers, config) {
                            jQuery().alert(
                                'Couldn\'t save list of permissions!',
                                null,
                                'An error occured while saving'
                            );
                        }
                    );
                }
            };

            return {link: thisLink}
        }

    ]
);