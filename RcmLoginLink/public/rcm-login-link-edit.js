var RcmLoginLinkEdit = function (instanceId, container) {
    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmLoginLink}
     */
    var me = this;

    var loginLink = container.find('.loginLink');
    var logOutLink = container.find('.logOutLink');
    var popup = container.find('.popup');

    /**
     *
     * @type {RcmLoginLink}
     */
    var rcmLoginLink = window['RcmLoginLink'][instanceId];

    me.initEdit = function(){

        //Allow labels to be clicked
        container.find('label').attr('for',null);

        //Disable buttons
        container.find('button').unbind();
        container.find('button').click(function(){return false;});

        loginLink.click(rcmLoginLink.loginLinkClick);
        rcmLoginLink.showShowCorrectLink(false);

        //Context menu for editing "login" vs "logout"
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId),

            items:{
                login:{
                    name:'Show "Login" for translation',
                    icon:'edit',
                    callback:function () {
                        rcmLoginLink.showShowCorrectLink(false);
                    }
                },
                logout:{
                    name:'Show "Logout" for translation',
                    icon:'edit',
                    callback:function () {
                        rcmLoginLink.showShowCorrectLink(true);
                    }
                }
            }
        });
    };

    me.getSaveData = function () {
        return {};
    };
};