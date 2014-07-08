/**
 * Created by idavis on 7/2/14.
 */
angular.module('rcmLocales', [])
   .controller('rcmTranslations', ['$scope', '$log', '$http',
        function ($scope, $log, $http) {
           var self = this;


           self.url = {
                locales: '/rcmi18n/messages'
            };
           $scope.locales = null;
           $scope.loading = false;
           self.getLocales = function () {
               $scope.loading = true;
               $http({method: 'GET', url: self.url.locales}).
                   success(function(data, status, headers, config) {
                       $scope.locales = data;
                       $scope.loading = false;
                       // this callback will be called asynchronously
                       // when the response is available
                   }).
                   error(function(data, status, headers, config) {
                       $scope.loading = false;
                       // called asynchronously if an error occurs
                       // or server returns response with an error status.
                   });
           }

            self.getLocales();


        }]);