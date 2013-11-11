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
    $scope.choices = true;
    $.brightcove.find_all_videos().done(function (data) {
        //Do something with the API
        $scope.videos = data.items;
        $scope.selectedVideos = $scope.videos[0];
        $scope.$apply();
    });
    $.brightcove.find_all_playlists(false).done(function (data) {
        //Do something with the API
        $scope.playlists = data.items;
        $scope.selectedPlaylists = $scope.playlists[0];
        $scope.$apply();
    });
    $scope.items = [
        { id: 0, name: 'single embed' },
        { id: 1, name: 'multiple video player' },
    ];

//    video_id = 2764326442001;
//    $.brightcove.find_video_by_id(video_id).done(function (data) {
//        //Do something with the API
//        console.log(data);
//        $scope.video = data.items;
//        $scope.$apply();
//       // console.log($scope.video);
//    });
});
$(function(){
$('#brightcoveform').dialog({ width: '600px', position: 'center'});


});
