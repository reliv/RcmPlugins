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

        //Transfer data into DOM for later use and easy saving
        $.each(
            container.find('.person'),
            function () {
                var personEle = $(this);
                var personId = personEle.attr('data-personId');
                var person = instanceConfig.people[personId];
                personEle.append(
                    '<table class="details" style="display:none;">' +
                        '<tr>' +
                        '<td class="longDesc">' +
                        person.longDesc +
                        '</td>' +
                        '<td>' +
                        '<img class="largeImage" ' +
                        'src="' + person.largeImage + '">' +
                        '</td>' +
                        '</tr>' +
                        '</table>'
                )
            }
        );

        container.find('.person').click(me.handlePersonClick);
        container.find('.left').click(apertureSlider.pageBack);
        container.find('.right').click(apertureSlider.pageForward);
    };

    me.handlePersonClick = function () {
        var thisPersonDiv = $(this);

        container.find('.person').removeClass('selected');
        thisPersonDiv.addClass('selected');

        container.find('.mainPerson table').html(
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

    me.init();

};