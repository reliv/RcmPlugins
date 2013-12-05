
angular.element(document).ready(function () {
    $.each($('[ng-controller=playerTabsCtrl]'), function (key, element) {
        angular.bootstrap(element, ['playerTabs']);
    });
});
console.log('here');
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

        function findPlaylistsById() {
            console.log('%c^*^*^*^*^*^*^*^*  FIND PLAYLISTS BY ID *^*^*^*^*^*^*^*^*^*^', "background: green; color:white; font-size: large");
            var data = $.ajax({
                type: 'POST',
                url: 'http://api.brightcove.com/services/library?command=find_playlists_by_ids&playlist_ids=1519039001001,1787088394001,1787088391001&video_fields=id,name,thumbnailURL&page_size=100&page_number=0&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
                dataType: 'jsonp',
                success: renderTabs
            });

        }

        findPlaylistsById();

    })

function requestPlaylist(callback) {
    console.log('=============================================== PLAYLISTS: =================================================');
    var data = $.ajax({
        type: 'POST',
        url: 'http://api.brightcove.com/services/library?command=find_all_playlists&video_fields=id,name,thumbnailURL&page_size=100&page_number=0&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
        dataType: 'jsonp',
        success: callback
    });
    console.log('EEEEEEEeeeeeeeeeeeeeeyeEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEeeeeeeeeyeeeeeeOOOOOOOOOOOOOOOOOO');
}