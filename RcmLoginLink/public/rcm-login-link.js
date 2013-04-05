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

    var logInLink = container.find('.logInLink');
    var logOutLink = container.find('.logOutLink');

    me.init = function(){
        logInLink.click(me.logInLinkClick);
    };

    me.showShowCorrectLink=function(isLoggedIn){
        if(isLoggedIn){
            logInLink.hide();
            logOutLink.show();
        }else{
            logOutLink.hide();
            logInLink.show();
        }
    };

    me.logInLinkClick=function(){
        container.find('.popup').slideToggle('fast',function(){
            container.find('.usernameInput').focus();
        });
    };

    me.init();

};