/**
 * RcmEventManager
 * @constructor
 */
var RcmEventManager = function() {

    var self = this;

    /**
     * events
     * @type {{}}
     */
    self.events = {};

    /**
     * promises
     * @type {{}}
     */
    self.promises = {};

    /**
     * on - register listener
     * @param event
     * @param method
     * @param checkPromise
     */
    self.on = function (event, method, checkPromise) {

        if (!self.events[event]) {
            self.events[event] = [];
        }

        self.events[event].push(method);

        if (checkPromise) {

            self.honorPromise(event, method);
        }
    };

    /**
     * trigger listener
     * @param event
     * @param args
     */
    self.trigger = function (event, args) {

        if (self.events[event]) {
            jQuery.each(
                self.events[event],
                function (index, value) {
                    value(args);
                    self.makePromise(event, index, args);
                }
            );
        }
    };

    /**
     * makePromise
     * @param event
     * @param index
     * @param args
     */
    self.makePromise = function(event, index, args){

        if (!self.promises[event]) {
            self.promises[event] = [];
        }

        self.promises[event][index] = args;
    };

    /**
     * honorPromise
     * @param event
     * @param method
     */
    self.honorPromise = function(event, method){

        if (self.promises[event]) {
            jQuery.each(
                self.promises[event],
                function (index, value) {
                    method(value);
                }
            );
        }
    };

    /**
     *
     * @param event
     * @returns {boolean}
     */
    self.hasEvents = function (event) {

        if (!self.events[event]) {
            return false;
        }

        return (self.events[event].length > 0);
    }
};
