rcm.angularBootstrap('playerTabs', 'playerTabsCtrl');

var app = angular.module('playerTabs', [])
    .controller('playerTabsCtrl', function ($scope) {
        var instanceId;

        $scope.init = function(playlistIds, passedInstanceId) {
            instanceId = passedInstanceId;
            findPlaylistsById(playlistIds);
        };

        function renderTabs(data) {
            $scope.playlists = data.items;
            var firstVideo = $scope.playlists[0].videos[0];
            window['bgPlayerApi'+instanceId].cueVideoById(firstVideo.id);

            $scope.$apply();

            $('#rcm-brightcove-player-tabs').tabs();
        }

        function findPlaylistsById(playlist_ids) {

            var data = $.ajax({
                type: 'POST',
                url: 'http://api.brightcove.com/services/library?command=find_playlists_by_ids&playlist_ids=' + playlist_ids + '&video_fields=id,name,thumbnailURL,shortDescription&page_size=100&page_number=0&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
                dataType: 'jsonp',
                success: renderTabs
            });

        }

    })

