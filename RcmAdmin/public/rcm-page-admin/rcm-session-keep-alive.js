/**
 * Created by idavis on 9/10/14.
 */

(function () {
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
                        var timestamp = Math.floor(new Date().getTime() / 1000);
                        $.post(
                            '/api/rpc/rcm-admin/keep-alive',
                            {'requestTime': timestamp},
                            function(data){
                                //console.log('keep-alive',data);
                            }
                        );
                    }, 300000
                );
            }
        }
    );
})();