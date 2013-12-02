/**
 *  The following code is needed for every use of angular JS (with modification using variables specific to plug-in).
 *  Because of the following declaration, the directive 'ng-app' is NOT needed in the view
 */

angular.element(document).ready(function () {
    $.each($('[ng-controller=BrightcoveCtrl]'), function (key, element) {
        angular.bootstrap(element, ['brightcovePlayer']);
    });
});

/**
 * Angular JS controller for this plugin
 * @param $scope
 * @constructor
 */
var brightcovePlayer = angular.module('brightcovePlayer', []);

brightcovePlayer.controller('BrightcoveCtrl', function BrightcoveCtrl($scope) {

     singleEmbedDropdownList(function(items) {
            $scope.videos = items;
            $scope.selectedVideos = $scope.videos[0];
            $scope.$apply();
     });

    function collectData(data) {
        console.log(data.items)
        $scope.playlists = data.items;
        $scope.selectedPlaylists = $scope.playlists[0];
        $scope.$apply();
    }

    function requestPlaylist() {
        var data = $.ajax({
            type: 'POST',
            url: 'http://api.brightcove.com/services/library?command=find_all_playlists&video_fields=id,name,thumbnailURL&page_size=100&page_number=0&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
            dataType: 'jsonp',
            success: collectData
        });
    }
    requestPlaylist();

    $scope.items = [
        { id: 0, name: 'single embed' },
        { id: 1, name: 'multiple video player' }
    ];
    $scope.expression = "<h1>this is a test</h1>";
});
