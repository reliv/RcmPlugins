/**
 * Angular JS controller for this plugin
 * @param $scope
 * @constructor
 */
angular.element(document).ready(function () {
    $.each($('[ng-controller=BrightcoveCtrl]'), function (key, element) {
        angular.bootstrap(element, ['brightcovePlayer']);
    });
});

//************************************************************************************//

//var brightcovePlayer = angular.module('brightcovePlayer', []);
//
//brightcovePlayer.controller('BrightcoveCtrl', function BrightcoveCtrl($scope) {
//
//$.brightcove.find_all_playlists().done(function(data) {
//
//    console.log(data);
//
//}).fail(function(data) {
//        //handle the error
//    });
//
//});
//$.brightcove.find_all_videos().done(function(data) {
//    //Do something with the API
//    videoArray = data.items;
//    //console.log(videoArray[0].name);
//    $.each(videoArray, function(key, ele) {
//        console.log(ele.name);
//    });
//}).fail(function(data) {
//        //handle the error
//    });

