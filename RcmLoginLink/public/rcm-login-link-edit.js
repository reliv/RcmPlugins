var RcmLoginLinkEdit = function (instanceId, container) {
    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmLoginLink}
     */
    var me = this;

    var loginLink = container.find('.loginLink');
    var logOutLink = container.find('.logOutLink');
    var loginButton = container.find('button.login');
    var processingButton = container.find('button.processing');
    var usernameInput = container.find('input.username');
    var passwordInput = container.find('input.password');
    var popup = container.find('.popup');

    /**
     *
     * @type {RcmLoginLink}
     */
    var rcmLoginLink = window['RcmLoginLink'][instanceId];

    me.initEdit = function(){
        container.find('label').attr('for',null);//Allow labels to be clicked
        rcmLoginLink.setEditMode(true);
        container.find('.error').show();
        loginLink.click(rcmLoginLink.loginLinkClick);
        rcmLoginLink.showShowCorrectLink(false);
    };

    me.getSaveData = function () {
        return {};
    };
};