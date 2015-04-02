/**
 * Created by bjanish on 3/6/15.
 */

RcmAdminService.rcmAdminPageNotFound = {

    onEditChange: function(page){

        var pageData = page.model.getData();

        if(page.editMode) {
            if (pageData.name != pageData.requestedPageData.rcmPageName) {
                var actions = {
                    close: function () {
                        window.location = "/";
                    }
                };
                var dialog = RcmDialog.buildDialog('rcm-page-not-found-123', "Page does not exist. Create a new one?", '/rcm-admin/page/new?url=' + pageData.requestedPageData.rcmPageName + '', 'RcmFormDialog', actions);
                dialog.params.saveLabel = "Create new page";
                dialog.params.closeLabel = "Cancel";
                setTimeout(
                    function () {
                        dialog.open();
                    },
                    500
                );
            }
        }
    },

    init: function(){
        var page = RcmAdminService.getPage(
            function (page) {
                page.events.on(
                    'editingStateChange', RcmAdminService.rcmAdminPageNotFound.onEditChange
                );
            }
        );
    }
};

RcmAdminService.rcmAdminPageNotFound.init();