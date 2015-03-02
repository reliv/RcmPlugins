/**
 * This class handles events for brightcove
 *
 * Warning: This is a "smart" event manager in that it will look to see if your
 * event has fired before you registered an event listener. If your event already
 * fired, we trigger it again.
 */
var RcmBrightCoveEventManager = new function () {
    var self = this;
    var events = {};
    var previouslyTriggeredEvents = {};
    self.on = function (event, onTrigger) {

        if (!events[event]) {
            events[event] = [];
        }

        events[event].push(onTrigger);

        if (previouslyTriggeredEvents[event]) {
            onTrigger(event, previouslyTriggeredEvents[event]);
        }
    };

    self.trigger = function (event, args) {

        previouslyTriggeredEvents[event] = args;

        if (events[event]) {
            jQuery.each(
                events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    }
}();