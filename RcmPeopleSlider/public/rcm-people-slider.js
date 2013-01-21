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
            frameWidth : 150,
            minHeight : 100,
            frameSeparation : 0,
            framesPerView : framesPerView,
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
                    '<table class="details" style="display:none;">' +
                        '<tr>' +
                            '<td class="longDesc">' +person.longDesc +'</td>' +
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
    };

    me.handlePersonClick = function(){
        container.find('.mainPerson table').html($(this).find('.details').html());
        apertureSlider.goForward();
    };

    me.init();

};