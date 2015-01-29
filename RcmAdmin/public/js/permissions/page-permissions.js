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

                rcmUser.eventManager.on(
                    'rcmUserRolesService.onSetRoles',
                    function(roles){
                        scope.roles = roles;
                        rcmUserRolesService.setSelectedRoles(valueNamespace, data.selectedRoles);
                    }
                );

                rcmUser.eventManager.on(
                    'rcmUserRolesService.onSetSelectedRole',
                    function(result){
                        self.setLockDisplay();
                    }
                );

                rcmUser.eventManager.on(
                    'rcmUserRolesService.onRemoveSelectedRole',
                    function(result){
                        self.setLockDisplay();
                    }
                );

                //preparing data to include only selected roles
                var prepareData = function () {
                    //getting read of ticked parameter and creating array of names only
                    var roles = [];
                    angular.forEach(
                        scope.selectedItems, function (value) {
                            roles.push(value['name']);
                        }
                    );
                    return {
                        siteId: data.siteId,
                        pageType: data.pageType,
                        pageName: data.pageName,
                        roles: roles,
                        selectedRoles: rcmUserRolesService.getSelectedRoles(valueNamespace)
                    }
                };

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
                    var newData = {
                        siteId: data.siteId,
                        pageType: data.pageType,
                        pageName: data.pageName,
                        selectedRoles: rcmUserRolesService.getSelectedRoles(valueNamespace)
                    };

                    $http(
                        {
                            method: 'PUT',
                            url: 'api/admin/page/permissions/' + newData.pageName,
                            data: newData
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