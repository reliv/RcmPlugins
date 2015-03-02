var RcmBrightCoveEventManager = {

    events: {},

    on: function (event, method) {

        if (!RcmBrightCoveEventManager.events[event]) {
            RcmBrightCoveEventManager.events[event] = [];
        }

        RcmBrightCoveEventManager.events[event].push(method);
    },

    trigger: function (event, args) {

        if (RcmBrightCoveEventManager.events[event]) {
            jQuery.each(
                RcmBrightCoveEventManager.events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    }
}