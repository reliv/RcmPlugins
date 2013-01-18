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

    var framesPerView = 2;

    /**
     * This object helps us manage the online app as a multi-part form
     * @type {ApertureSlider}
     */
    var apertureSlider = new ApertureSlider(
        container.find('.peopleAperture'),
        {
            width : 160 * framesPerView,
            minHeight : 100,
            frameSeparation : 0,
            framesPerView : 2,
            hideOffScreenFrames : false
        }
    );

    /**
     * Runs immediately after this class is instantiated
     */
    me.init = function(){

        //Transfer data into DOM for later use and easy saving
        $.each(
            container.find('.person'),
            function(){
                var personEle = $(this);
                var personId = personEle.attr('data-personId');
                var person = instanceConfig.people[personId];
                personEle.append(
                    '<div class="details" style="display:none;">' +
                        '<div class="longDesc">' +
                        person.longDesc +
                        '</div>' +
                        '<img class="largeImage" ' +
                            'src="' + person.largeImage + '">' +
                    '</div>'
                )
            }
        );

        container.find('.person').click(me.handlePersonClick);
    };

    me.handlePersonClick = function(){
        container.find('.mainPerson').html($(this).find('.details').html());
        apertureSlider.goForward();
    };

    me.init();

};