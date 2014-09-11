rcm.addAngularModule('rcmBrightcovePlayer');
angular.module('rcmBrightcovePlayer', [])
    .directive(
    'rcmBrightcovePlayerDownloadLink',
    function () {

        var registeredEvent = false;

        return {
            link: function (scope, elm, attrs) {

                var instanceId = attrs.rcmBrightcovePlayerDownloadLink;

                if (!registeredEvent) {

                    RcmBrightcovePlayerService.eventManager.on(
                        'downloadUrlChange',
                        function (playerCtrl) {
                            if (instanceId == playerCtrl.instanceId) {
                                scope.$apply();
                            }
                        }
                    );

                    scope.playerController = RcmBrightcovePlayerService.getPlayerController(
                        instanceId
                    );

                    registeredEvent = true;
                }
            },

            template: '<a href="{{playerController.downloadUrl}}" ng-show="playerController.downloadUrl"><span data-textEdit="download" ng-model="playerController.instanceConfig.download">{{playerController.instanceConfig.download}}</span></span></a>'
        };
    }
)
    .directive(
        'rcmBrightcovePlayer',
        [
            '$parse',
            function ($parse) {

                return {
                    compile: function (tElm, tAttrs) {

                        var instanceId = tAttrs.rcmBrightcovePlayer;
                        var instanceConfig = JSON.parse(tAttrs.rcmBrightcovePlayerConfig);

                        var objectElm = tElm.children(":first");

                        var playerController = RcmBrightcovePlayerService.buildPlayerController(
                            instanceId,
                            instanceConfig,
                            function () {
                                console.log('rcmBrightcovePlayer build complete');
                            }
                        );

                        objectElm.attr('id', playerController.experienceId);

                        if (playerController.videoId) {
                            objectElm.append('<param name="@videoPlayer" value="' + playerController.videoId + '"/>');
                        }

                        if (playerController.onTemplateLoad) {
                            objectElm.append('<param name="templateLoadHandler" value="RcmBrightcovePlayerService.getPlayerController(\'' + instanceId + '\').onTemplateLoad"/>');
                        }

                        if (playerController.onTemplateReady) {
                            objectElm.append('<param name="templateReadyHandler" value="RcmBrightcovePlayerService.getPlayerController(\'' + instanceId + '\').onTemplateReady"/>');
                        }

                        return function (scope, elm, attrs) {

                            scope.playerController = playerController;
                        };
                    },

                    restrict: 'A',
                    template: '<!-- Start of Brightcove Player -->' +
                        '<object id="myExperienceXXX" class="BrightcoveExperience">' +
                        ' <param name="bgcolor" value="#FFFFFF"/>' +
                        ' <param name="width" value="{{playerController.instanceConfig.playerConfig.width}}"/>' +
                        ' <param name="height" value="{{playerController.instanceConfig.playerConfig.height}}"/>' +
                        ' <param name="playerID" value="{{playerController.instanceConfig.playerConfig.playerID}}"/>' +
                        ' <param name="playerKey" value="{{playerController.instanceConfig.playerConfig.playerKey}}" />' +
                        ' <param name="isVid" value="true"/>' +
                        ' <param name="isUI" value="true"/>' +
                        ' <param name="dynamicStreaming" value="true"/>' +
                        ' <param name="mute" value="true"/>' +
                        ' <param name="secureConnections" value="true"/>' +
                        ' <param name="secureHTMLConnections" value="true"/>' +
                        ' <param name="wmode" value="opaque"/>' +
                        ' <param name="includeAPI" value="true" />' +
                        //' <param name="@videoPlayer" value="{{playerController.videoId}}"/>' +
                        //' <param name="templateLoadHandler" value="RcmBrightcovePlayerService.getPlayerController(\'' + instanceId + '\').onTemplateLoad"/>' +
                        //' <param name="templateReadyHandler" value="RcmBrightcovePlayerService.getPlayerController(\'' + instanceId + '\').onTemplateReady"/>' +
                        '</object>' +
                        '<!-- End of Brightcove Player -->'
                }
            }
        ]
    )
    .directive(
    'rcmBrightcovePlayerTabs',
    function () {

        var registeredEvent = false;

        var updateTabs = function (elm, playlists, onComplete) {

            console.log('updateTabs');

            var tabWrapperElm = elm.find('.rcm-brightcove-player-tabs-wrapper');

            var rcmBrightcovePlayerTabs = tabWrapperElm.find('.rcmBrightcovePlayerTabs');
            var rcmBrightcovePlayerTabsContent = tabWrapperElm.find('.rcmBrightcovePlayerTabs');
            rcmBrightcovePlayerTabs.html('');

            rcmBrightcovePlayerTabsContent.html('');

            jQuery.each(
                playlists,
                function (key, playlist) {
                    rcmBrightcovePlayerTabs.append(
                        '<li><a href="#tabs-' + key + '">' + playlist.name + '</a></li>'
                    );

                    var tabContent = jQuery('<div class="videoAreaWrap" id="tabs-' + key + '" ></div>');

                    jQuery.each(
                        playlist.videos,
                        function (pkey, video) {
                            tabContent.append(
                                ' <div ng-repeat="video in tplaylist.videos">' +
                                    '  <a href="javascript:void(0);" ng-click="videoClick(video.id)" class="videoArea" >' +
                                    '    <table>' +
                                    '     <tr>' +
                                    '      <td style="text-align: left;">' +
                                    '       <img src="' + video.thumbnailURL + '" width="135px" height="70px"/>' +
                                    '       </td>' +
                                    '      </tr>' +
                                    '      <tr>' +
                                    '       <td>' +
                                    '        <span class="title">' +
                                    '         <p style="text-decoration: none; color: #00a4e4; font-weight: bold; font-size: 10px;">' + video.name + '</p>' +
                                    '        </span>' +
                                    '       </td>' +
                                    '       </tr>' +
                                    '       <tr>' +
                                    '        <td>' +
                                    '         <span class="description">' +
                                    '          <p style="text-decoration: none;font-size: 10px;">' + video.shortDescription + '</p>' +
                                    '         </span>' +
                                    '        </td>' +
                                    '       </tr>' +
                                    '      </table>' +
                                    '   </a>' +
                                    ' </div>'
                            );
                        }
                    );

                    tabWrapperElm.append(tabContent);
                }
            );

            tabWrapperElm.tabs();

            if(typeof onComplete === 'function'){
                onComplete();
            }
        };

        var link = function (scope, elm, attrs) {

            var instanceId = attrs.rcmBrightcovePlayerTabs;

            var instanceConfig = JSON.parse(attrs.rcmBrightcovePlayerTabsConfig);

            scope.testme = 'test';

            // disable click until player is ready
            scope.videoClick = function (videoId) {

                return false;
            };

            if (!registeredEvent) {

                RcmBrightcovePlayerService.eventManager.on(
                    'playlistsBuilt',
                    function (playerCtrl) {

                        scope.playlists = playerCtrl.playlists;

                        console.log('rcmBrightcovePlayerTabs.on.playlistsBuilt', scope.playlists.length);

                        if (instanceId == playerCtrl.instanceId) {

                            scope.videoClick = function (videoId) {

                                playerCtrl.loadVideoById(videoId);
                            };
                            setTimeout(
                                function () {
                                    updateTabs(elm, scope.playlists, onComplete);
                                    scope.$apply();
                                }
                            );
                        }
                    }
                );
                registeredEvent = true;
            }
        };

        var compile = function (tElem, tAttr) {


            return link;
        };

        var controller = function ($scope) {
            $scope.testme = 'test2';
        };

        return {
            link: link,
            restrict: 'A'
        };
    }
)


/**
 * RcmBrightcovePlayerService
 * @type {{}}
 */
var RcmBrightcovePlayerService = {

    playerControllers: {},

    configs: {},

    events: {},
    eventManager: {

        on: function (event, method) {

            if (!RcmBrightcovePlayerService.events[event]) {
                RcmBrightcovePlayerService.events[event] = [];
            }

            RcmBrightcovePlayerService.events[event].push(method);
        },

        trigger: function (event, args) {

            if (RcmBrightcovePlayerService.events[event]) {
                jQuery.each(
                    RcmBrightcovePlayerService.events[event],
                    function (index, value) {
                        value(args);
                    }
                );
            }
        }
    },

    buildPlayerController: function (instanceId, instanceConfig, onComplete) {

        instanceConfig.playerConfig = RcmBrightcovePlayerService.playerConfig;

        var existing = RcmBrightcovePlayerService.getPlayerController(instanceId, onComplete);

        if (existing) {

            return existing;
        }

        if (instanceConfig.type == 'multi-embed') {

            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerMulti(instanceId, instanceConfig, onComplete);
        } else {

            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerSingle(instanceId, instanceConfig, onComplete);
        }

        return RcmBrightcovePlayerService.playerControllers[instanceId];
    },

    getPlayerController: function (instanceId, onComplete) {

        if (RcmBrightcovePlayerService.playerControllers[instanceId]) {

            if (typeof onComplete === 'function') {

                onComplete(RcmBrightcovePlayerService.playerControllers[instanceId]);
            }

            return RcmBrightcovePlayerService.playerControllers[instanceId];
        }

        console.warn('RcmBrightcovePlayerService: Config for ' + instanceId + ' not registered, use addConfig before get.')
        return null;
    },

    playerConfig: {
        width: 672,
        height: 378,
        playerID: 2660464878001,
        playerKey: "AQ~~,AAABWA8lTok~,NLWj-wltGTxQtoAwLzwEdE62BFMU_8At"
    }
};

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
            RcmBrightcovePlayerService.eventManager.trigger('downloadUrlChange', self);
        }
    };

    self.onTemplateLoad = function (experienceID) {
        console.log('RcmBrightcovePlayerSingle.onTemplateLoad')
    };

    self.onTemplateReady = function (evt) {
        console.log('RcmBrightcovePlayerSingle.onTemplateReady')

    };

    self.init = function (onComplete) {

        RcmBrightcoveApiService.getDownloadURL(
            self.videoId,
            function (url) {
                self.setDownloadUrl(url);
                RcmBrightcovePlayerService.eventManager.trigger('init', self);

                if (typeof onComplete === 'function') {
                    onComplete(self);
                }
            }
        );
    };

    self.init(onComplete);
};

/**
 * RcmBrightcovePlayerMulti
 * @param playerModel
 * @constructor
 */
var RcmBrightcovePlayerMulti = function (instanceId, instanceConfig, onComplete) {

    var self = this;
    self.instanceId = instanceId;
    self.instanceConfig = instanceConfig;
    self.downloadUrl = '';
    self.videoId = null; // active video
    self.experienceId = 'myExperience' + self.instanceId;

    self.templateReady = false;
    self.loadVideoIdWhenReady = 0;

    self.player;
    self.APIModules;
    self.videoPlayer;

    self.playlists = [];

    self.onTemplateLoad = function (experienceID) {
        console.log('onTemplateLoad: ' + experienceID);

        self.player = brightcove.api.getExperience(experienceID);
        self.APIModules = brightcove.api.modules.APIModules;
        self.mediaEvent = brightcove.api.events.MediaEvent;

        self.videoPlayer = self.player.getModule(self.APIModules.VIDEO_PLAYER);
        self.videoPlayer.addEventListener(self.mediaEvent.BEGIN, self.onMediaBegin);
        self.videoPlayer.addEventListener(self.mediaEvent.COMPLETE, self.onMediaComplete);

        self.templateReady = true;

        if (self.loadVideoIdWhenReady) {
            self.cueVideoById(self.loadVideoIdWhenReady);
        }
    };

    self.onTemplateReady = function (evt) {

        console.log('onTemplateReady: ');
    };

    self.loadVideoById = function (videoId, callback) {

        console.log('loadVideoById', videoId);

        self.videoPlayer.loadVideoByID(videoId);

        self.getDownloadURL(videoId, callback);
    };

    self.cueVideoById = function (videoId, callback) {

        console.log('cueVideoById: ' + videoId);

        if (self.templateReady) {

            // onTemplateReady has already been called so we can just cue the video
            self.videoPlayer.cueVideoByID(videoId);
            RcmBrightcovePlayerService.eventManager.trigger('cueVideo', videoId);
        } else {

            // onTemplateReady hasn't been called yet so we set this property to be used when it does get called
            self.loadVideoIdWhenReady = videoId;
        }

        self.getDownloadURL(videoId, callback);
    };

    self.onMediaBegin = function (evt) {
        //displayName  = evt.media.displayName;
        RcmBrightcovePlayerService.eventManager.trigger('onMediaBegin', self);
    }

    self.onMediaComplete = function (evt) {
        nextVideo++;
        if (nextVideo == videos.length) {
            nextVideo = 0;
        }

        self.videoPlayer.loadVideoByID(videos[nextVideo]);
        RcmBrightcovePlayerService.eventManager.trigger('onMediaComplete', self);
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

            console.log(self.downloadUrl + ' !== ' + url);

            self.downloadUrl = url;
            RcmBrightcovePlayerService.eventManager.trigger('downloadUrlChange', self);
        }
    };

    self.prepareData = function (data, callback) {

        console.log('prepareData', data.items);
        self.playlists = data.items;
        // set videoId to play fo first video
        self.videoId = self.playlists[0].videos[0].id;

        if (typeof callback === 'function') {
            callback(self);
        }

        RcmBrightcovePlayerService.eventManager.trigger('playlistsBuilt', self);
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
