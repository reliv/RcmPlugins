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
                var personDetailsTable = $(
                    '<table class="personDetails"'
                        + dataPersonId + ' style="display:none;"' + '>' +
                        '<tr>' +
                            '<td class="longDesc">' +
                            person.longDesc +
                            '</td>' +
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
                    personDetailsTable.removeAttr('style');
                    me.loadDelayedImage(personDetailsTable);
                }

                peopleDetailsDiv.append(personDetailsTable);
            }
        );

        container.find('.person').click(me.handlePersonClick);
        container.find('.left').click(apertureSlider.pageBack);
        container.find('.right').click(apertureSlider.pageForward);
    };

    me.handlePersonClick = function () {

        event.preventDefault();

        var thisPersonDiv = $(this);
        var personId = thisPersonDiv.attr('data-personId');

        container.find('.person').removeClass('selected');
        thisPersonDiv.addClass('selected');

        peopleDetailsDiv.children().hide();
        var personDetailsTable
            = container.find('.personDetails[data-personId=' + personId + ']');

        me.loadDelayedImage(personDetailsTable);

        personDetailsTable.show();

        container.find('.personDetails').html(
            thisPersonDiv.find('.details').html()
        );

        //PersonId's start with zero but frames start with 1
        var frame = parseInt(thisPersonDiv.attr('data-personId')) + 1;

        //Put the selected frame more in the center
        if (frame > 1) {
            frame = frame - 1;
        }

        apertureSlider.goToFrame(frame);
    };

    /**
     * Load the large image once we know it will be used
     * @param personDetailsTable
     */
    me.loadDelayedImage = function(personDetailsTable){
        var largeImage = personDetailsTable.find('.largeImage');
        largeImage.attr('src', largeImage.attr('data-delayedSrc'));
    };

    me.init();

};