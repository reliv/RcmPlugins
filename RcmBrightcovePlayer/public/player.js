/**
 * Created by bjanish on 12/4/13.
 */

var brightcovePlayerApi = function(instanceId) {

    var me = this;
    me.player=null;
    me.modVP=null;
    me.nextVideo = 0;
    var templateReady = false;
    var loadVideoIdWhenReady = 0;

    me.myTemplateLoaded = function(experienceID) {
        me.player = brightcove.api.getExperience(experienceID);
        me.modVP = me.player.getModule(brightcove.api.modules.APIModules.VIDEO_PLAYER);
    };

    me.onTemplateReady = function (evt) {
        templateReady = true;
        me.modVP.addEventListener(brightcove.api.events.MediaEvent.BEGIN, me.onMediaBegin);
        me.modVP.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, me.onMediaComplete);
        if(loadVideoIdWhenReady){
            me.cueVideoById(loadVideoIdWhenReady);
        }
    };

    me.loadVideoById = function (videoId) {
        me.modVP.loadVideoByID(videoId);
        getDownloadURL(videoId, me.setDownloadUrl);
    };

    me.cueVideoById = function (videoId) {
        if(templateReady){
            // onTemplateReady has already been called so we can just cue the video
            me.modVP.cueVideoByID(videoId);
        } else {
            // onTemplateReady hasn't been called yet so we set this property to be used when it does get called 
            loadVideoIdWhenReady = videoId;
        }
        getDownloadURL(videoId, me.setDownloadUrl);
    };

    me.setDownloadUrl = function(url){
        $('#RcmBrightcovePlayerDownloadLink' + instanceId).attr('href', url);
    }

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
