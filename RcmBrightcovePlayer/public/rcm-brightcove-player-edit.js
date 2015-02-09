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

    var form = container.find('form');
    var type;
    var typeSelect = form.find('select[name=type]');
    var videoSelect = form.find('select[name=video]');
    var aspectRatioSelect = form.find('select[name=aspectRatio]');
    var renderCorrectOptionForm = function () {
        type = typeSelect.val();
        form.find('.single-embed-options').hide();
        form.find('.multi-embed-options').hide();
        form.find('.' + type + '-options').show();
    };
    var renderVidPreview = function () {
        if (videos[videoSelect.val()]) {
            form.find('img.thumbnailPreview').attr('src', videos[videoSelect.val()].thumbnailURL);
        }
    };
    var videos = [];

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {
        container.dblclick(me.showEditDialog);

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

        RcmBrightcoveApiService.requestVideoList(
            function (items) {
                videos = items;
                $.each(items, function () {
                    var option = $('<option></option>');
                    option.val(this.id);
                    option.html(this.name);
                    videoSelect.append(option)
                });
            }
        );

        videoSelect.change(renderVidPreview);
        typeSelect.change(renderCorrectOptionForm);
    };

    /**
     * Called by content management system to get this plugins instanceConfig for saving
     * on the server
     *
     * @return {Object}
     */
    this.getSaveData = function () {
        return RcmBrightcovePlayerService.getPlayerController(instanceId).instanceConfig;
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    this.showEditDialog = function () {


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

        var playerController = RcmBrightcovePlayerService.getPlayerController(instanceId);
        var type = playerController.instanceConfig.type;
        typeSelect.val(type);
        videoSelect.val(playerController.instanceConfig['videoId']);
        aspectRatioSelect.val(playerController.instanceConfig.aspectRatio);
        renderVidPreview();
        renderCorrectOptionForm();
        form.dialog(
            {
                width: '660px',
                position: 'center',
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    "OK": function () {
                        type = typeSelect.val();
                        playerController.instanceConfig.aspectRatio = aspectRatioSelect.val();
                        playerController.instanceConfig['type'] = type;

                        if (type == 'single-embed') {
                            playerController.instanceConfig['videoId']
                                = videoSelect.val();

                        } else {

                            playerController.instanceConfig['playlistIds'] = [];

                            var lis = form.find('ul.playlist-list li');

                            $.each(lis, function () {
                                var playlistId = $(this).attr('data-id');
                                playerController.instanceConfig['playlistIds'].push(parseInt(playlistId));
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
            //@TODO MOVE EVERYTHING IN THIS DIRECTIVE TO THE JQUERY CONTROLLER ABOVE AND REMOVE THIS DIRECTIVE
            var instanceId = attrs.rcmBrightcovePlayerEdit;
            scope.videos = [];
            scope.selectedVideos = null;

            RcmBrightcoveApiService.requestPlaylist(
                function (data) {
                    var allOfPlaylists = {};
                    jQuery.extend(allOfPlaylists, data.items);
                    var selectedIds = RcmBrightcovePlayerService.getPlayerController(instanceId).instanceConfig['playlistIds'];
                    scope.unselectedPlaylist = [];
                    scope.selectedPlaylist = [];
                    jQuery.each(allOfPlaylists, function () {

                        var pos = $.inArray(this.id.toString(), selectedIds);
                        if (pos == -1) {
                            scope.unselectedPlaylist.push(this);
                        } else {
                            scope.selectedPlaylist.push(this);
                        }
                    });
                    scope.$apply();
                }
            );
        };

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
                '<ul id="sortable1" class="droptrue connectedSortable" style="height:200px">' +
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
                '<ul id="sortable2" class="droptrue playlist-list" style="height:200px">' +
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
                '</div><div style="clear:both"></div>'
        }
    }
);
