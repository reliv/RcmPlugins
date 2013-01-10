/**
 * RcmSocialButtons
 *
 * JS for editing RcmSocialButtons
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmSocialButtons
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmSocialButtonsEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmSocialButtonsEdit}
     */
    var me = this;

    /**
     * Config options from the module config
     * @type {Object}
     */
    var availableButtons = null;

    var data = null;

    /**
     * Called by content management system to make this plugin user-editable
     *

     */
    me.initEdit = function () {

        me.disableShareThis();

        $.getJSON(
            '/rcm-plugin-admin-proxy/rcm-social-buttons/' + instanceId
                + '/available-buttons',
            function(returnedData) {
                availableButtons = returnedData;
                if (me.haveDataAndAvailableButtons()) {
                    me.completeInitEdit();
                }
            }
        );

        $.getJSON(
            '/rcm-plugin-admin-proxy/rcm-social-buttons/' + instanceId
                + '/data',
            function success(returnedData) {
                data = returnedData;
                if (me.haveDataAndAvailableButtons()) {
                    me.completeInitEdit();
                }
            }
        );
    };

    me.haveDataAndAvailableButtons = function(){
        return (data!=null && availableButtons!=null);
    };
    
    /**
     * Completes the edit init after we get our data via ajax
     */
    me.completeInitEdit = function () {
        //Double clicking will show properties dialog
        container.delegate('.rcmSocialButtonsWrapper', 'dblclick', function (e) {
            e.preventDefault();
            me.showEditDialog();
        });

        //Add right click menu
        rcmEdit.pluginContextMenu({
            selector:rcm.getPluginContainerSelector(instanceId) + ' .rcmSocialButtonsWrapper',
            //Here are the right click menu options
            items:{
                edit:{
                    name:'Edit Properties',
                    icon:'edit',
                    callback:function () {
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
        return data;
    };

    /**
     * Disables the share this functionality so we can edit

     */
    me.disableShareThis = function () {
        //Disable the normal social sharing functionality
        container.find('*').click(function (e) {
            e.stopPropagation();
        });
    };

    /**
     * Displays a dialog box to edit href and image src
     *

     */
    me.showEditDialog = function () {

        //Create and show our edit dialog
        var form = $('<form></form>');
        form.addClass('simple');
        form.addSelect(
            'style',
            'Button Style',
            {
                '':'Small buttons',
                '_large':'Large buttons',
                '_hcount':'Buttons with horizontal counters',
                '_vcount':'Buttons with vertical counters'
            },
            data.style
        );
        var checkBoxDiv = $('<div></div>');
        checkBoxDiv.append('<label>Buttons</label><br>');
        me.iterateAvailableButtons(function (name, desc) {
            checkBoxDiv.addCheckBox(
                name,
                desc,
                me.buttonEnabled(name)
            );
        });
        checkBoxDiv.find('p').attr('style', 'margin:0,padding:0 0 0 10px');
        form.append(checkBoxDiv);
        form.addInput('href', '"ShareThis" Publisher Key:', data.shareThisKey);
        form.dialog({
            title:'Properties',
            modal:true,
            width:620,
            buttons:{
                Cancel:function () {
                    $(this).dialog("close");
                },
                Ok:function () {

                    //get style from form
                    data.style = form.find('[name=style]').val();

                    //get buttons from form
                    data.buttons = [];
                    me.iterateAvailableButtons(function (name) {
                        if (form.find('[name="' + name + '"]').prop("checked")) {
                            data.buttons.push(name);
                        }
                    data.shareThisKey = form.find('[name=href]').val();

                    });

                    me.render();

                    $(this).dialog("close");
                }
            }
        });
    };

    /**
     * Renders saveData into html on the page
     */
    me.render = function () {
        var contDiv = container.children('.rcmSocialButtonsWrapper');
        contDiv.empty();
        me.iterateAvailableButtons(function (name) {
            if (me.buttonEnabled(name)) {
                var span = $('<span></span>');
                span.addClass('st_' + name + data.style);
                contDiv.append(span);
            }
        });
        stButtons.locateElements();
        me.disableShareThis();
    };

    /**
     * Checks if an available button is currently enabled
     *
     * @param {String} name button name
     * @return {Boolean}
     */
    me.buttonEnabled = function (name) {
        return ($.inArray(name, data.buttons) != -1)
    };

    /**
     * Calls a callback function for each available button
     *
     * @param {Function} callBack callback function

     */
    me.iterateAvailableButtons = function (callBack) {
        for (var name in availableButtons) {
            callBack(name, availableButtons[name]);
        }
    }
};