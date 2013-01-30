/**
 *
 * @param {Integer} instanceId
 * @param {Object} instanceConfig
 * @constructor
 */
var RcmPeopleSlider = function (instanceId, instanceConfig) {


    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmDistributorApp}
     */
    var me = this;

    /**
     * Plugin container div jQuery object
     * @type {Object}
     */
    var container = rcm.getPluginContainer(instanceId);

    var framesPerView = 4;

    var peopleDetailsDiv = container.find('.peopleDetails');

    var selectedPersonId = 0;

    /**
     * This object helps us manage the online app as a multi-part form
     * @type {ApertureSlider}
     */
    var apertureSlider = new ApertureSlider(
        container.find('.peopleAperture'),
        {
            frameWidth:160,
            minHeight:140,
            frameSeparation:0,
            framesPerView:framesPerView,
            hideOffScreenFrames:false
        }
    );

    /**
     * Runs immediately after this class is instantiated
     */
    me.init = function () {

        //Register this object so the editor can use it
        if(typeof(window.RcmPeopleSliders)!='array'){
            window.RcmPeopleSliders = [];
        }
        window.RcmPeopleSliders[instanceId] = me;

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
                var dataPersonId = ' data-personId="' + personId + '"';
                var detailsEle = $(
                    '<table class="personDetails"'
                        + dataPersonId + ' style="display:none;"' + '>' +
                        '<tr>' +
                            '<td><div class="longDesc">' +
                            person.longDesc +
                            '</div></td>' +
                        '<td>' +
                        //We don't put anything in src so images only load later
                        '<img ' +
                            'class="largeImage" ' +
                            'data-delayedSrc="' + person.largeImage + '">' +
                        '</td>' +
                        '</tr>' +
                        '</table>'
                );

                if(personId==selectedPersonId){
                    detailsEle.removeAttr('style');
                    me.loadDelayedImage(detailsEle.find('.largeImage'));
                }

                peopleDetailsDiv.append(detailsEle);
            }
        );

        container.find('.person').click(me.handlePersonClick);
        container.find('.left').click(apertureSlider.pageBack);
        container.find('.right').click(apertureSlider.pageForward);
    };

    me.handlePersonClick = function () {
        event.preventDefault();
        selectedPersonId = $(this).attr('data-personId');
        me.render();
    };

    me.render = function(){
        var personEles = me.getPersonElements(selectedPersonId);

        container.find('.person').removeClass('selected');
        personEles.preview.addClass('selected');

        peopleDetailsDiv.children().hide();

        me.loadDelayedImage(personEles.largeImage);

        personEles.details.show();

        container.find('.personDetails').html(
            personEles.preview.find('.details').html()
        );

        //PersonId's start with zero but frames start with 1
        var frame = parseInt(personEles.preview.attr('data-personId')) + 1;

        //Put the selected frame more in the center
        if (frame > 1) {
            frame = frame - 1;
        }

        apertureSlider.goToFrame(frame);
    };

    /**
     * Gets all the elements for a person. Is also used in editor JS file.
     * @param {Integer} personId
     * @return {Object}
     */
    me.getPersonElements = function(personId){
        var dataSelector = '[data-personId=' + personId + ']';
        var details = container.find('.personDetails' + dataSelector);
        var preview = container.find('.person' + dataSelector);
        return {
            details: details,
            preview: preview,
            shortDesc: preview.find('.shortDesc'),
            longDesc: details.find('.longDesc'),
            smallImage: preview.find('.smallImage'),
            largeImage : details.find('.largeImage')
        }
    };

    /**
     * Load the large image once we know it will be used
     * @param detailsEle
     */
    me.loadDelayedImage = function(imageElement){
        imageElement.attr('src', imageElement.attr('data-delayedSrc'));
    };

    me.init();

};