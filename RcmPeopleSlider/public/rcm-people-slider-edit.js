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
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmPeopleSliderEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmPeopleSliderEdit}
     */
    var me = this;

    /**
     * The the object used by non-admins to view this plugin
     * @type {RcmPeopleSlider}
     */
    var peopleSlider = window['RcmPeopleSliders'][instanceId];

    var personTemplate = {
        largeImage: '/modules/rcm-lib/images/no-image.png',
        smallImage: '/modules/rcm-lib/images/no-image.png',
        shortDesc: 'Short Description',
        longDesc: 'Long description'
    };

    var newPersonId = 10000;

    var sortable = false;

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {
        var contSel = rcm.getPluginContainerSelector(instanceId);

        // Due to a bug in core, the view object has a container that is not it
        // the dom. This fixes that.
        peopleSlider.init(container);

        //Add right click menu
        $.contextMenu({
            selector: contSel + ' .person, ' + contSel + ' .personDetails',
            //Here are the right click menu options
            items: {
                sort: {
                    name: 'Toggle Sort Mode (Allows drag and drop sorting but ' +
                        'disables text editing)',
                    icon: 'edit',
                    callback: function () {
                        if (sortable) {
                            sortable = false;
                            container.find('.previews').sortable('destroy');
                        } else {
                            sortable = true;
                            container.find('.previews').sortable();
                        }
                    }
                },
                line1: '-',
                create: {
                    name: 'Create Person',
                    icon: 'edit',
                    callback: me.createPerson
                },
                edit: {
                    name: 'Edit Person Images',
                    icon: 'edit',
                    callback: me.editPersonImages
                },
                line2: '-',
                "delete": {
                    name: 'Delete Person',
                    icon: 'delete',
                    callback: me.deletePerson
                }
            }
        });

        me.makePeopleEditable();
    };

    me.makePeopleEditable = function () {
        $.each(
            container.find('.person'),
            function () {
                me.makePersonEditable($(this).attr('data-personId'));
            }
        );
        peopleSlider.attachClickEvents();

        //Work around for ckEditor Issues with hidden edit-ables
        setTimeout(peopleSlider.showHideSelectedPerson, 100);
    };

    me.makePersonEditable = function (personId) {
        var personEles = peopleSlider.getPersonElements(personId);
        if (!personEles.shortDesc.attr('editInited')) {
            personEles.shortDesc.attr('editInited', true);

            //Make the shortDesc editable
            personEles.shortDesc.css('cursor', 'text');

            // We load all images when in the editor to make saving easier
            peopleSlider.loadDelayedImage(personEles.largeImage);

            //Allow double click to edit images
            personEles.smallImage.dblclick(me.editPersonImages);
            personEles.largeImage.dblclick(me.editPersonImages);

            //CkEditor has issues attaching to hidden elements
            personEles.details.show();

            rcm.angularCompile(container);
        }
    };

    me.createPerson = function () {
        newPersonId += 1;

        container.find('.previews').append(
            peopleSlider.buildPersonPreview(newPersonId, personTemplate)
        );

        container.find('.peopleDetails').append(
            peopleSlider.buildPersonDetails(newPersonId, personTemplate)
        );

        //Makes the slide recalculate the frame count
        peopleSlider.apertureSlider.init();

        peopleSlider.selectPerson(newPersonId);

        peopleSlider.attachClickEvents();

        me.makePersonEditable(newPersonId);
    };

    me.deletePerson = function () {
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

    me.editPersonImages = function () {
        var clickedEle = $(this);
        var personId = clickedEle.attr('data-personId');

        //Used for double clicking on images
        if (typeof(personId) == 'undefined') {
            personId = clickedEle
                .closest('[data-personId]').attr('data-personId');
        }

        var personEles = peopleSlider.getPersonElements(personId);

        var smallImage = $.dialogIn(
            'image',
            'Small Preview Image (150px by 100px)',
            personEles.smallImage.attr('src')
        );
        var largeImage = $.dialogIn(
            'image',
            'Large Details Image (200px wide)',
            personEles.largeImage.attr('src')
        );

        var form = $('<form></form>')
            .addClass('simple')
            .append(smallImage, largeImage)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form
                        personEles.smallImage.attr('src', smallImage.val());
                        personEles.largeImage.attr('src', largeImage.val());
                        personEles.largeImage.attr(
                            'delayed-src', largeImage.val()
                        );

                        $(this).dialog('close');
                    }
                }
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
            function () {
                var personEles = peopleSlider.getPersonElements(
                    $(this).attr('data-personId')
                );
                people.push(
                    {
                        'smallImage': personEles.smallImage.attr('src'),
                        'largeImage': personEles.largeImage.attr('src'),
                        'shortDesc': personEles.shortDesc.html(),
                        'longDesc': personEles.longDesc.html()
                    }
                );
            }
        );

        return {
            people: people
        }
    }
};