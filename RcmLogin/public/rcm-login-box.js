var RcmLoginBox = function(sessionId) {

    var me = this;

    me.sessionId = sessionId;

    me.init = function() {

        $(function(){
            $("#rcmLoginBoxLoginForm").submit(function(event){
                me.submitBind();
                event.preventDefault();
            });

            $("#rcmLoginBoxLoginSubmit").click(function(event){
                me.submitBind();
                event.preventDefault();
            });

            if (typeof(rcm) === 'object') {
                var urlParams = rcm.getUrlParams();

                if (urlParams.rcmLoginError) {
                    window['rcmLoginMgr'].processError(urlParams.rcmLoginError);
                }
            }
        });
    };

    me.loginSuccessCallback = function(){
        var redirectUrl = '';

        if (typeof(rcm) === 'object') {
            var urlParams = rcm.getUrlParams();

            if (urlParams.redirect) {
                redirectUrl = urlParams.redirect
            }
        }

        if (redirectUrl == '') {
            redirectUrl ='https://portal.local.reliv.com';
        }

        var redirectWithSession = rcm.updateURLParameter(redirectUrl,'sess_id', me.sessionId);

        window.location.replace(redirectWithSession);
    };

    me.loginFailCallback = function(error){
        switch(error) {
            case 'invalid':
                me.hideErrors();
                $("#rcmLoginBoxInvalidError").show();
                me.showSubmitButton();
                break;
            case 'missing':
                me.hideErrors();
                $("#rcmLoginBoxMissingError").show();
                me.showSubmitButton();
                break;
            default://error probably == systemFailure
                me.hideErrors();
                $("#rcmLoginBoxSystemError").show();
                me.showSubmitButton();
        }
    };

    me.submitBind = function() {
        me.hideSubmitButton();
        window['rcmLoginMgr'].doLogin(
            $("#rcmLoginBoxUserName").val(),
            $("#rcmLoginBoxPassword").val(),
            me.loginSuccessCallback,
            me.loginFailCallback
        );
    };

    me.hideSubmitButton = function() {
        $("#rcmLoginBoxLoginSubmit").hide();
        $("#rcmLoginBoxProcessingMsgContainer").show();
    };

    me.showSubmitButton = function() {
        $("#rcmLoginBoxLoginSubmit").show();
        $("#rcmLoginBoxProcessingMsgContainer").hide();
    };

    me.hideErrors = function() {
        $("#rcmLoginBoxInvalidError").hide();
        $("#rcmLoginBoxMissingError").hide();
        $("#rcmLoginBoxSystemError").hide();
    };

    me.init();
};

