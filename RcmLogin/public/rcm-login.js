var RcmLogin = function (instanceId, buttonProcessing) {
    /**
     * Plugin container div jQuery object
     * @type {Object}
     */
    var container = rcm.getPluginContainer(instanceId);

    /**
     * @type {ZendFormHelper}
     */
    var formHelper = new ZendFormHelper(container);

    container.find('form').submit(function () {
        formHelper.showProcessing(buttonProcessing);
    });
};

