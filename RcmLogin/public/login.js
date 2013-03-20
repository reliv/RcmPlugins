var RcmLoginBoxJs = function(loginMgr) {

    var me = this;

    me.loginMgr = loginMgr;

    me.init = function() {

        me.loginMgr.setSuccessCallback(me.rcmLoginBoxSuccessFunction);
        me.loginMgr.setMissingCallback(me.rcmLoginBoxMissingItemsCallback);
        me.loginMgr.setInvalidCallback(me.rcmLoginBoxInvalidLoginCallback);
        me.loginMgr.setSystemFailureCallback(me.rcmLoginBoxSystemFailureCallback);

        $(function(){
            $("#loginSubmit").bind('click', function(event){
                me.clickBind()
                event.preventDefault();
            });

            if (typeof(rcm) === 'object') {
                var urlParms = rcm.getUrlParams();

                if (urlParms.rcmLoginError) {
                    me.loginMgr.processError(urlParms.rcmLoginError);
                }
            }
        });
    };

    me.clickBind = function() {
        me.loginMgr.doLogin($("#rcmLoginBoxUserName").val(), $("#rcmLoginBoxPassword").val());
    };

    me.rcmLoginBoxSuccessFunction = function(data) {
        window.location.replace('/');
    };

    me.rcmLoginBoxInvalidLoginCallback = function(data) {
        console.log('invalid called');
    };

    me.rcmLoginBoxMissingItemsCallback = function(data) {
        console.log('missing called');
    };

    me.rcmLoginBoxSystemFailureCallback = function(data) {
        console.log('system failure called');
    }
};

