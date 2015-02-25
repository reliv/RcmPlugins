/**
 * RcmAdminModel
 * @constructor
 */
var RcmAdminModel = function () {

    var self = this;

    /**
     * RcmPageModel
     */
    self.RcmPageModel = {

        /**
         * getDocument
         * @param onComplete
         * @returns {*}
         */
        getDocument: function (onComplete) {

            var doc = jQuery(document);

            if (typeof onComplete === 'function') {
                onComplete(doc)
            }

            return doc;
        },

        /**
         * getElm
         * @param onComplete
         * @returns {*}
         */
        getElm: function (onComplete) {

            var elm = jQuery('body');

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * getData
         * @param onComplete
         * @returns {{}}
         */
        getData: function (onComplete) {

            var pageInfo = JSON.parse(jQuery('meta[property="rcm:page"]').attr('content'));

            var data = {};
            data.title = jQuery(document).find("head > title").text();
            //data.url = jQuery(location).attr('href');
            //data.path = jQuery(location).attr('pathname');
            data.description = jQuery('meta[name="description"]').attr('content');
            data.keywords = jQuery('meta[name="keywords"]').attr('content');

            data.name = pageInfo.rcmPageName;
            data.type = pageInfo.rcmPageType;
            data.revision = pageInfo.rcmPageRevision;
            data.siteId = pageInfo.rcmSiteId;

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    };

    /**
     * RcmContainerModel
     */
    self.RcmContainerModel = {

        /**
         * getElms
         * @param onComplete
         * @returns {*}
         */
        getElms: function (onComplete) {

            var pageElm = self.RcmPageModel.getElm();

            var elms = pageElm.find('[data-containerId]');

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },

        /**
         * getElm
         * @param containerId
         * @param onComplete
         * @returns {*}
         */
        getElm: function (containerId, onComplete) {

            var pageElm = self.RcmPageModel.getElm();

            var elm = pageElm.find("[data-containerId='" + containerId + "']");

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * getId
         * @param containerElm
         * @param onComplete
         * @returns {*}
         */
        getId: function (containerElm, onComplete) {

            var id = containerElm.attr('data-containerId');

            if (typeof onComplete === 'function') {
                onComplete(id)
            }

            return id;
        },

        /**
         * getData
         * @param containerId
         * @param onComplete
         * @returns {{}}
         */
        getData: function (containerId, onComplete) {

            var data = {};

            var elm = self.RcmContainerModel.getElm(containerId);

            data.id = containerId;

            data.revision = elm.attr('data-containerRevision');

            if (elm.attr('data-isPageContainer') == 'Y') {
                data.type = 'page';
            } else {
                data.type = 'layout';
            }

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    };

    /**
     * RcmPluginModel
     */
    self.RcmPluginModel = {

        /**
         * getPluginContainerSelector
         * @param pluginId
         * @returns {string}
         */
        getPluginContainerSelector: function (pluginId) {

            return ('[data-rcmPluginInstanceId="' + pluginId + '"] .rcmPluginContainer');
        },

        /**
         * getElms
         * @param containerId
         * @param onComplete
         * @returns {*}
         */
        getElms: function (containerId, onComplete) {

            var containerElm = self.RcmContainerModel.getElm(containerId);

            var elms = containerElm.find('[data-rcmPluginInstanceId]');

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },

        /**
         * getElm
         * @param containerId
         * @param pluginId
         * @param onComplete
         * @returns {*}
         */
        getElm: function (containerId, pluginId, onComplete) {

            var containerElm = self.RcmContainerModel.getElm(containerId);

            var elm = containerElm.find('[data-rcmPluginInstanceId="' + pluginId + '"]');

            elm = jQuery(elm[0]);

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * deleteElm
         * @param containerId
         * @param pluginId
         * @param onComplete
         * @returns {*}
         */
        deleteElm: function (containerId, pluginId, onComplete) {

            var elm = self.RcmPluginModel.getElm(containerId, pluginId);

            elm.remove();

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * getId
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getId: function (pluginElm, onComplete) {

            var id = pluginElm.attr('data-rcmPluginInstanceId');

            if (typeof onComplete === 'function') {
                onComplete(id)
            }

            return id;
        },

        /**
         * getName
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getName: function (pluginElm, onComplete) {

            var name = pluginElm.attr('data-rcmPluginName');

            if (typeof onComplete === 'function') {
                onComplete(name)
            }

            return name;
        },
        /**
         * getData
         * @param containerId
         * @param id
         * @param onComplete
         * @returns {{}}
         */
        getData: function (containerId, id, onComplete) {

            var data = {};

            var elm = self.RcmPluginModel.getElm(containerId, id);

            data.containerId = containerId;

            data.instanceId = elm.attr('data-rcmPluginInstanceId');

            data.isSitewide = (elm.attr('data-rcmSiteWidePlugin') == '1' || elm.attr('data-rcmSiteWidePlugin') == 'Y');
            data.name = elm.attr('data-rcmPluginName');

            data.sitewideName = elm.attr('data-rcmPluginDisplayName');

            data.columnClass = elm.attr('data-rcmPluginColumnClass');

            data.rowNumber = elm.attr('data-rcmPluginRowNumber');

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        },
        /**
         * getPluginContainer
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getPluginContainer: function (pluginElm, onComplete) {

            var pluginContainerElm = pluginElm.find('.rcmPluginContainer');

            if (typeof onComplete === 'function') {
                onComplete(pluginContainerElm)
            }

            return pluginContainerElm;
        },
        /**
         * getEditorElms
         * @param containerId
         * @param pluginId
         * @param onComplete
         * @returns {{}}
         */
        getEditorElms: function (containerId, pluginId, onComplete) {

            var elm = self.RcmPluginModel.getElm(containerId, pluginId);

            var richEditors = elm.find('[data-richEdit]');
            var textEditors = elm.find('[data-textEdit]');

            var elms = {};

            richEditors.each(
                function (index) {
                    elms[jQuery(this).attr('data-richEdit')] = this;
                }
            );

            textEditors.each(
                function (index) {
                    elms[jQuery(this).attr('data-textEdit')] = this;
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },
        /**
         * getInstanceConfig
         * @param containerId
         * @param pluginId
         * @param onComplete
         */
        getInstanceConfig: function (containerId, pluginId, onComplete) {

            var elm = self.RcmPluginModel.getElm(containerId, pluginId);

            var url = '/api/admin/instance-configs/'
                + self.RcmPluginModel.getName(elm)
                + '/'
                + self.RcmPluginModel.getId(elm);

            //Hide while loading
            elm.hide();

            jQuery.getJSON(
                url,
                function (result) {
                    elm.show();

                    onComplete(result.instanceConfig, result.defaultInstanceConfig);
                }
            );
        }
    }
};
