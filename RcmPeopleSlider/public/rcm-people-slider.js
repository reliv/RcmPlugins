/**
 *
 * @param {Integer} instanceId
 * @param {Object} instanceConfig
 * @constructor
 */
var RcmPeopleSlider = function (instanceId, instanceConfig) {


    /**
     * Always refers to this object unlike the 'this' JS variable;
     */
    var me = this;

    /**
     * Plugin container div jQuery object
     * @type {Object}
     */
    var container;

    var framesPerView = 4;

    var peopleDetailsDiv;

    var selectedPersonId = 0;

    /**
     * This object helps us manage the online app as a multi-part form
     * @type {ApertureSlider}
     *
     * This is a public property so the editor change the frame count
     */
    me.apertureSlider = null;

    /**
     * Runs immediately after this class is instantiated
     */
    me.init = function (containerObj) {

        //Allow container to be passed in so the edit object can work around
        // a bug in core
        container = containerObj;

        peopleDetailsDiv = container.find('.peopleDetails');

        me.apertureSlider = new ApertureSlider(
            container.find('.peopleAperture'),
            {
                frameWidth: 158,
                minHeight: 140,
                frameSeparation: 0,
                framesPerView: framesPerView,
                hideOffScreenFrames: false
            }
        );

        //Register this object so the editor can use it
        if (typeof(window.RcmPeopleSliders) != 'array') {
            window.RcmPeopleSliders = [];
        }
        window['RcmPeopleSliders'][instanceId] = me;

        var selectedPersonId = peopleDetailsDiv.attr('data-selectedPersonId');

        peopleDetailsDiv.empty();

        /**
         * Transfer data into DOM for later use and easy saving. We do this in
         * JS instead of PHP because we want search engines to see each person
         * as a separate linked page.
         */
        $.each(
            instanceConfig.people,
            function (personId, person) {
                var detailsEle = me.buildPersonDetails(personId, person);
                peopleDetailsDiv.append(detailsEle);
                if (personId == selectedPersonId) {
                    detailsEle.show();
                    me.loadDelayedImage(detailsEle.find('.largeImage'));
                }
            }
        );

        me.attachClickEvents();

    };

    me.attachClickEvents = function () {
        container.find('.person').click(me.handlePersonClick);
        container.find('.left').click(me.apertureSlider.pageBack);
        container.find('.right').click(me.apertureSlider.pageForward);
    };

    me.buildPersonDetails = function (personId, person) {

        var dataPersonId = ' data-personId="' + personId + '"';

        var imageTag = '';
        var longDescNoimageCss = ' noimage';

            //We don't put anything in src so images only load later
        imageTag = '<img ' +
            'class="largeImage" ' +
            'data-delayedSrc="' + person.largeImage + '">';
            longDescNoimageCss = '';


        return $(
            '<div class="personDetails"' + dataPersonId + ' style="display:none;"' + '>' +
            '<div class="longDesc' + longDescNoimageCss + '" data-rcm-html-edit>' +
            person.longDesc +
            '</div>' +
            imageTag +
            '</div>'
        );
    };

    /**
     * Used by editor JS to build new person previews
     * @param personId
     * @param person
     * @return {*|jQuery|HTMLElement}
     */
    me.buildPersonPreview = function (personId, person) {
        return $(
            '<a href="?id=' + personId + '" ' +
            'class="person" ' +
            'data-personId="' + personId + '">' +
            '<img class="smallImage" src="' + person.smallImage + '">' +
            '<div class="shortDesc" data-rcm-html-edit>' +
            person.shortDesc +
            '</div>' +
            '</a>'
        );
    };

    me.handlePersonClick = function (event) {
        event.preventDefault();
        selectedPersonId = $(this).attr('data-personId');
        me.render();
    };

    me.selectPerson = function (personId) {
        selectedPersonId = personId;
        me.render();
    };

    me.render = function () {
        me.showHideSelectedPerson();

        var personEles = me.getPersonElements(selectedPersonId);

        container.find('.person').removeClass('selected');
        personEles.preview.addClass('selected');

        me.loadDelayedImage(personEles.largeImage);


        container.find('.personDetails').html(
            personEles.preview.find('.details').html()
        );

        //PersonId's start with zero but frames start with 1
        var frame = parseInt(personEles.preview.attr('data-personId')) + 1;

        //Put the selected frame more in the center
        if (frame > 1) {
            frame = frame - 1;
        }

        me.apertureSlider.goToFrame(frame);
    };

    me.showHideSelectedPerson = function () {
        var personEles = me.getPersonElements(selectedPersonId);

        peopleDetailsDiv.children().hide();
        personEles.details.show();
    };

    /**
     * Gets all the elements for a person. Is also used in editor JS file.
     * @param {Integer} personId
     * @return {Object}
     */
    me.getPersonElements = function (personId) {
        var dataSelector = '[data-personid=' + personId + ']';
        var details = container.find('.personDetails' + dataSelector);
        var preview = container.find('.person' + dataSelector);
        return {
            details: details,
            preview: preview,
            shortDesc: preview.find('.shortDesc'),
            longDesc: details.find('.longDesc'),
            smallImage: preview.find('.smallImage'),
            largeImage: details.find('.largeImage')
        }
    };

    /**
     * Load the large image once we know it will be used
     * @param {Object} imageElement
     */
    me.loadDelayedImage = function (imageElement) {

        var imageSrc = imageElement.attr('data-delayedSrc');

        imageElement.attr('src', imageElement.attr('data-delayedSrc'));

        if (imageSrc == 'undefined') {
            imageElement.hide();
        }
    };

    me.init(rcm.getPluginContainer(instanceId));

};