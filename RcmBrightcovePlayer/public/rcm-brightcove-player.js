rcm.addAngularModule('playerTabs');
var app = angular.module('playerTabs', [])
    .controller('playerTabsCtrl', function ($scope) {
        var instanceId;

        $scope.init = function (playlistIds, passedInstanceId) {
            instanceId = passedInstanceId;
            findPlaylistsById(playlistIds);
        };

        function renderTabs(data) {
            $scope.playlists = data.items;
            var firstVideo = $scope.playlists[0].videos[0];
            window['bgPlayerApi' + instanceId].cueVideoById(firstVideo.id);

            $scope.$apply();

            $('#rcm-brightcove-player-tabs').tabs();
        }

        function findPlaylistsById(playlist_ids) {

            var data = $.ajax({
                type: 'POST',
                url: 'http://api.brightcove.com/services/library?command=find_playlists_by_ids&playlist_ids=' + playlist_ids + '&video_fields=id,name,thumbnailURL,shortDescription&page_size=100&page_number=0&get_item_count=true&token=' + bgReadToken,
                dataType: 'jsonp',
                success: renderTabs
            });

        }

        $scope.videoClick = function () {
            var apiObjectName = 'bgPlayerApi' + instanceId;
            window[apiObjectName].loadVideoById(this.video.id);
        };

    }
).controller(
    'PlayerEditCtrl',
    function PlayerEditCtrl($scope) {

        $scope.init = function (instanceConfig) {

            function fillDropdownList(items) {

                $scope.videos = items;
                $scope.selectedVideos = $scope.videos[0];
                $scope.$apply();
            }

            singleEmbedDropdownList(fillDropdownList);

            function processMultiselectResponse(data) {

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

            }

            requestPlaylist(processMultiselectResponse);
        };

        $scope.items = [
            { id: 'single-embed', name: 'single embed' },
            { id: 'multi-embed', name: 'tabbed video player' }
        ];
    }
);
