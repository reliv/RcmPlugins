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


                // This was moved from a down below

                //window['rcmEdit'].refreshEditors(container);
                rcm.angularCompile(container);

                //Add right click menu
                $.contextMenu({
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


        $(function () {
            $("ul.droptrue").sortable({
                connectWith: "ul"
            });

            $("ul.droptrue").sortable({
                connectWith: "ul",
                dropOnEmpty: true
            });

            $("#sortable1, #sortable2").disableSelection();
        });

        form.dialog({
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

                        getVideoId = form.find(".singleVideo").html();

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
        });
    };
};
