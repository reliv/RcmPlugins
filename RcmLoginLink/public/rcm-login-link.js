/**
 *
 * @param {int} instanceId
 * @constructor
 */
var RcmLoginLink = function (instanceId) {

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

    var loginLink = container.find('.loginLink');
    var logOutLink = container.find('.logOutLink');
    var loginButton = container.find('button.login');
    var processingButton = container.find('button.processing');
    var usernameInput = container.find('input.username');
    var passwordInput = container.find('input.password');
    var popup = container.find('.popup');

    me.init = function(){
        loginLink.click(me.loginLinkClick);
        loginButton.click(me.loginButtonClick);
        container.find('.loginForm').submit(function(){me.loginButtonClick});
    };

    me.showShowCorrectLink=function(isLoggedIn){
        if(isLoggedIn){
            loginLink.hide();
            logOutLink.show();
        }else{
            logOutLink.hide();
            loginLink.show();
        }
    };

    me.loginLinkClick=function(){
        popup.slideToggle('fast',function(){
            usernameInput.focus();
        });
    };

    me.loginButtonClick=function(e){
        loginButton.hide();
        processingButton.show();
        window['rcmLoginMgr'].doLogin(
            usernameInput.val(),
            passwordInput.val(),
            me.loginSuccessCallback,
            me.loginFailCallback
        );
        return false;//Prevent form submission
    };

    me.loginSuccessCallback = function(){
        me.showShowCorrectLink(true);
        me.hideProcessing();
        popup.slideUp('fast');
    };

    me.loginFailCallback = function(error){
        switch(error) {
            case 'invalid':
                me.hideErrors();
                container.find(".error.invalid").show();
                break;
            case 'missing':
                me.hideErrors();
                container.find(".error.missing").show();
                break;
            default://error probably == systemFailure
                me.hideErrors();
                container.find(".error.systemFailure").show();
        }
        passwordInput.val(null);
        me.hideProcessing();
    };

    me.hideProcessing = function(){
        processingButton.hide();
        loginButton.show();
    };

    me.hideErrors = function() {
        container.find('.error').hide();
    };

    me.init();

};