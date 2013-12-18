/**
 * Created by bjanish on 12/4/13.
 */

var brightcovePlayerApi = function(startVideo) {

    var me = this;
    me.player=null;
    me.modVP=null;
    me.nextVideo = 0;
    me.startVideo = startVideo;

    me.myTemplateLoaded = function(experienceID) {
        me.player = brightcove.api.getExperience(experienceID);
        me.modVP = me.player.getModule(brightcove.api.modules.APIModules.VIDEO_PLAYER);
        console.log("%c^*^*^*^^*^*^*^*^*^*^*^  MY TEMPLATE LOADED ^*^*^*^*^*^*^*^*^*^*^", "background: red; color:white; font-size: large");
    };

    me.onTemplateReady = function (evt) {
        me.loadVideoById(me.startVideo);
        me.modVP.addEventListener(brightcove.api.events.MediaEvent.BEGIN, me.onMediaBegin);
        me.modVP.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, me.onMediaComplete);
        console.log("%c^*^*^*^^*^*^*^*^*^*^*^  ON TEMPLATE LOADED ^*^*^*^*^*^*^*^*^*^*^", "background: red; color:white; font-size: large");
    };

    me.loadVideoById =function (videoId) {
        me.modVP.loadVideoByID(videoId);
        getDownloadURL(videoId);

    };

    me.onMediaBegin = function (evt) {
        document.getElementById("mediaInfo").innerHTML = evt.media.displayName;
    }

    me.onMediaComplete = function (evt) {
        nextVideo++;
        if (nextVideo == videos.length) {
            nextVideo = 0;
        }
        modVP.loadVideoByID(videos[nextVideo]);
    };

};


