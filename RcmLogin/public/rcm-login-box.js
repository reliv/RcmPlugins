var RcmLoginBox = function(instanceId, sessionId) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmLoginLink}
     */
    var me = this;

    /**
     * Plugin container div jQuery object
     * @type {jQuery}
     */
    var container = rcm.getPluginContainer(instanceId);

    var loginButton;
    me.sessionId = sessionId;

    me.init = function() {

        loginButton = container.find('button.login');

        $(function(){
            container.find('form').submit(function(event){
                me.login();
                event.preventDefault();
            });

            loginButton.click(function(event){
                me.login();
                event.preventDefault();
            });

            if (typeof(rcm) === 'object') {
                var urlParams = rcm.getUrlParams();

                if (urlParams['rcmLoginError']) {
                    window['rcmLoginMgr'].processError(
                        urlParams['rcmLoginError'],
                        me.handleLoginFail
                    );
                }
            }
        });
    };

    me.loginSuccessCallback = function(redirectUrl){

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

    me.handleLoginFail = function(error){
        switch(error) {
            case 'invalid':
                me.hideErrors();
                $("#rcmLoginBoxInvalidError").show();
                me.hideProcessing();
                break;
            case 'missing':
                me.hideErrors();
                $("#rcmLoginBoxMissingError").show();
                me.hideProcessing();
                break;
            default://error probably == systemFailure
                me.hideErrors();
                $("#rcmLoginBoxSystemError").show();
                me.hideProcessing();
        }
    };

    me.login = function() {
        me.showProcessing();
        window['rcmLoginMgr'].doLogin(
            container.find('input.username').val(),
            container.find('input.password').val(),
            me.handleLoginFail
        );
    };

    me.hideErrors = function() {
        $("#rcmLoginBoxInvalidError").hide();
        $("#rcmLoginBoxMissingError").hide();
        $("#rcmLoginBoxSystemError").hide();
    };

    me.showProcessing = function(){
        loginButton.append(
            '<img class="processingSpinner" ' +
                'src="/modules/rcm/images/busy-spinner-16x16.gif" ' +
                'width="16" ' +
                'height="16">'
        );
        loginButton.addClass('disabled');

    };

    me.hideProcessing = function(){
        container.find('.processingSpinner').remove();
        loginButton.removeClass('disabled');
    };

    me.init();
};

