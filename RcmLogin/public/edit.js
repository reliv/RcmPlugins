/**
 * Reliv Content Manager Login
 *
 * JS for editing Login Plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RandomImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmLoginEdit = function(instanceId, container){

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RandomImage}
     */
    var me = this;

    /**
     * Called by RelivContentManger to make the random image editable
     *
     * @return {Null}
     */
    me.initEdit = function(){

        $('[data-textEdit="loginErrorInvalidCopy"]').show();

    }

    /**
     * Called by RelivContentManger to get the state of this plugin to pass to
     * the server
     *
     * @return void
     */
    me.getSaveData = function(){

    }
}