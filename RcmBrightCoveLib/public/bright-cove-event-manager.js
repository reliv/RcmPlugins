var BrightCoveEventManager = {

    events: {},

    on: function (event, method) {

        if (!BrightCoveEventManager.events[event]) {
            BrightCoveEventManager.events[event] = [];
        }

        BrightCoveEventManager.events[event].push(method);
    },

    trigger: function (event, args) {

        if (BrightCoveEventManager.events[event]) {
            jQuery.each(
                BrightCoveEventManager.events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    }
}