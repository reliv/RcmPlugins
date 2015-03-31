RcmAdminService.editModeCheck = function () {

    var method = function (page) {
        if (page.editMode) {

            //ajax call to canEdit service
            $.ajax({
                url: '/api/rpc/rcm-admin/can-edit',
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    var editable = data.data;
                    if (!editable.canEdit) {
                        bootbox.dialog({
                            size: 'small',
                            title: '<h1>Session Timed Out</h1>',
                            closeButton: false,
                            message: 'Your session has timed out. Please log in again before editing.',
                            buttons: {
                                success: {
                                    label: 'Ok',
                                    callback: function (result) {
                                        $.ajax({
                                            url: '/api/admin/current-site',
                                            type: 'get',
                                            dataType: 'json',
                                            success: function (data) {
                                                var login = data.data;
                                                if (result) {
                                                    window.location = login.loginPage + '?redirect=' + window.location.pathname;
                                                }
                                            }
                                        });

                                    }

                                }
                            }

                        })
                    }

                }
            });
        }
    };
    var callback = function (page) {
        page.events.on('editingStateChange', method);
    };
    RcmAdminService.getPage(callback);

};

RcmAdminService.editModeCheck();
