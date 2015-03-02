var RcmBrightcovePlayerMulti = function (instanceId, instanceConfig, onComplete) {

    var self = this;
    self.instanceId = instanceId;
    self.instanceConfig = instanceConfig;
    self.downloadUrl = '';
    self.videoId = null; // active video
    self.experienceId = 'myExperience' + self.instanceId;

    self.templateReady = false;
    self.loadVideoIdWhenReady = 0;

    self.player = null;
    self.APIModules = null;
    self.videoPlayer = null;

    self.playlists = [];

    self.setInstanceConfig = function (instanceConfig) {

        self.instanceConfig = instanceConfig;

        RcmBrightCoveEventManager.trigger('setInstanceConfig', self);
    };

    RcmBrightCoveEventManager.on('templateLoad-' + instanceId, function (experienceID) {

        self.player = brightcove.api.getExperience(experienceID);
        self.APIModules = brightcove.api.modules.APIModules;
        self.mediaEvent = brightcove.api.events.MediaEvent;

        self.videoPlayer = self.player.getModule(self.APIModules.VIDEO_PLAYER);
        self.videoPlayer.addEventListener(self.mediaEvent.BEGIN, self.onMediaBegin);
        self.videoPlayer.addEventListener(self.mediaEvent.COMPLETE, self.onMediaComplete);

        self.templateReady = true;
    });

    RcmBrightCoveEventManager.on('templateReady-' + instanceId, function () {
        if (self.loadVideoIdWhenReady) {
            self.cueVideoById(self.loadVideoIdWhenReady);
        }
    });

    self.loadVideoById = function (videoId, callback) {

        self.videoPlayer.loadVideoByID(videoId);

        self.getDownloadURL(videoId, callback);
    };

    self.cueVideoById = function (videoId, callback) {

        if (self.templateReady) {

            // onTemplateReady has already been called so we can just cue the video
            self.videoPlayer.cueVideoByID(videoId);
        } else {

            // onTemplateReady hasn't been called yet so we set this property to be used when it does get called
            self.loadVideoIdWhenReady = videoId;
        }

        self.getDownloadURL(videoId, callback);
    };

    self.onMediaBegin = function (evt) {
        //displayName  = evt.media.displayName;
        RcmBrightCoveEventManager.trigger('onMediaBegin', self);
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

        /**
         * Sometimes the tabs directive loads before us and misses our event, other times
         * we load before the tabs directive and miss its event. Doing it "both ways" below
         * prevents this.
         */
        RcmBrightCoveEventManager.trigger('playlistsBuilt-' + instanceId, self);
        RcmBrightCoveEventManager.on('tabDirectiveReady-' + instanceId, function () {
            RcmBrightCoveEventManager.trigger('playlistsBuilt-' + instanceId, self);
        });
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

    /**
     * init
     * @param onComplete
     */
    self.init = function (onComplete) {

        self.buildPlaylist(
            function (thisPlayer) {

                if (thisPlayer.videoId) {
                    thisPlayer.cueVideoById(thisPlayer.videoId)
                }

                if (typeof onComplete === 'function') {
                    onComplete(thisPlayer);
                }
            }
        );
    };

    self.init(onComplete);
};