/**
 * RcmGoogleSearchBox
 *
 * JS for editing RcmGoogleSearchBox
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
var RcmGoogleSearchBoxEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmGoogleSearchBoxEdit}
     */
    var me = this;

    var searchForm = container.find('form');

    var propName = 'Search Box Properties';

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {

        //Disable button click
        container.find('button').click(function () {
            event.preventDefault();
        });

        //Double clicking will show properties dialog
        container.delegate('input, button', 'dblclick', function () {
            me.showEditDialog();
        });

        //Add right click menu
        $.contextMenu({
            selector: rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit ' + propName,
                    icon: 'edit',
                    callback: function () {
                        me.showEditDialog();
                    }
                }

            }
        });


    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return {
            resultPage: searchForm.attr('action'),
            googleKey: container.find('input[name=cx]').val()
        }
    };


    me.showEditDialog = function () {

        var resultPage = $.dialogIn(
            'text', 'Search Result Page URL', searchForm.attr('action')
        );
        var googleKey = $.dialogIn(
            'text', 'Google Search Key', container.find('input[name=cx]').val()
        );

        var form = $('<form></form>')
            .addClass('simple')
            .append(resultPage, googleKey)
            .dialog({
                title: propName,
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Get user-entered data from form
                        searchForm.attr('action', resultPage.val());
                        container.find('input[name=cx]').val(googleKey.val());

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};