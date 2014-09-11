rcm.addAngularModule('playerTabs');
angular.module('playerTabs', [])

    .directive(
    'rcmBrightcovePlayerDownloadLink',
    function () {
        return {
            link: function (scope, elm, attrs) {

                var instanceId = attrs.rcmBrightcovePlayerDownloadLink;

                RcmBrightcovePlayerService.eventManager.on(
                    'downloadUrlChange',
                    function (playerCtrl) {
                        console.log('downloadUrlChange:' + instanceId + ' ' + playerCtrl.instanceId);

                        if (instanceId == playerCtrl.instanceId) {
                            scope.$apply();
                        }
                    }
                );

                scope.playerController = RcmBrightcovePlayerService.getPlayerController(
                    instanceId
                );
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
    'playerTabsCtrl',
    function () {

        var compile = function (tElem, tAttr) {

            var link = function (scope, elm, attrs) {

                var instanceId = tAttr.playerTabsCtrl;

                RcmBrightcovePlayerService.eventManager.on(
                    'playlistsBuilt',
                    function (playerCtrl) {

                        scope.playlists = playerCtrl.playlists;

                        if (instanceId == playerCtrl.instanceId) {

                            scope.$apply(
                                function () {
                                    setTimeout(function () {
                                        console.log('playerTabsCtrl.on.playlistsBuilt');
                                        elm.find('#rcm-brightcove-player-tabs').tabs();
                                    });
                                }
                            );
                        }
                    }
                );

                scope.playerController = RcmBrightcovePlayerService.getPlayerController(
                    instanceId,
                    function (playerCtrl) {
                        console.log('scope.playerController', scope.playerController.playlists);
                    }
                );

                scope.videoClick = function (videoId) {

                    scope.playerController.loadVideoById(videoId);
                };
            };

            return link;
        };

        return {
            compile: compile,
            restrict: 'A'
        };
    }
)
    .controller(
    'PlayerEditCtrl',
    function ($scope) {

        $scope.videos = [];
        $scope.selectedVideos = null;

        $scope.init = function (instanceConfig) {

            //console.log('PlayerEditCtrl.init');

            function fillDropdownList(items) {

                //console.log('PlayerEditCtrl.fillDropdownList');

                $scope.videos = items;
                $scope.selectedVideos = $scope.videos[0];
                $scope.$apply();
            };

            RcmBrightcoveApiService.requestVideoList(fillDropdownList);

            function processMultiselectResponse(data) {

                //console.log('PlayerEditCtrl.processMultiselectResponse', data.items);
                var allOfPlaylists = data.items;
                var selectedIds = instanceConfig['playlistIds'];
                $scope.unselectedPlaylist = [];
                $scope.selectedPlaylist = [];
                $.each(allOfPlaylists, function () {

                    var pos = $.inArray(this.id, selectedIds);
                    if (pos == -1) {
                        $scope.unselectedPlaylist.push(this);
                    } else {
                        $scope.selectedPlaylist.push(this);
                    }
                });

                $scope.$apply();

            };

            RcmBrightcoveApiService.requestPlaylist(processMultiselectResponse);
        };

        $scope.items = [
            { id: 'single-embed', name: 'single embed' },
            { id: 'multi-embed', name: 'tabbed video player' }
        ];
    }
);

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

//    addConfig: function (instanceId, instanceConfig, onComplete) {
//
//
//
//        RcmBrightcovePlayerService.buildPlayerController(instanceId, instanceConfig);
//    },

    buildPlayerController: function (instanceId, instanceConfig, onComplete) {

        instanceConfig.playerConfig = RcmBrightcovePlayerService.playerConfig;

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

        console.log('loadVideoById',videoId);

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
            function(thisPlayer) {

                if(thisPlayer.videoId){
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
