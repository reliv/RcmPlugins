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
            $scope.locales = [];
            $scope.loading = false;//loadin ng-show set to false
            $scope.messageQuery = '';
            self.getLocales = function () {
                $scope.loading = true;//loadin ng-show set to true when getLocales is called
                $http({method: 'GET', url: self.url.locales}).//method get to get all locales
                    success(function (data, status, headers, config) {
                        $scope.locales = data;
                        $scope.loading = false;
                        // this callback will be called asynchronously
                        // when the response is available
                    }).
                    error(function (data, status, headers, config) {
                        $scope.loading = false;
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });

            }


            self.getLocales();
            $scope.selectedLocale = null;
            $scope.messages = [];
            $scope.loading = false;
            $scope.OpenLocale = function () {
                $scope.loading = true;//loadin ng-show set to true when ng change OpenLocale() is called
                var locale = $scope.selectedLocale;
                if (locale) {
                    $scope.loading = true;
                    $http({
                        method: 'GET',//method get to get selected locale
                        url: '/rcmi18n/messages/' + $scope.selectedLocale
                    }).
                        success(function (data, status, headers, config) {
                            $scope.messages = data;
                            $scope.loading = false;
                        }

                    ).
                        error(function (data, status, headers, config) {
                            $scope.loading = false;
                            // called asynchronously if an error occurs
                            // or server returns response with an error status.
                        });

                }
            }

            $scope.saveText = function (message) {
                $http({
                    method: 'PUT',//method put to update selected locale
                    url: '/rcmi18n/messages/' + $scope.selectedLocale + '/' + message.defaultText,
                    data: message
                }).
                    success(function (data, status, headers, config) {
                       // message = data;
                        message.dirty = false;
                    }

                ).
                    error(function (data, status, headers, config) {
                        alert('Couldn\'t save!');
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });


            }

        }


    ])
    .filter('filter', function () { //search for text and Default text

        var compareStr = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return stra.indexOf(strb) !== -1;
        }

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = [];
            angular.forEach(input, function (message) {
                if (compareStr(message.defaultText, query) || compareStr(message.text, query)) {
                    result.push(message);
                }

            });

            return result;
        };
    });
