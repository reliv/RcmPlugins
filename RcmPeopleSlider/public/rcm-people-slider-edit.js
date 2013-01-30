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
     * The the object used by non-admins to view this plugin
     * @type {RcmPeopleSlider}
     */
    var peopleSlider = window.RcmPeopleSliders[instanceId];

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

        me.makePeopleEditable();

        //for testing only
        console.log(me.getSaveData());

    };

    me.makePeopleEditable = function(){
        $.each(
            container.find('.person'),
            function(){
                me.makePersonEditable($(this).attr('data-personId'));
            }
        );
    };

    me.makePersonEditable = function(personId){
        var personEles = peopleSlider.getPersonElements(personId);
        if(!personEles.shortDesc.attr('contenteditable')){
            personEles.shortDesc.attr('contenteditable', true);
            personEles.shortDesc.css('cursor', 'text');

            // We load all images when in the editor to make saving easier
            peopleSlider.loadDelayedImage(personEles.largeImage);
            // This fails when the plugin is brand new because we can't start ck
            // editors on elements that are not in the dom
            try{
                rcmEditor.convertToHtml5Editor(personEles.longDesc);
            }catch(e){

            }
        }
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
                var personEles = peopleSlider.getPersonElements(
                    $(this).attr('data-personId')
                );
                people.push(
                    {
                        'smallImage' :personEles.smallImage.attr('src'),
                        'largeImage' : personEles.largeImage.attr('src'),
                        'shortDesc' : personEles.shortDesc.html(),
                        'longDesc' : personEles.longDesc.html()
                    }
                );
            }
        );

        return {
            people:people
        }
    }

}