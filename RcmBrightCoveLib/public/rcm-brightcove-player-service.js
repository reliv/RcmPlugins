/**
 * RcmBrightcovePlayerService
 * @type {{}}
 */
var RcmBrightcovePlayerService = {

    playerControllers: {},

    configs: {},

    instantiatePlayerController: function (instanceId, instanceConfig) {
        if (instanceConfig.type == 'multi-embed') {
            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerMulti(instanceId, instanceConfig);
        } else {
            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerSingle(instanceId, instanceConfig);
        }
        RcmBrightcovePlayerService.playerControllers[instanceId].playerConfig = RcmBrightcovePlayerService.playerConfig;
    },

    getPlayerController: function (instanceId) {
        return RcmBrightcovePlayerService.playerControllers[instanceId];
    },


    playerConfig: {
        width: 672,
        height: 378,
        playerID: 2660464878001,
        playerKey: "AQ~~,AAABWA8lTok~,NLWj-wltGTxQtoAwLzwEdE62BFMU_8At"
    }
};

var RcmPlayerControllerOnTemplateLoad = function (pArgument) {
    var instanceId = pArgument.replace(/myExperience/gi, "");

    BrightCoveEventManager.trigger('templateLoad-' + instanceId, pArgument);
};

var RcmPlayerControllerOnTemplateReady = function (pArgument) {
    /* Fix for IOS */
    var experienceId = pArgument.target.experience['id'];
    var instanceId = experienceId.replace(/myExperience/gi, "");

    BrightCoveEventManager.trigger('templateReady-' + instanceId, pArgument);
};