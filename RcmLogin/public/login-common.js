var RcmLoginMgr = function(loginUrl) {

    var me = this

    me.loginUrl = loginUrl;

    me.successCallback = null;

    me.missingCallback = null;

    me.invalidCallback = null;

    me.systemFailureCallBack = null;

    me.noAuthCallback = null;

    me.doLogin = function(username, password) {

        var data = {
            username : username,
            password :  password
        };

        $.ajax({
            type: 'POST',
            url : me.loginUrl,
            cache : false,
            data : data,
            dataType: "json",
            success : me.processResponse,
            error : me.doSystemFailure
        });
    };

    me.processResponse = function(data) {
        if(!data.dataOk) {
            if (!data.error) {
                me.doSystemFailure(data);
                return
            }

            me.processError(data.error, data);
            return;
        }

        me.doSuccess(data);
    };

    me.processError = function(error, data) {
        switch(error) {
            case 'missingNeeded':
                me.doMissing(data);
                break;
            case 'invalid':
                me.doInvalid(data);
                break;
            case 'noAuth':
                me.doNoAuth(data);
                break;
            default:
                me.doSystemFailure(data);
        }
    };

    me.doSuccess = function (data) {
        if (typeof(me.successCallback) === 'function') {
            me.successCallback.call(this, data);
        }
    };

    me.doMissing = function (data) {
        if (typeof(me.missingCallback) === 'function') {
            me.missingCallback.call(this, data);
        }
    };

    me.doNoAuth = function (data) {
        if (typeof(me.noAuthCallback) === 'function') {
            me.noAuthCallback.call(this, data);
        }
    };

    me.doSystemFailure = function(data) {
        if (typeof(me.systemFailureCallBack) === 'function') {
            me.systemFailureCallBack.call(this, data);
        }
    };

    me.setSuccessCallback = function(successCallback) {
        me.successCallback = successCallback;
    };

    me.setMissingCallback = function(missingCallback) {
        me.missingCallback = missingCallback;
    };

    me.setInvalidCallback = function(invalidCallback) {
        me.invalidCallback = invalidCallback;
    };

    me.setSystemFailureCallback = function(systemFailureCallback) {
        me.systemFailureCallBack = systemFailureCallback;
    };

    me.setNoAuthFailureCallback = function(noAuthCallback) {
        me.noAuthCallback = noAuthCallback;
    };
};