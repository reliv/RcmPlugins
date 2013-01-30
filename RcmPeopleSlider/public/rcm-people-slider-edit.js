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

    var personTemplate = {
        largeImage: rcmEdit.getBlankImgUrl(),
        smallImage: rcmEdit.getBlankImgUrl(),
        shortDesc: 'Short Description',
        longDesc: 'Long description'
    };

    var newPersonId = 10000;

    /**
     * Called by content management system to make this plugin user-editable
     *
     * @return {Null}
     */
    me.initEdit = function(){

        var contSel = rcm.getPluginContainerSelector(instanceId);

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector: contSel + ' .person, ' + contSel + ' .personDetails',
            //Here are the right click menu options
            items:{
                edit:{
                    name:'Create Person',
                    icon:'edit',
                    callback:me.createPerson
                },
                edit:{
                    name:'Delete Person',
                    icon:'delete',
                    callback:me.deletePerson
                }

            }
        });

        me.makePeopleEditable();
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

            //Make the shortDesc editable
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

    me.createPerson = function(){
        newPersonId+=1;

        container.find('.previews').append(
            peopleSlider.buildPersonPreview(newPersonId, personTemplate)
        );

        container.find('.peopleDetails').append(
            peopleSlider.buildPersonDetails(newPersonId, personTemplate)
        );

        peopleSlider.selectPerson(newPersonId);

        //Makes the slide recalculate the frame count
        peopleSlider.apertureSlider.init();
    };

    me.deletePerson = function(){
        var personEles = peopleSlider.getPersonElements(
            $(this).attr('data-personId')
        );
        personEles.details.remove();
        personEles.preview.remove();

        //Select first person in case we deleted the selected person
        peopleSlider.selectPerson($('.person').attr('data-personId'));

        //Makes the slide recalculate the frame count
        peopleSlider.apertureSlider.init();
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

};