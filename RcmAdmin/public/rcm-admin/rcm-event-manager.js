/**
 * RcmEventManager
 * @constructor
 */
var RcmEventManager = function() {

    var self = this;

    self.events = {};

    self.on = function (event, method) {

        if (!self.events[event]) {
            self.events[event] = [];
        }

        self.events[event].push(method);
    };

    self.trigger = function (event, args) {

        if (self.events[event]) {
            jQuery.each(
                self.events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    };

    self.hasEvents = function (event) {

        if (!self.events[event]) {
            return false;
        }

        if (self.events[event].length > 0) {
            return true;
        }

        return false;
    }
};
