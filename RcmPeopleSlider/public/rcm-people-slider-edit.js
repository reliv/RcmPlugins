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

    var peopleDetailsDiv = container.find('.peopleDetails');

    /**
     * Called by content management system to make this plugin user-editable
     *
     * @return {Null}
     */
    me.initEdit = function(){

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items:{
                edit:{
                    name:'Edit Properties',
                    icon:'edit',
                    callback:function () {}
                }

            }
        });

        me.loadAllDelayedImages();

        me.getSaveData();
    };

    me.makePersonEditable = function(personId){
        var dataSelector = '[data-personId=' + personId + ']';
        var personEle = container.find('.person' + dataSelector);
        var personDetailsEle = container.find('.personDetails' + dataSelector);
    };

    /**
     * Delaying the loading of these is good for viewing but when we are
     * editing, we just want to load them all
     */
    me.loadAllDelayedImages = function(){
        $.each(
            container.find('.peopleDetails .largeImage'),
            function(){
                var largeImage = $(this);
                largeImage.attr('src', largeImage.attr('data-delayedSrc'));
            }
        );
    };

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
                var personId = personDiv.attr('data-personId');
                var personDetailsTable = peopleDetailsDiv.find(
                    'table[data-personId=' + personId + ']'
                );
                people.push(
                    {
                        'smallImage' :
                            personDiv.find('.smallImage').attr('src'),
                        'largeImage' :
                            personDetailsTable.find('.largeImage').attr('src'),
                        'shortDesc' :
                            personDiv.find('.shortDesc').html(),
                        'longDesc' :
                            personDetailsTable.find('.longDesc').html()
                    }
                );
            }
        );

        return {
            people:people
        }
    }

}