/**
 * Created by idavis on 9/10/14.
 */

    (function() {
        var page = RcmAdminService.getPage();
        var sessionKeepAlive = false;
        page.events.on(
            'editingStateChange',
            function (page) {
                if (page.editMode === true && !sessionKeepAlive) {
                    //preventing from session keep alive on multiple pages
                    sessionKeepAlive = true;
                    setInterval(
                        function () {
                            $.get('/rcm-page-search/title');
                }, 300000
                    );
                }
            }
        );
    })();