/**
 * rcmAdminMenu
 *  @require:
 *  AngularJS
 *  rcm (rcm core)
 *  RcmDialog
 */
angular.module(
        'rcmAdminMenu',
        ['RcmDialog']
    )
/**
 * rcmAdminMenu.RcmAdminMenu
 */
    .directive(
        'RcmAdminMenu',
        [
            '$log',
            'rcmDialogService',
            function ($log, rcmDialogService) {

                var thisLink = function (scope, elm, attrs) {

                    var htlmLink = elm.find("a");

                    htlmLink.on('click', null, null, function (event) {

                        event.preventDefault();

                        // get strategyName
                        var strategyName = 'DEFAULT';

                        if (elm[0].classList[1]) {
                            strategyName = elm[0].classList[1];
                        }

                        var strategy = {
                            loading: true,
                            name: strategyName,
                            title: htlmLink.attr('title'),
                            url: htlmLink.attr('href')
                        }

                        rcmDialogService.openDialog(strategy, scope);
                    });

                };

                return {
                    restrict: 'C',
                    link: thisLink
                }
            }

        ]
    );
rcm.addAngularModule('rcmAdminMenu');