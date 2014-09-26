/**
 * <RcmEdit>
 * Main Javascript file for the content manager.
 */
function RcmEdit(config) {

    var me = this;

    /**
     * Makes a text input autocomplete page names
     * @param {jQuery} inputEle
     */
    me.attachPageListAutoComplete = function (inputEle) {
        $.getJSON('/rcm-page-search/title', function (data) {
            var pageUrls = [];
            $.each(data, function (pageUrl) {
                pageUrls.push(pageUrl);
            });
            inputEle.autocomplete(
                {
                    source: pageUrls,
                    minLength: 0
                }
            );
        });
    };

    me.saveAjaxAdminWindow = function (saveUrl, send, formContainer, dataOkHeadline, dataOkMessage, keepOpen, successCallback) {
        $.getJSON(saveUrl,
                  send,
                  function (data) {
                      me.saveAjaxAdminWindowSuccess(data, formContainer, dataOkHeadline, dataOkMessage, keepOpen, successCallback)
                  }
        ).error(function () {
                    me.saveAjaxAdminWindowSuccessError(formContainer);
                });
    };

    me.saveAjaxAdminWindowUsingPost = function (saveUrl, send, formContainer, dataOkHeadline, dataOkMessage, keepOpen, successCallback) {
        $.post(saveUrl,
               send,
               function (data) {
                   me.saveAjaxAdminWindowSuccess(data, formContainer, dataOkHeadline, dataOkMessage, keepOpen, successCallback)
               },
               'json'
        ).error(function () {
                    me.saveAjaxAdminWindowSuccessError(formContainer);
                });
    };

    me.saveAjaxAdminWindowSuccess = function (data, formContainer, dataOkHeadline, dataOkMessage, keepOpen, successCallback) {
        if (data.dataOk == 'Y' && data.redirect == undefined) {
            //Close Window unless told not to
            if (keepOpen !== true) {
                $(formContainer).parent().dialog("close");
            } else {
                $(formContainer).find(".ajaxFormErrorLine").html('').hide();
            }

            //Show Status Message if passed in
            if (dataOkHeadline && dataOkMessage) {
                $.growlUI(dataOkHeadline, dataOkMessage);
            }

            //Process sucessCallback if passed in
            if (typeof successCallback === 'function') {
                successCallback(data);
            }
        } else if (data.dataOk == 'Y' && data.redirect) {
            window.location = data.redirect;
        } else if (data.dataOk != 'Y' && data.error != '') {
            $(formContainer).find(".ajaxFormErrorLine").html('<br /><p style="color: #FF0000;">' + data.error + '</p><br />').show();
            $(formContainer).parent().scrollTop(0);
        } else {
            $(formContainer).find(".ajaxFormErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
            $(formContainer).parent().scrollTop(0);
        }

    };

    me.saveAjaxAdminWindowSuccessError = function (formContainer) {
        $(formContainer).find(".ajaxFormErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
        $(formContainer).parent().scrollTop(0);
    };
}

var rcmEdit = new RcmEdit();
/* </RcmEdit> */