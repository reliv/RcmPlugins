rcmShowPermissions = function (selectedRoles, onOkCallback)
{
    var nameSpace = $.generateUUID();
    var roleForm = $('<div rcm-user-role-selector="' +
        nameSpace +
        '" rcm-user-role-selector-id-property="namespace" ' +
        'rcm-user-role-selector-title-property="roleId" ' +
        'rcm-user-role-selector-show-nesting="-" ' +
        'rcm-user-role-selector-search-label="Search" ' +
        'rcm-user-role-selector-search-placeholder="Search...">' +
        '</div>'
    ).addClass('simple').dialog({
            title: 'Properties',
            modal: true,
            width: 620,
            buttons: {
                Cancel: function () {
                    $(this).dialog("close");
                },
                Ok: function() {
                    selectedRoles = rcmUser.rcmUserRolesService.service.getSelectedRoles(nameSpace);
                    var roles = [];
                    jQuery.each(selectedRoles, function(roleIndex, roleValue) {
                        roles.push(roleIndex);
                    });


                    console.log(roles);

                    if (typeof onOkCallback == "function") {
                        onOkCallback(roles);
                    }
                }
            }
        });

    angular.element(roleForm).injector().invoke(
        function ($compile) {
            var scope = angular.element(roleForm).scope();
            $compile(roleForm)(scope);
        }
    );
};