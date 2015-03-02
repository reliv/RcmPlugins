
/**
 * RcmBrightcovePlayerSingle
 * @param playerModel
 * @constructor
 */
var RcmBrightcovePlayerSingle = function (instanceId, instanceConfig, onComplete) {

    var self = this;
    self.instanceId = instanceId;
    self.instanceConfig = instanceConfig;
    self.downloadUrl = '';
    self.videoId = instanceConfig.videoId;
    self.experienceId = 'myExperience' + self.instanceId;

    self.setDownloadUrl = function (url) {

        if (self.downloadUrl !== url) {

            self.downloadUrl = url;
            BrightCoveEventManager.trigger('downloadUrlChange', self);
        }
    };

    self.setInstanceConfig = function (instanceConfig) {

        self.instanceConfig = instanceConfig;

        BrightCoveEventManager.trigger('setInstanceConfig', self);
    }

    self.init = function (onComplete) {

        RcmBrightcoveApiService.getDownloadURL(
            self.videoId,
            function (url) {
                self.setDownloadUrl(url);
                BrightCoveEventManager.trigger('init', self);

                if (typeof onComplete === 'function') {
                    onComplete(self);
                }
            }
        );
    };

    self.init(onComplete);
};