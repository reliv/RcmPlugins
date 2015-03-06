/**
 * Created by bjanish on 3/6/15.
 */

var RcmAdminPageNotFound = {

    onPageLoad: function(page){

        var pageData = page.model.getData();

        console.log(pageData);

        if(pageData.name != pageData.requestedPageData.rcmPageName) {
            //console.log(page.model.getData());
            var actions = {close: function () {
                window.location = "/";
            }}
            var dialog = RcmDialog.buildDialog('rcm-page-not-found-123',"Page does not exist",'/rcm-admin/page/new?url=' + pageData.requestedPageData.rcmPageName +'','RcmFormDialog', actions);
            setTimeout(
                function(){
                    dialog.open();
                },
                500
            );

        }



        //console.log(dialog);
       //

        // get url from dom

        // If it does not, then show the create page dialog

        // else , nothing



    },

    init: function(){
        var page = RcmAdminService.getPage(
            function (page) {
                RcmAdminPageNotFound.onPageLoad(page);
            }
        );
    }
};

RcmAdminPageNotFound.init();

