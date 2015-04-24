
/**
 * RcmBrightcovePlayerSingle
 * @param playerModel
 * @constructor
 */
var RcmBrightcovePlayerSingle = function (instanceId, instanceConfig) {

    var self = this;
    self.instanceId = instanceId;
    self.instanceConfig = instanceConfig;
    self.downloadUrl = '';
    self.videoId = instanceConfig.videoId;
    self.experienceId = 'myExperience' + self.instanceId;

    self.setDownloadUrl = function (url) {

        if (self.downloadUrl !== url) {
            self.downloadUrl = url;
            RcmBrightCoveEventManager.trigger('downloadUrlChange-'+instanceId, self);
        }
    };

    self.init = function () {

        RcmBrightcoveApiService.getDownloadURL(
            self.videoId,
            function (url) {
                self.setDownloadUrl(url);
                RcmBrightCoveEventManager.trigger('init', self);
            }
        );
    };

    self.init();
};