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

                        BrightCoveEventManager.on(
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
                        ' <param name="templateLoadHandler" value="RcmPlayerControllerOnTemplateLoad" />' +
                        ' <param name="templateReadyHandler" value="RcmPlayerControllerOnTemplateReady" />' +
                        //' <param name="@videoPlayer" value="{{playerController.videoId}}"/>' +
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

                            BrightCoveEventManager.on(
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