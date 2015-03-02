var RcmBrightcovePlayerMulti = function (instanceId, instanceConfig) {

    var self = this;
    self.instanceId = instanceId;
    self.instanceConfig = instanceConfig;
    self.downloadUrl = '';
    self.videoId = null; // active video
    self.experienceId = 'myExperience' + self.instanceId;

    self.player = null;
    self.APIModules = null;
    self.videoPlayer = null;

    self.playlists = [];

    RcmBrightCoveEventManager.on('templateLoad-' + instanceId, function (experienceID) {

        self.player = brightcove.api.getExperience(experienceID);
        self.APIModules = brightcove.api.modules.APIModules;
        self.mediaEvent = brightcove.api.events.MediaEvent;

        self.videoPlayer = self.player.getModule(self.APIModules.VIDEO_PLAYER);
        self.videoPlayer.addEventListener(self.mediaEvent.COMPLETE, self.onMediaComplete);
    });

    self.loadVideoById = function (videoId, callback) {

        self.videoPlayer.loadVideoByID(videoId);

        self.getDownloadURL(videoId, callback);
    };

    self.onMediaComplete = function (evt) {
        nextVideo++;
        if (nextVideo == videos.length) {
            nextVideo = 0;
        }

        self.videoPlayer.loadVideoByID(videos[nextVideo]);
        RcmBrightCoveEventManager.trigger('onMediaComplete', self);
    };

    self.getDownloadURL = function (videoId, callback) {

        RcmBrightcoveApiService.getDownloadURL(
            videoId,
            function (url) {
                self.setDownloadUrl(url);
                if (typeof callback === 'function') {
                    callback(self);
                }
            }
        );
    };

    self.setDownloadUrl = function (url) {

        if (self.downloadUrl !== url) {

            self.downloadUrl = url;
            RcmBrightCoveEventManager.trigger('downloadUrlChange-' + instanceId, self);
        }
    };

    self.prepareData = function (data, callback) {

        self.playlists = data.items;
        // set videoId to play fo first video
        self.videoId = self.playlists[0].videos[0].id;

        if (typeof callback === 'function') {
            callback(self);
        }

        RcmBrightCoveEventManager.trigger('playlistsBuilt-' + instanceId, self);
    };

    /**
     * buildPlaylist
     * @param onComplete
     */
    self.buildPlaylist = function (onComplete) {

        RcmBrightcoveApiService.findPlaylistsById(
            self.instanceConfig.playlistIds,
            function (data) {
                self.prepareData(data, onComplete);
            }
        );
    };

    self.init = function () {
        self.buildPlaylist(
            function (thisPlayer) {
                if (thisPlayer.videoId) {
                    RcmBrightCoveEventManager.on('templateReady-' + instanceId, function () {
                        self.videoPlayer.cueVideoByID(thisPlayer.videoId);
                        self.getDownloadURL(thisPlayer.videoId);
                    });
                }
            }
        );
    };

    self.init();
};