var RcmLoginBox = function(instanceId, errors) {

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

    var errorDiv = container.find('div.error');

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

    me.handleLoginFail = function(error){
        errorDiv.html(errors[error]);
        errorDiv.show();
        me.hideProcessing();
    };

    me.login = function() {
        me.showProcessing();
        window['rcmLoginMgr'].doLogin(
            container.find('input.username').val(),
            container.find('input.password').val(),
            me.handleLoginFail
        );
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

