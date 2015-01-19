/**
 *
 */
angular.module('rcmMessage', ['rcmApi'])
    .controller(
    'rcmMessageList', [
        '$scope', 'rcmApiService',
        function ($scope, rcmApiService) {

            $scope.hiddenUserMessageIds = {};
            $scope.messageHiddenCount = 0;
            /**
             * Fire and forget - Tell server it was viewed
             * @param $userMessageId
             */
            $scope.dismissUserMessage = function ($userId, $userMessageId)
            {
                // Fire and forget - hide right away
                $scope.hiddenUserMessageIds[$userId+':'+$userMessageId] = true;
                $scope.messageHiddenCount ++;

                rcmApiService.put(
                    {
                        url: '/api/message/user/'+$userId+'/message/'+$userMessageId,
                        data: {
                            viewed: 1
                        },

                        loading: function (loading) {
                        },
                        success: function (data) {
                            //console.log('success', data);
                        },
                        error: function (data) {
                            //console.error('error', data);
                        }
                    },
                    true
                );
            }
        }
    ]
);
rcm.addAngularModule(
    'rcmMessage'
);


