/**
 * Created by idavis on 9/22/14.
 */
rcm.addAngularModule('pagePermissions');

angular.module('pagePermissions', ['multi-select'])
    .directive('rcmPagePermissions', ['$log', '$http',
        function ($log, $http) {
            var thisLink = function (scope, element, attrs) {
                var data = JSON.parse(attrs.rcmPagePermissionsData);
                scope.roles = data.roles;
                setTimeout(function(){  $('.multiSelectButton').trigger('click');}, 1);
                $('.multiSelectButton').hide();
                 //preparing data to include only selected roles
                var prepareData = function () {
                    //getting read of ticked parameter and creating array of names only
                    var roles = [];
                    angular.forEach(scope.selectedItems, function (value) {
                        roles.push(value['name']);
                    });
                    return {
                        siteId: data.siteId,
                        pageType: data.pageType,
                        pageName: data.pageName,
                        roles: roles
                    }

                };

                scope.savePermissions = function () {
                    var newData = prepareData();
                    var page = RcmAdminService.getPage();
                    $http({
                        method: 'PUT',
                        url: 'api/admin/page/permissions/' + newData.pageName,
                        data: newData
                    }).
                        success(function (data, status, headers, config) {
                            if(newData.roles.length > 0) {
                                $("#unlockPermissionsNonEdit").hide();
                                $("#lockPermissionsNonEdit").show();
                                $("#unlockPermissionsEditMode").hide();
                                $("#lockPermissionsEditMode").show();
                            } else {
                                $("#lockPermissionsNonEdit").hide();
                                $("#unlockPermissionsNonEdit").show();
                                $("#lockPermissionsEditMode").hide();
                                $("#unlockPermissionsEditMode").show();
                            }
                        })
                        .error(function (data, status, headers, config) {
                            jQuery().alert(
                                'Couldn\'t save list of permissions!',
                                null,
                                'An error occured while saving'
                            );
                        });
                }
            };

            return {link: thisLink}
        }

    ]);