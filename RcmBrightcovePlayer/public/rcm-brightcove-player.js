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
    var items = [];

    function processSearchVideoResponse(data) {

        items =  items.concat(data.items);

        console.log(items);

        var nextPage = (data['page_number'] + 1);
//        console.log(data['page_number'], 'page_number');
        var newNum = (nextPage * data['page_size']);

        //if there are more pages call request page
        if (newNum < data['total_count']) {
//            console.log(data['page_number'], 'page_number');
            requestPage(nextPage);
        } else {
            //Do something with the API
            //  $scope.videos = data.items;
            $scope.videos = items;
            $scope.selectedVideos = $scope.videos[0];
            $scope.$apply();
        }
    }


    function requestPage(page) {
        var data = $.ajax({
            url: 'http://api.brightcove.com/services/library?command=search_videos&video_fields=id,name&page_size=100&sort_by=publish_date:desc&page_number=' + page + '&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
            dataType: 'jsonp',
            success: processSearchVideoResponse
        });
    }

    //  console.log('im here');

    //1 ms

    requestPage(0);

    //2ms

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
    $scope.expression = "<h1>this is a test</h1>";
});
