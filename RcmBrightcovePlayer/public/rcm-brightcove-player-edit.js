/**
 * requires AjaxPluginEditHelper which should be included by rcm-admin
 * RcmCallToActionBox
 *
 * JS for editing RcmCallToActionBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmBrightcovePlayerEdit = function (instanceId, container, pluginHandler) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     */
    var me = this;

    /**
     * Settings from db
     * @type {Object}
     */
    var instanceConfig;

    /**
     * Default settings from config json file
     * @type {Object}
     */
    var defaultInstanceConfig;

    var playerController = {};

    var ajaxEditHelper = new AjaxPluginEditHelper(instanceId, container, pluginHandler);

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {

//        playerController = RcmBrightcovePlayerService.getPlayerController(
//            instanceId,
//            function (playerCtrl) {
//
//                instanceConfig = playerCtrl.instanceConfig;
//
//                defaultInstanceConfig = instanceConfig;
//
//                container.dblclick(me.showEditDialog);
//
//                // This was moved from a down below
//                pluginHandler.updateView();
//
//                //Add right click menu
//                $.contextMenu(
//                    {
//                        selector: rcm.getPluginContainerSelector(instanceId),
//                        //Here are the right click menu options
//                        items: {
//                            edit: {
//                                name: 'Edit Properties',
//                                icon: 'edit',
//                                callback: function () {
//                                    me.showEditDialog();
//                                }
//                            }
//                        }
//                    }
//                );
//            }
//        );

        ajaxEditHelper.ajaxGetInstanceConfigs(
            function (returnedData, returnedDefaultData) {

                instanceConfig = returnedData;

                defaultInstanceConfig = returnedDefaultData;

                RcmBrightcovePlayerService.dedefaultInstanceConfig = defaultInstanceConfig;

                container.dblclick(me.showEditDialog);

                // This was moved from a down below
                pluginHandler.updateView();

                //Add right click menu
                $.contextMenu(
                    {
                        selector: rcm.getPluginContainerSelector(instanceId),
                        //Here are the right click menu options
                        items: {
                            edit: {
                                name: 'Edit Properties',
                                icon: 'edit',
                                callback: function () {
                                    me.showEditDialog();
                                }
                            }
                        }
                    }
                );
            }
        );

    };

    /**
     * Called by content management system to get this plugins instanceConfig for saving
     * on the server
     *
     * @return {Object}
     */
    this.getSaveData = function () {

        return instanceConfig;
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    this.showEditDialog = function () {

        //$(".hide-vid").hide();

        var form = container.find('form');

        $(function () {
            $("ul.droptrue").sortable(
                {
                    connectWith: "ul"
                }
            );

            $("ul.droptrue").sortable(
                {
                    connectWith: "ul",
                    dropOnEmpty: true
                }
            );

            $("#sortable1, #sortable2").disableSelection();
        });

        form.dialog(
            {
                width: '660px',
                position: 'center',
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    "OK": function () {

                        var selection = form.find("[name=selection]").attr("data-selection");

                        instanceConfig['type'] = selection;

                        if (selection == 'single-embed') {

                            var getVideoId = form.find(".singleVideo").html();

                            instanceConfig['videoId'] = $.trim(getVideoId);

                        } else {

                            instanceConfig['playlistIds'] = [];

                            var lis = form.find('ul.playlist-list li');

                            $.each(lis, function () {
                                var playlistId = $(this).attr('data-id');
                                instanceConfig['playlistIds'].push(parseInt(playlistId));
                            });
                        }

                        $(this).dialog("close");
                    }
                }
            }
        );
    };
};
rcm.addAngularModule('rcmBrightcovePlayerEdit');
angular.module('rcmBrightcovePlayerEdit', [])
    .directive(
    'rcmBrightcovePlayerEdit',
    function () {

        var link = function (scope, elm, attrs) {

            // var instanceId = attrs.rcmBrightcovePlayerEdit;
            var instanceConfig = JSON.parse(attrs.rcmBrightcovePlayerEditConfig);

            scope.videos = [];
            scope.selectedVideos = null;

            function fillDropdownList(items) {

                scope.videos = items;
                scope.selectedVideos = scope.videos[0];
                scope.$apply();
            };

            RcmBrightcoveApiService.requestVideoList(fillDropdownList);

            function processMultiselectResponse(data) {

                var allOfPlaylists = {};
                jQuery.extend(allOfPlaylists, data.items);
                var selectedIds = instanceConfig['playlistIds'];
                scope.unselectedPlaylist = [];
                scope.selectedPlaylist = [];
                jQuery.each(allOfPlaylists, function () {

                    var pos = $.inArray(this.id, selectedIds);
                    if (pos == -1) {
                        scope.unselectedPlaylist.push(this);
                    } else {
                        scope.selectedPlaylist.push(this);
                    }
                });

                scope.$apply();
            };

            RcmBrightcoveApiService.requestPlaylist(processMultiselectResponse);

            scope.selectedItem = instanceConfig.type;

            scope.items = [
                { id: 'single-embed', name: 'single embed' },
                { id: 'multi-embed', name: 'tabbed video player' }
            ];
        }

        return {
            link: link
        }
    }
)
    .directive(
    'rcmBrightcovePlayerEditMultiSelect',
    function () {
        return {
            compile: function (el, attr, linker) {

                return function (scope, $element, $attr) {

                }
            },
            template: '' +
                '<div>' +
                '<ul id="sortable1" class="droptrue connectedSortable" >' +
                '<span style="padding: 5px;">' +
                '        <strong>Unselected Playlists</strong>' +
                '    </span>' +
                '    <li' +
                '    style="height:20px;"' +
                '    class="ui-state-default"' +
                '    ng-repeat="playlist in unselectedPlaylist"' +
                '    data-first-video="{{playlist.videoIds.0}}"' +
                '    data-id="{{playlist.id}}"' +
                '    >' +
                '        {{playlist.name}}' +
                '    </li>' +
                '</ul>' +
                '<ul id="sortable2" class="droptrue playlist-list">' +
                '    <span style="padding: 5px;">' +
                '        <strong>Selected Playlists</strong>' +
                '    </span>' +
                '    <li class="ui-state-highlight" ' +
                '    ng-repeat="playlist in selectedPlaylist" ' +
                '    data-first-video="{{playlist.videoIds.0}}" ' +
                '    data-id="{{playlist.id}}" ' +
                '    style="min-height: 15px"' +
                '    >' +
                '        {{playlist.name}}' +
                '    </li>' +
                '</ul>' +
                '</div>' +
                '</div>'
        }
    }
);
