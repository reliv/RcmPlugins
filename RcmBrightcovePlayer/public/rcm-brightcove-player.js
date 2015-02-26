rcm.addAngularModule('rcmBrightcovePlayer');
angular.module('rcmBrightcovePlayer', [])
    .directive(
    'rcmBrightcovePlayerDownloadLink',
    function () {

        var registeredEvent = false;

        return {
            compile: function (elm, attrs) {

                var instanceId = attrs.rcmBrightcovePlayerDownloadLink;

                var playerController = RcmBrightcovePlayerService.getPlayerController(
                    instanceId
                );

                return function (scope, elm, attrs) {

                    if (!registeredEvent) {

                        RcmBrightcovePlayerService.eventManager.on(
                            'downloadUrlChange',
                            function (playerCtrl) {
                                if (instanceId == playerCtrl.instanceId) {
                                    scope.$apply();
                                }
                            }
                        );

                        registeredEvent = true;
                    }

                    scope.playerController = playerController;
                };
            },

            template: '<a href="{{playerController.downloadUrl}}" ng-show="playerController.downloadUrl"><span data-textEdit="download" ng-model="playerController.instanceConfig.download">{{playerController.instanceConfig.download}}</span></span></a>'
        };
    }
)
    .directive(
        'rcmBrightcovePlayer',
        [
            '$compile',
            function ($compile) {

                return {
                    compile: function (tElm, tAttrs) {
                        var instanceId = tAttrs.rcmBrightcovePlayer;

                        var objectElm = tElm.children(":first");

                        var playerController = RcmBrightcovePlayerService.getPlayerController(
                            instanceId
                        );

                        objectElm.attr('id', playerController.experienceId);

                        if (playerController.videoId) {
                            objectElm.append('<param name="@videoPlayer" value="' + playerController.videoId + '"/>');
                        }

                        if (playerController.onTemplateLoad) {
                            objectElm.append('<param name="templateLoadHandler" value="RcmPlayerControllerOnTemplateLoad" />');
                        }

                        if (playerController.onTemplateReady) {
                            objectElm.append('<param name="templateReadyHandler" value="RcmPlayerControllerOnTemplateReady" />');
                        }

                        return function (scope, elm, attrs) {
                            scope.playerController = playerController;
                        };

                    },

                    restrict: 'A',

                    template: '<!-- Start of Brightcove Player -->' +
                        '<object id="myExperienceXXX" class="BrightcoveExperience">' +
                        ' <param name="bgcolor" value="#FFFFFF"/>' +
                        ' <param name="playerID" value="{{playerController.playerConfig.playerID}}"/>' +
                        ' <param name="playerKey" value="{{playerController.playerConfig.playerKey}}" />' +
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
        [
            '$compile',
            function ($compile) {

                var registeredEvent = false;


                var updateTabs = function (scope, elm, playlists, onComplete) {


                    var tabWrapperElm = elm.find('.rcm-brightcove-player-tabs-wrapper');

                    var rcmBrightcovePlayerTabs = tabWrapperElm.find('.rcmBrightcovePlayerTabs');
                    var rcmBrightcovePlayerTabsContent = tabWrapperElm.find('.tab-content');
                    rcmBrightcovePlayerTabs.html('');

                    rcmBrightcovePlayerTabsContent.html('');

                    scope.isHide = function () {
                        if (playlists.length == 1) {
                            return {
                                display: 'none'
                            }
                        }
                    };

                    jQuery.each(
                        playlists,
                        function (key, playlist) {
                            var tab = '';
                            var active = '';

                            if (key == 0) {
                                tab = '<li role="presentation" class="title active" data-tabid="#tabs-' + key + '">';
                                active = 'active';
                            } else {
                                tab = '<li role="presentation" class="title" data-tabid="#tabs-' + key + '">';
                            }
                            rcmBrightcovePlayerTabs.append(
                                tab + '<a href="#tabs-' + key + '" role="tab" aria-controls="tabs-' + key + '" data-toggle="tab">' + playlist.name + '</a></li>'
                            );

                            var tabContent = jQuery('<div role="tabpanel" class="tab-pane ' + active + '" id="tabs-' + key + '"></div>');


                            jQuery.each(
                                playlist.videos,
                                function (pkey, video) {
                                    tabContent.append(
                                        '  <a href="javascript:void(0);" ng-click="videoClick(' + video.id + ')" class="videoArea" >' +
                                            '    <table>' +
                                            '     <tr>' +
                                            '      <td style="text-align: left;">' +
                                            '       <img src="' + video.thumbnailURL + '" width="135px" height="70px"/>' +
                                            '       </td>' +
                                            '      </tr>' +
                                            '      <tr>' +
                                            '       <td>' +
                                            '        <span class="title">' +
                                            '         <p style="text-decoration: none; color: #333333; font-weight: bold; font-size: 10px;">' + video.name + '</p>' +
                                            '        </span>' +
                                            '       </td>' +
                                            '       </tr>' +
//                                    '       <tr>' +
//                                    '        <td>' +
//                                    '         <span class="description">' +
//                                    '          <p style="text-decoration: none;font-size: 10px;">' + video.shortDescription + '</p>' +
//                                    '         </span>' +
//                                    '        </td>' +
//                                    '       </tr>' +
                                            '      </table>' +
                                            '   </a>'
                                    );
                                }
                            );

                            tabWrapperElm.append(tabContent);
                            rcmBrightcovePlayerTabsContent.append(tabContent);
                        }
                    );

                    tabWrapperElm.tab();


                    tabWrapperElm.find('[href="#<?= $activeTabId ?>"]').trigger('click');

                    tabWrapperElm.tabCollapse(
                        {
                            accordionTemplate: function (heading, groupId, parentId, active) {
                                return '<div class="panel panel-default">' +
                                    '   <div class="panel-heading">' +
                                    '      <h2 class="panel-title">' +
                                    '        <a class="" data-toggle="collapse" data-parent="#' + parentId + '" href="#' + groupId + '<?= $noColapse ?>">' +
                                    '           ' + heading +
                                    '        </a>' +
                                    '      </h2>' +
                                    '   </div>' +
                                    '   <div id="' + groupId + '" class="panel-collapse collapse in">' +
                                    '       <div class="panel-body js-tabcollapse-panel-body">' +
                                    '       </div>' +
                                    '   </div>' +
                                    '</div>';
                            },
                            tabsClass: 'hidden-xs',
                            accordionClass: 'visible-xs'
                        }
                    );


                    $compile(tabWrapperElm.contents())(scope);

                    if (typeof onComplete === 'function') {
                        onComplete();
                    }


                };

                var controller = function ($scope) {

                    $scope.testme = 'test';

                    $scope.videoClick = function (videoId) {

                        $scope.playerController.loadVideoById(videoId);
                    };
                };


                var compile = function (tElem, tAttr) {

                    return function (scope, elm, attrs, controller, transcludeFn) {

                        scope.instanceId = attrs.rcmBrightcovePlayerTabs;

                        scope.playerController = RcmBrightcovePlayerService.getPlayerController(
                            scope.instanceId
                        );

                        if (!registeredEvent) {

                            var updateEvent = function (playerCtrl) {

                                scope.playlists = playerCtrl.playlists;

                                if (scope.instanceId == playerCtrl.instanceId) {
                                    setTimeout(
                                        function () {
                                            updateTabs(
                                                scope,
                                                elm,
                                                playerCtrl.playlists
                                            );
                                        },
                                        1
                                    );
                                }
                            };

                            RcmBrightcovePlayerService.eventManager.on(
                                'playlistsBuilt',
                                updateEvent
                            );

                            registeredEvent = true;
                        }
                    }
                };

                var template = '' +
                    '<div class="rcm-brightcove-player-tabs-wrapper">' +
                    ' <ul class="rcmBrightcovePlayerTabs">' +
                    '  <li ng-repeat="(key,playlist) in playlists">' +
                    '   {{playlist.videos.length | json}}' +
                    '   <a href="#tabs-{{key}}">{{playlist.name}}</a>' +
                    '  </li>' +
                    ' </ul>' +
                    '  <div class="videoAreaWrap" ng-repeat="(tkey,tplaylist) in playlists" id="tabs-{{tkey}}">' +
                    '   <div ng-repeat="video in tplaylist.videos">' +
                    '    <a href="javascript:void(0);" ng-click="videoClick(video.id)" class="videoArea">' +
                    '     <table>' +
                    '      <tr>' +
                    '       <td style="text-align: left;">' +
                    '        <img ng-src="{{video.thumbnailURL}}" width="135px" height="70px"/>' +
                    '       </td>' +
                    '      </tr>' +
                    '      <tr>' +
                    '       <td>' +
                    '        <span class="title">' +
                    '         <p style="text-decoration: none; color: #00a4e4; font-weight: bold; font-size: 10px;">' +
                    '         {{video.name}}' +
                    '         </p>' +
                    '        </span>' +
                    '       </td>' +
                    '      </tr>' +
                    '      <tr>' +
                    '       <td>' +
                    '        <span class="description">' +
                    '         <p style="text-decoration: none;font-size: 10px;">' +
                    '         {{video.shortDescription}}' +
                    '         </p>' +
                    '        </span>' +
                    '       </td>' +
                    '      </tr>' +
                    '     </table>' +
                    '    </a>' +
                    '   </div>' +
                    '  </div>' +
                    '</div>'

                return {
                    compile: compile,
                    controller: controller,
                    restrict: 'A' //template: template
                };
            }
        ]
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

    var playerController = RcmBrightcovePlayerService.getPlayerController(instanceId);
    playerController.onTemplateLoad(pArgument);
};

var RcmPlayerControllerOnTemplateReady = function (pArgument) {
    /* Fix for IOS */
    var experienceId = pArgument.target.experience['id'];
    var instanceId = experienceId.replace(/myExperience/gi, "");

    var playerController = RcmBrightcovePlayerService.getPlayerController(instanceId);
    playerController.onTemplateReady(pArgument);
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

    self.setInstanceConfig = function (instanceConfig) {

        self.instanceConfig = instanceConfig;

        RcmBrightcovePlayerService.eventManager.trigger('setInstanceConfig', self);
    }

    self.onTemplateLoad = function (experienceID) {
    };

    self.onTemplateReady = function (evt) {
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

    self.setInstanceConfig = function (instanceConfig) {

        self.instanceConfig = instanceConfig;

        RcmBrightcovePlayerService.eventManager.trigger('setInstanceConfig', self);
    }

    self.onTemplateLoad = function (experienceID) {

        self.player = brightcove.api.getExperience(experienceID);
        self.APIModules = brightcove.api.modules.APIModules;
        self.mediaEvent = brightcove.api.events.MediaEvent;

        self.videoPlayer = self.player.getModule(self.APIModules.VIDEO_PLAYER);
        self.videoPlayer.addEventListener(self.mediaEvent.BEGIN, self.onMediaBegin);
        self.videoPlayer.addEventListener(self.mediaEvent.COMPLETE, self.onMediaComplete);

        self.templateReady = true;
    };

    self.onTemplateReady = function (evt) {
        if (self.loadVideoIdWhenReady) {
            self.cueVideoById(self.loadVideoIdWhenReady);
        }
    };

    self.loadVideoById = function (videoId, callback) {

        self.videoPlayer.loadVideoByID(videoId);

        self.getDownloadURL(videoId, callback);
    };

    self.cueVideoById = function (videoId, callback) {

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
