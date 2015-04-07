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
            function ($log) {

                var thisLink = function (scope, elm, attrs) {

                    var htlmLink = elm.find("a");

                    htlmLink.on('click', null, null, function (event) {

                        event.preventDefault();

                        // get strategyName
                        var strategyName = null;

                        var classAttr = elm.attr('class');

                        if (classAttr) {
                            var classes = classAttr.split(" ");
                            if(classes[1]){
                                strategyName = classes[1];
                            }
                        }

                        var rcmDialogActions = null;

                        if (attrs.rcmDialogActions) {
                            try {
                                rcmDialogActions = scope.$eval(attrs.rcmDialogActions);
                            } catch (e) {

                            }
                        }

                        var dialog = RcmDialog.buildDialog(
                            htlmLink.attr('href'), //id
                            htlmLink.attr('title'),
                            htlmLink.attr('href'),
                            strategyName,
                            rcmDialogActions,
                            scope
                        );

                        dialog.open();
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