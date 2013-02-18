var RcmBrightCove = {
    onPlayerReady : function (experienceID) {

        if ($.type(RcmBrightCove.rcmRegisteredCallbacks[experienceID]) === 'function' ) {
            RcmBrightCove.rcmRegisteredCallbacks[experienceID](experienceID);
        }
    },

    getPlayer : function(container) {
        var containerId = $(container).attr("id");
        var playerId = 'brightcove_'+containerId;
        return brightcove.getExperience(experienceID);
    },

    setVideo : function(container, id) {
        var APIModules = brightcove.api.modules.APIModules;
        var videoPlayer = me.player.getModule(APIModules.VIDEO_PLAYER);
        videoPlayer.loadVideoByID(id);
    },

    addPlayer : function(container, playerId, callback) {

        var containerId = $(container).attr("id");
        var playerId = 'brightcove_'+containerId;

        var check = $(container).find("#"+playerId);

        if (check.length > 0) {
            return;
        }

        if ($.type(callback) === 'function') {
            RcmBrightCove.rcmRegisteredCallbacks[playerId] = callback;
        }

        newDiv = $('<div style="display:none"></div>');
        newObject = $('<object id="'+playerId+'" class="BrightcoveExperience"></object>');
        $(newObject).append('<param name="bgcolor" value="#FFFFFF" />');
//        $(newObject).append('<param name="width" value="720" />');
//        $(newObject).append('<param name="height" value="888" />');
        $(newObject).append('<param name="playerID" value="'+playerId+'" />');
        $(newObject).append('<param name="@playlistTabs" value="1519039001001, 1519039006001, 1519039009001" />');
        $(newObject).append('<param name="playerKey" value="AQ~~,AAABWA8lTok~,NLWj-wltGTwmtIIwHnfmL3pPT3olHP74" />');
        $(newObject).append('<param name="isVid" value="true" />');
        $(newObject).append('<param name="isUI" value="true" />');
        $(newObject).append('<param name="dynamicStreaming" value="true" />');
        $(newObject).append('<param name="wmode" value="transparent" />');
        $(newObject).append('<param name="templateLoadHandler" value="RcmBrightCove.onPlayerReady" />');
        $(container).html('');
        $(container).append(newDiv);
        $(container).append(newObject);

        RcmBrightCove.startPlayer();
    },

    startPlayer : function(){
        $.ajaxSetup({
            cache: true
        });
        $.getScript("http://admin.brightcove.com/js/BrightcoveExperiences.js", function(){
            $.getScript("http://admin.brightcove.com/js/APIModules_all.js", function(){
                RcmBrightCove.scriptLoadedCallback();
            });
        });
    },

    scriptLoadedCallback : function() {
        brightcove.createExperiences();
//        RcmBrightCove.player = brightcove.getExperience(me.playerId);
//        console.log(RcmBrightCove.playerId);
    },

    rcmRegisteredCallbacks : {}

};

