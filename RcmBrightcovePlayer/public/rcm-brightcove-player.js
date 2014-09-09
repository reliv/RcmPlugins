rcm.addAngularModule('playerTabs');
angular.module('playerTabs', [])

    .directive(
    'rcmBrightcovePlayerDownloadLink',
    function () {
        return {
            link: function (scope, elm, attrs) {

                var instanceId = attrs.rcmBrightcovePlayerDownloadLink;

                scope.playerController = RcmBrightcovePlayerService.getPlayerController(
                    instanceId,
                    function(player) {
                        scope.$apply();
                    }
                );

//                if (scope.playerController) {
//                    scope.playerController.getDownloadURL(
//                        playerController.vide
//                        function (url) {
//                            attrs.$set('href', url);
//                        }
//                    );
//                }
            },

            template: '<pre>{{playerController | json}}</pre><span data-textEdit="download" href="{{playerController.downloadUrl}}" ng-model="playerController.instanceConfig.download">{{playerController.instanceConfig.download}}</span>'
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

                        var videoId = tAttrs.rcmBrightcovePlayer;

                        var playerModel = RcmBrightcovePlayerService.getPlayerModel(videoId);

                        var objectElm = tElm.children(":first");

                        objectElm.attr('id', 'myExperience' + videoId);

                        objectElm.append('<param name="@videoPlayer" value="' + videoId + '"/>');

                        if (playerModel.templateLoadHandler) {
                            objectElm.append('<param name="templateLoadHandler" value="RcmBrightcovePlayerService.getPlayerModel(\'' + videoId + '\').templateLoadHandler"/>');
                        }

                        if (playerModel.templateReadyHandler) {
                            objectElm.append('<param name="templateLoadHandler" value="RcmBrightcovePlayerService.getPlayerModel(\'' + videoId + '\').templateReadyHandler"/>');
                        }

                        return function (scope, elm, attrs) {

                            scope.playerModel = playerModel;
                        };
                    },

                    restrict: 'A',
                    template: '<!-- Start of Brightcove Player -->' +
                        '<object id="myExperienceXXX" class="BrightcoveExperience">' +
                        ' <param name="bgcolor" value="#FFFFFF"/>' +
                        ' <param name="width" value="672"/>' +
                        ' <param name="height" value="378"/>' +
                        ' <param name="playerID" value="2660464878001"/>' +
                        ' <param name="playerKey" value="AQ~~,AAABWA8lTok~,NLWj-wltGTxQtoAwLzwEdE62BFMU_8At" />' +
                        ' <param name="isVid" value="true"/>' +
                        ' <param name="isUI" value="true"/>' +
                        ' <param name="dynamicStreaming" value="true"/>' +
                        ' <param name="mute" value="true"/>' +
                        ' <param name="secureConnections" value="true"/>' +
                        ' <param name="secureHTMLConnections" value="true"/>' +
                        //' <param name="@videoPlayer" value="' + videoId + '"/>' +
                        //' <param name="templateLoadHandler" value="RcmBrightcovePlayerService.getPlayerModel(\'{{playerModel.videoId}}\').templateLoadHandler"/>' +
                        //' <param name="templateReadyHandler" value="RcmBrightcovePlayerService.getPlayerModel("' + videoId + '").templateReadyHandler"/>' +
                        '</object>' +
                        '<!-- End of Brightcove Player -->'
                }
            }
        ]
    )
    .directive(
    'playerTabsCtrl',
    function () {

        var playerController

        var compile = function (tElem, tAttr) {



            var link = function (scope, elm, attrs) {

                var instanceId = tAttr.playerTabsCtrl;

                playerController = RcmBrightcovePlayerService.getPlayerController(
                    instanceId,
                    function (player) {

                        scope.playlists = player.playlists;

                        scope.$apply(
                            function () {

                                elm.find('#rcm-brightcove-player-tabs').tabs();
                            }
                        );
                    }
                )
            };

            return link;
        };

        var controller = function ($scope) {

            $scope.videoClick = function (videoId) {

                playerController.loadVideoById(videoId);
            };
        };


        return {
            compile: compile,
            controller: controller,
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

            if(typeof onComplete === 'function'){

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

            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerMulti(instanceConfig, onComplete);
        } else {

            RcmBrightcovePlayerService.playerControllers[instanceId] = new RcmBrightcovePlayerSingle(instanceConfig, onComplete);
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
var RcmBrightcovePlayerSingle = function (instanceConfig) {

    var self = this;
    self.instanceConfig = instanceConfig;
    self.downloadUrl = '';
};

/**
 * RcmBrightcovePlayerMulti
 * @param playerModel
 * @constructor
 */
var RcmBrightcovePlayerMulti = function (instanceConfig, onComplete) {

    var self = this;
    self.instanceConfig = instanceConfig;
    self.templateReady = false;
    self.loadVideoIdWhenReady = 0;
    self.instanceConfig = instanceConfig;

    self.downloadUrl = '';

    self.player = null;
    self.modVP = null;

    self.playlists = [];
    self.firstVideo = null;

    self.templateLoadHandler = function (experienceID) {
        self.player = brightcove.api.getExperience(experienceID);
        self.modVP = self.player.getModule(brightcove.api.modules.APIModules.VIDEO_PLAYER);
    };

    self.templateReadyHandler = function (evt) {

        self.templateReady = true;
        self.modVP.addEventListener(brightcove.api.events.MediaEvent.BEGIN, self.onMediaBegin);
        self.modVP.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, self.onMediaComplete);
        if (self.loadVideoIdWhenReady) {
            self.cueVideoById(self.loadVideoIdWhenReady);
        }
    };

    self.loadVideoById = function (videoId) {

        self.modVP.loadVideoByID(videoId);

        self.getDownloadURL(videoId, self.setDownloadUrl);
    };

    self.cueVideoById = function (videoId) {

        if (self.templateReady) {
            // onTemplateReady has already been called so we can just cue the video
            self.modVP.cueVideoByID(videoId);
        } else {
            // onTemplateReady hasn't been called yet so we set this property to be used when it does get called
            self.loadVideoIdWhenReady = videoId;
        }

        self.getDownloadURL(videoId, self.setDownloadUrl);
    };

    self.onMediaComplete = function (evt) {
        nextVideo++;
        if (nextVideo == videos.length) {
            nextVideo = 0;
        }
        self.modVP.loadVideoByID(videos[nextVideo]);
    };

    self.getDownloadURL = function (videoId, callback) {

        return RcmBrightcoveApiService.getDownloadURL(videoId, callback);
    };

    self.setDownloadUrl = function (url) {

        self.downloadUrl = url;
    }

    self.prepareData = function (data, callback) {

        self.playlists = data.items;
        self.firstVideo = self.playlists[0].videos[0];

        self.cueVideoById(self.firstVideo.id);

        if (typeof callback === 'function') {

            callback(self);
        }
    }

    self.buildPlaylist = function(onComplete) {

        RcmBrightcoveApiService.findPlaylistsById(
            self.instanceConfig.playlistIds,
            function (data) {
                self.prepareData(data, onComplete);
            }
        );
    }

    self.init = function(onComplete){

        self.buildPlaylist(onComplete);
    }

    self.init(onComplete);
};
