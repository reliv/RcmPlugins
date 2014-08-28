/**
 * Created by idavis on 7/15/14.
 */

rcm.addAngularModule('rcmAdminPage');
angular.module('rcmAdminPage', [])
    .controller('PageProperties',
    function ($scope) {
        var self = this;
        //getting title, description and keywords from dom to our form
        $scope.title = $('title').html();
        $scope.description = $('description').html();
        $scope.keywords = $('keywords').html();
        //save function
        $scope.save = function () {

            //if title tag doesn't exists then adding it to head
            if ($('title').length == 0) {
                $('head').append($('title'));
            }
            else {
                $('title').html($scope.title);
            }
            var meta = $('<meta>');
            var metaDesciption = $('meta[name="description"]');

            //if meta description doesn't exists then adding it to head
            if (metaDesciption.length == 0) {
                meta.attr('name', 'description');
                meta.attr('content', $scope.description);
                $('head').append(meta);

            }
            else {
                metaDesciption.attr('content', $scope.description);
            }
            var metaKeywords = $('meta[name="keywords"]');
            var metaK = $('<meta>');
            //if meta keywords doesn't exists then adding it to head
            if (metaKeywords.length == 0) {
                metaK.attr('name', 'keywords');
                metaK.attr('content', $scope.keywords);
                $('head').append(metaK);
            }
            else {
                metaKeywords.attr('content', $scope.keywords);
            }
        }
    }
);