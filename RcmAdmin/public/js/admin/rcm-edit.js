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
}

var rcmEdit = new RcmEdit();
/* </RcmEdit> */