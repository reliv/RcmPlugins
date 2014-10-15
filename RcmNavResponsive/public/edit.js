/**
 * RcmNavResponsive
 *
 * JS for editing RcmNavResponsive
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmNavResponsiveEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmNavResponsiveEdit}
     */
    var me = this;

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        //Save our html to the hidden input box
        return {};
    };
};