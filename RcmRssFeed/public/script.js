function RssReader(proxy, instanceId, displayContainer, urlOverride, limit) {

    var me = this;

    me.instanceId = instanceId;

    me.displayContainer = displayContainer;

    me.proxy = proxy;

    me.dataToSend = {
        instanceId: instanceId,
        urlOverride: urlOverride,
        limit: limit
    };

    $.getJSON(me.proxy, me.dataToSend, function (data) {
        $(displayContainer).html('');
        if (data.length < 1) {
            var newLine = $("<p>").html('No RSS feed results found.');
            $(me.displayContainer).html(newLine);
        }
        $.each(data, function (key, value) {
            var newLine = $("<p>").html('<a href="' + value.feedlink + '">' + value.feedtitle + '</a>').addClass("rcmRssFeedLine");
            $(me.displayContainer).append(newLine);
        });
    }).error(function () {
        var newLine = $("<p>").html('There was a problem retrieving the RSS feed');
        $(me.displayContainer).html(newLine);
    });
}
