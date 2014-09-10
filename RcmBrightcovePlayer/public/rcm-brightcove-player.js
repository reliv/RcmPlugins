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
                        var objectElm = tElm.children(":first");

                        var playerController = RcmBrightcovePlayerService.getPlayerController(
                            instanceId,
                            function () {

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

                            RcmBrightcovePlayerService.eventManager.on(
                                'cueVideo',
                                function (videoId) {

                                    console.log('rcmBrightcovePlayer.on.cueVideo' + videoId);
                                    scope.$apply();
                                }
                            );

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

                console.log('playerTabsCtrl--', instanceId);

                RcmBrightcovePlayerService.eventManager.on(
                    'playlistsBuilt',
                    function (playerCtrl) {

                        console.log('on.playlistsBuilt');
                        scope.playlists = playerCtrl.playlists;

                        if (instanceId == playerCtrl.instanceId) {

                            scope.$apply(
                                function(){
                                    setTimeout(function () {
                                        console.log('playerTabsCtrl.apply', elm);
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
    'playerTabsCtrl',
    function ($scope) {

        var instanceId;

        $scope.playlists = [];

        $scope.init = function (playlistIds, passedInstanceId) {
            console.log('playerTabsCtrl.init');
            instanceId = passedInstanceId;
            RcmBrightcoveApiService.findPlaylistsById(
                playlistIds,
                renderTabs
            );
        };

        function renderTabs(data) {

            console.log('playerTabsCtrl.renderTabs');
            $scope.playlists = data.items;
            var firstVideo = $scope.playlists[0].videos[0];
            window['bgPlayerApi' + instanceId].cueVideoById(firstVideo.id);

            $scope.$apply(
                function () {

                    $('#rcm-brightcove-player-tabs').tabs();
                }
            );
        }

        $scope.videoClick = function () {
            console.log('playerTabsCtrl.videoClick');
            var apiObjectName = 'bgPlayerApi' + instanceId;
            window[apiObjectName].loadVideoById(this.video.id);
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

    addConfig: function (instanceId, instanceConfig) {

        instanceConfig.playerConfig = RcmBrightcovePlayerService.playerConfig;

        RcmBrightcovePlayerService.configs[instanceId] = instanceConfig;
    },

    getPlayerController: function (instanceId, onComplete) {

        if (RcmBrightcovePlayerService.playerControllers[instanceId]) {

            if (typeof onComplete === 'function') {

                onComplete(RcmBrightcovePlayerService.playerControllers[instanceId]);
            }

            return RcmBrightcovePlayerService.playerControllers[instanceId];
        }

        if (!RcmBrightcovePlayerService.configs[instanceId]) {

            console.warn('RcmBrightcovePlayerService: Config for ' + instanceId + ' not registered.')
            return null;
        }

        var instanceConfig = RcmBrightcovePlayerService.configs[instanceId];

        if (instanceConfig.type == 'multi-embed') {

            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerMulti(instanceId, instanceConfig, onComplete);
        } else {

            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerSingle(instanceId, instanceConfig, onComplete);
        }

        return RcmBrightcovePlayerService.playerControllers[instanceId];
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
    self.firstVideo = null;

    self.onTemplateLoad = function (experienceID) {
        console.log('onTemplateLoad: ' + experienceID);

        player = brightcove.api.getExperience(experienceID);
        //APIModules = brightcove.api.modules.APIModules;
        console.log('heheheherheh');
    };

    self.onTemplateReady = function (evt) {

        videoPlayer = player.getModule(APIModules.VIDEO_PLAYER);

        console.log('onTemplateReady: ');

        console.log('onTemplateReady: loadVideoIdWhenReady', self.loadVideoIdWhenReady);

        self.templateReady = true;
        videoPlayer.addEventListener(brightcove.api.events.MediaEvent.BEGIN, self.onMediaBegin);
        videoPlayer.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, self.onMediaComplete);

        console.log('onTemplateReady: loadVideoIdWhenReady', self.loadVideoIdWhenReady);
        if (self.loadVideoIdWhenReady) {
            self.cueVideoById(self.loadVideoIdWhenReady);
        }
    };

    self.loadVideoById = function (videoId, callback) {

        videoPlayer.loadVideoByID(videoId);

        self.getDownloadURL(videoId, callback);
    };

    self.cueVideoById = function (videoId, callback) {

        console.log('cueVideoById: ' + videoId);

        if (self.templateReady) {
            // onTemplateReady has already been called so we can just cue the video
            if (self.loadVideoIdWhenReady == videoId) {
                self.loadVideoIdWhenReady = null;
            }

            self.videoId = videoId;
            videoPlayer.cueVideoByID(videoId);
            self.getDownloadURL(videoId, callback);
            console.log('cueVideoById.do: ' + videoId);
            RcmBrightcovePlayerService.eventManager.trigger('cueVideo', videoId);
        } else {
            // onTemplateReady hasn't been called yet so we set this property to be used when it does get called
            console.log('cueVideoById.save: ' + videoId);
            self.loadVideoIdWhenReady = videoId;
        }
    };

    self.onMediaBegin = function (evt) {
        //document.getElementById("mediaInfo").innerHTML = evt.media.displayName;
        RcmBrightcovePlayerService.eventManager.trigger('onMediaBegin', self);
    }

    self.onMediaComplete = function (evt) {
        nextVideo++;
        if (nextVideo == videos.length) {
            nextVideo = 0;
        }
        RcmBrightcovePlayerService.eventManager.trigger('onMediaComplete', self);
        videoPlayer.loadVideoByID(videos[nextVideo]);
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
        self.firstVideo = self.playlists[0].videos[0];

        RcmBrightcovePlayerService.eventManager.trigger('playlistsBuilt', self);

        if (typeof callback === 'function') {
            callback(self);
        }
    };

    self.buildPlaylist = function (onComplete) {

        RcmBrightcoveApiService.findPlaylistsById(
            self.instanceConfig.playlistIds,
            function (data) {
                self.prepareData(data, onComplete);
            }
        );
    };

    self.init = function (onComplete) {

        self.buildPlaylist(
            function (player) {

                self.cueVideoById(player.firstVideo.id, onComplete);

                RcmBrightcovePlayerService.eventManager.trigger('init', self);
            }
        );
    };

    self.init(onComplete);
};

// TEST
var MultiPlayer = new function () {
    var player,
        APIModules,
        videoPlayer,
        contentModule,
        videosAra,
        counter = 0;

    this.onTemplateLoad = function (experienceID) {
        player = brightcove.api.getExperience(experienceID);
        APIModules = brightcove.api.modules.APIModules;
    };

    this.onTemplateReady = function (evt) {
        videoPlayer = player.getModule(APIModules.VIDEO_PLAYER);
        contentModule = player.getModule(APIModules.CONTENT);
        contentModule.getPlaylistByID("1323984733001", onGetPlaylist);
        videoPlayer.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, onComplete);
    };

    this.onComplete = function (evt) {
        counter++;
        var toPlayID = videosAra[counter].id;
        videoPlayer.loadVideoByID(toPlayID);
    }

    this.onGetPlaylist = function (playlistDTO) {
        videosAra = playlistDTO.videos;
        videoPlayer.loadVideoByID(videosAra[0].id);
        for (var i = 0; i < playlistDTO.videoCount; i++) {
            videoSelect.innerHTML += '<option value="' + playlistDTO.videos[i].id + '">' + playlistDTO.videos[i].displayName + '</option>';
        }
    };

    this.onVideoSelected = function (evt) {
        var selectedIndex = videoSelect.selectedIndex;
        counter = selectedIndex;
        var toPlayID = videosAra[selectedIndex].id;
        videoPlayer.loadVideoByID(toPlayID);
    };
}