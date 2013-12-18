/**
 * Synchronously grab dependency object file(s)
 */
$.ajax({
    async: false,
    url: '/modules/rcm/js/admin/ajax-plugin-edit-helper.js',
    instanceConfigType: 'script'
});

/**
 * RcmCallToActionBox
 *
 * JS for editing RcmCallToActionBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmProfileForm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmBrightcovePlayerEdit = function (instanceId, container) {

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

    var ajaxEditHelper = new AjaxPluginEditHelper(instanceId, container, 'rcm-brightcove-player');

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {
        ajaxEditHelper.ajaxGetInstanceConfigs(
            function (returnedData, returnedDefaultData) {
                instanceConfig = returnedData;
                defaultInstanceConfig = returnedDefaultData;

                container.dblclick(me.showEditDialog);

                //Add right click menu
                window['rcmEdit'].pluginContextMenu({
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
                });
            }
        );

        window['rcmEdit'].refreshEditors(container);

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

        $(".hide-vid").hide();

        var form = container.find('form');

        $(function() {
            $( "#sortable1, #sortable2" ).sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        });

        form.dialog({
            width: '600px',
            position: 'center',
            buttons: {
                Cancel: function () {
                    $(this).dialog("close");
                },
                "OK": function () {

                 var selection = form.find("[name=selection]").attr("data-selection");

                 instanceConfig['type'] = selection;

                 if (selection == 'single-embed') {

                    getVideoId = form.find(".singleVideo").html();
                    instanceConfig['videoId'] = getVideoId;

//                    processVideoIdResponse(getVideoId);


                 } else {

                        instanceConfig['playlistIds'] = [];

                        var lis = form.find('ul.playlist-list li');

                        var first = lis.attr('data-first-video');

                        instanceConfig['videoId'] = parseInt(first);

                        $.each(lis, function () {
                            var playlistId = $(this).attr('data-id');
                            console.log(playlistId, 'PLAYLISTID');
                            instanceConfig['playlistIds'].push(parseInt(playlistId));
                        });
                    }
//
//                    console.log(instanceConfig['playlistIds'], '**** PLAYLIST ID ****');
//                    console.log(instanceConfig['videoId']);
                    $(this).dialog("close");
                }
            }
        });
    };
};


/**
 *  The following code is needed for every use of angular JS (with modification using variables specific to plug-in).
 *  Because of the following declaration, the directive 'ng-app' is NOT needed in the view
 */

angular.element(document).ready(function () {
    $.each($('[ng-controller=PlayerEditCtrl]'), function (key, element) {
        angular.bootstrap(element, ['playerEditModule']);
    });
});

/**
 * Angular JS controller for this plugin
 * @param $scope
 * @constructor
 */

var playerEditModule = angular.module('playerEditModule', []);
playerEditModule.controller('PlayerEditCtrl', function PlayerEditCtrl($scope) {

    singleEmbedDropdownList(function (items) {
        $scope.videos = items;
        $scope.selectedVideos = $scope.videos[0];
        $scope.$apply();
    });


    $scope.init = function (instanceConfig) {

        requestPlaylist(function (data) {
            var allOfPlaylists = data.items;
            var selectedIds = instanceConfig['playlistIds'];
            $scope.unselectedPlaylist = [];
            $scope.selectedPlaylist = [];
            $.each(allOfPlaylists, function () {

                var pos = $.inArray(this.id, selectedIds);
                if (pos == -1) {
                    $scope.unselectedPlaylist.push(this);
                } else {
                    $scope.selectedPlaylist.push(this);
                }
            });


            $scope.$apply();

        });
    };

    $scope.items = [
        { id: 'single-embed', name: 'single embed' },
        { id: 'multi-embed', name: 'multiple video player' }
    ];


});
