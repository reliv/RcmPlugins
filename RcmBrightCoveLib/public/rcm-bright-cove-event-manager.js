/**
 * This class handles listeners for brightcove
 *
 * Warning: This is a "smart" event manager in that it will look to see if your
 * event has fired before you registered an event listener. If your event already
 * fired, we trigger it again.
 */
var RcmBrightCoveEventManager = new function () {
    var self = this;
    var listeners = {};
    var triggeredEvents = {};

    /**
     * Registers an event listener
     *
     * @param event
     * @param listener
     */
    self.on = function (event, listener) {

        self.log('added listener for ' + event);

        if (!listeners[event]) {
            listeners[event] = [];
        }

        listeners[event].push(listener);

        if (triggeredEvents[event]) {
            self.log(event + ' was previously fired, calling its just-added handler now');
            listener(triggeredEvents[event]);
        }
    };

    /**
     * Triggers an event
     *
     * @param event
     * @param args
     */
    self.trigger = function (event, args) {

        self.log('triggering ' + event);

        triggeredEvents[event] = args;

        if (listeners[event]) {
            jQuery.each(
                listeners[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    };

    /**
     * This is a great place to toss a console dot log
     *
     * @param message
     */
    self.log = function (message) {
    }
}();