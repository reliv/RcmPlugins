/**
 * RcmPeopleSlider
 *
 * JS for editing RcmPeopleSlider
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmPeopleSlider
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmPeopleSliderEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmPeopleSlider}
     */
    var me = this;

    /**
     * jQuery object for the two links
     *
     * @type {Object}
     */
    var aTags = container.find('a');

    /**
     * Background image jQuery object
     *
     * @type {Object}
     */
    var imgTag = container.find('img');

    /**
     * Called by content management system to make this plugin user-editable
     *
     * @return {Null}
     */
    me.initEdit = function(){

        //Double clicking will show properties dialog
        container.delegate('div', 'dblclick', function(event){
            me.showEditDialog();
        });

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items:{
                edit:{
                    name:'Edit Properties',
                    icon:'edit',
                    callback:function () {
                        me.showEditDialog();
                    }
                }

            }
        });


    }

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        var people = [];

        $.each(
            container.find('.person'),
            function(){
                var personDiv = $(this);
                people.push(
                    {
                        'smallImage':personDiv.find('.smallImage').attr('src'),
                        'largeImage':personDiv.find('.largeImage').attr('src'),
                        'shortDesc':personDiv.find('.shortDesc').html(),
                        'longDesc':personDiv.find('.longDesc').html()
                    }
                );
            }
        );

        return {
            people:people
        }
    }

}