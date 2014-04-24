/**
 *
 * @param {int} instanceId
 * @constructor
 */
var RcmGoogleSearchBox = function (instanceId) {

    /**
     * jQuery container div that wraps this plugin
     * @type {jQuery}
     */
    var container = $('div[data-rcmPluginInstanceId=' + instanceId + ']');

    container.find("form").submit(
        function () {
            var qq = container.find('input[name=qq]').val();

            // Get domain of site we're searching
            var domain = location.host;

            // Add 'site:' in front of domain (ex site:www.reliv.com)
            var sitePlusDomain = " site:" + domain;

            // Combine everything together
            var finalValue = qq + sitePlusDomain;

            // store final search string in form variable
            var q = container.find('input[name=q]').val(finalValue);
        }
    );


};