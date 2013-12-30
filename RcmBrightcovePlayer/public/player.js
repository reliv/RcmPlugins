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
        if(me.startVideo){
            me.cueVideoById(me.startVideo);
        }
    };

    me.onTemplateReady = function (evt) {
        me.modVP.addEventListener(brightcove.api.events.MediaEvent.BEGIN, me.onMediaBegin);
        me.modVP.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, me.onMediaComplete);

    };

    me.loadVideoById =function (videoId) {
        me.modVP.loadVideoByID(videoId);
        getDownloadURL(videoId);
    };

    me.cueVideoById =function (videoId) {
        if(me.modVP){
            me.modVP.cueVideoByID(videoId);
        }else{
            me.startVideo = videoId;
        }
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


