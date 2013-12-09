angular.element(document).ready(function () {
    $.each($('[ng-controller=playerTabsCtrl]'), function (key, element) {
        angular.bootstrap(element, ['playerTabs']);
    });
});

var app = angular.module('playerTabs', [])
    .controller('playerTabsCtrl', function ($scope) {


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

        // this is where the fix will be

//        console.log(instanceConfig['playlistId']);
//        var playlists = $('form').find('[name=playlist]').attr('data-value');
//
//            console.log(playlists, 'lllllllllllllllllllllllllllllllllllllllllllllllllllll');
           playlist_ids = '1787088394001';

            findPlaylistsById(playlist_ids);




    })

