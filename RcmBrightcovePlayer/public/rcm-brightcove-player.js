angular.element(document).ready(function () {
    $.each($('[ng-controller=playerTabsCtrl]'), function (key, element) {
        angular.bootstrap(element, ['playerTabs']);
    });
});

var app = angular.module('playerTabs', [])
    .controller('playerTabsCtrl', function ($scope) {

        $scope.init = function(data) {
            console.log(data, "++++++++++++++++++++++++++++++++++  INIT +++++++++++++++++++++++++++++++++");
            findPlaylistsById(data);
        };

        function renderTabs(data) {
            console.log(data.items);
            $scope.playlists = data.items;
            $scope.selectedPlaylists = $scope.playlists[0];
            $scope.$apply();

            console.log('%c^*^*^*^*^*^*^*^*      RENDER TABS      *^*^*^*^*^*^*^*^*^*^', "background: green; color:white; font-size: large");
            $('#rcm-brightcove-player-tabs').tabs();
        }

        function findPlaylistsById(playlist_ids) {
            console.log('%c^*^*^*^*^*^*^*^*  FIND PLAYLISTS BY ID *^*^*^*^*^*^*^*^*^*^', "background: green; color:white; font-size: large");
            var data = $.ajax({
                type: 'POST',
                url: 'http://api.brightcove.com/services/library?command=find_playlists_by_ids&playlist_ids=' + playlist_ids + '&video_fields=id,name,thumbnailURL&page_size=100&page_number=0&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
                dataType: 'jsonp',
                success: renderTabs
            });

        }

    })

