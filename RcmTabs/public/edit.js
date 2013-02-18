/**
 * RcmPortalAnnouncementBox
 *
 * JS for editing RcmPortalAnnouncementBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmPortalAnnouncementBox
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   http://www.nolicense.com None
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
var RcmTabsEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmPortalAnnouncementBoxEdit}
     */
    var me = this;

    me.tabContainers = [];

    me.tabCount = 0;

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function(){

        me.refreshTabs();
        me.disablePropigationFromTitles();

        var tabs = container.find('li');

        me.tabCount = container.find('li').length;

        var selector = rcm.getPluginContainerSelector(instanceId);

        me.addRightClick(selector+' li', true);
        me.addRightClick(selector);

    };

    me.addRightClick = function(selector, addDelete) {
        var items = {
            add:{
                name:'Add New Tab',
                icon:'edit',
                callback:function () {
                    me.showAddDialog();
                }
            },
            sort: {
                name: 'Sort Tabs',
                icon:'edit',
                callback: function() {
                    me.refreshTabs(true);
                }
            }
        };

        if (addDelete) {
            items.delete = {
                name: 'Delete Tab',
                icon: 'delete',
                callback: function(){
                    me.deleteTab(this)
                }
            }
        }

        rcmEdit.pluginContextMenu({
            selector: selector,
            //Here are the right click menu options
            items: items
        });
    };

    me.deleteTab = function(tabToDelete) {
        var tabId = $(tabToDelete).find("a").attr("href");
        $(tabId).remove();
        $(tabToDelete).remove();
        me.refreshTabs();
        return;
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        me.tabContainers = [];

        $("#RcmRealPage .rcmTabs_"+instanceId).find('li').each(function(){
            me.tabContainers.push({
                'id' : $(this).attr('data-containerId'),
                'type' : $(this).attr('data-containerType')
            })
        });

        return {
            'containers': me.tabContainers
        }
    };

    me.getAssets = function(){
        return null;
    };

    me.showAddDialog = function () {
        var options = {
            'html' : 'HTML',
            'video' : 'Bright Cove Video'
        };

        var tabTypeSelector = $("<div id='tabType'></div>").dialogIn('select', 'Select Type', options, 'html');

        var form = $('<form></form>')
            .append(tabTypeSelector)
            .dialog({
                title:'Add New Tab',
                modal:true,
                width:620,
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {
                        me.addTab();
                        $(this).dialog("close");
                    }
                }
            });
    };

    me.addTab = function() {
        var newId = me.tabCount + 1;
        var tabType = $("#tabType").val();

        me.addTabTitle(newId, tabType);

        if (tabType == 'html') {
            me.addHtmlTab(newId);
        }

        me.refreshTabs();
        me.refreshEditors();
        me.disablePropigationFromTitles();

        me.tabCount = newId;
    };

    me.addTabTitle = function(newId, type) {
        var newLi = $('<li data-containerId="'+newId+'" data-containerType="'+type+'"></li>');
        var newA = $('<a href="#tab_'+newId+'"></a>');
        var newDiv = $('<div data-richedit="tab_title_'+newId+'">New Tab</div>');

        $(newA).append(newDiv);
        $(newLi).append(newA);

        $(container).find("ul").append(newLi);
    };

    me.addHtmlTab = function(newId) {
        var newDiv = $('<div id="tab_'+newId+'"></div>');
        var newHtmlContainer = $('<div data-richedit="tab_content_'+newId+'"></div>');
        var newDummyData = '<h1>Lorem ipsum</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque justosapien, convallis vehicula sollicitudin tristique, rhoncus vel enim.Maecenas mollis dignissim urna, et mollis augue tempus nec. Proin a orcinulla. Pellentesque laoreet orci vitae nisl viverra imperdiet. Nam eueuismod orci. Etiam adipiscing condimentum quam a venenatis. Fusce faucibuslacus non velit varius egestas. Nam molestie mattis sem quis cursus. Duissit amet nunc turpis. Mauris elit urna, dapibus id hendrerit eleifend,placerat at magna. Etiam ornare eleifend elit, vel blandit tellussagittis pretium. Vivamus ut dignissim purus.</p>';

        $(newHtmlContainer).html(newDummyData);
        $(newDiv).append(newHtmlContainer);

        $("#RcmRealPage .rcmTabs_"+instanceId).append(newDiv);
    };

    me.disablePropigationFromTitles = function() {
        $("#RcmRealPage .rcmTabs_"+instanceId).find("li").find("a").find("div").keydown(function(event){
            event.stopPropagation();
        })
    }

    me.refreshEditors = function() {
        var pluginContainer = $('#RcmRealPage div[data-rcmplugininstanceid="'+instanceId+'"]');
        rcmEdit.rcmPlugins.removeEdits(pluginContainer);
        rcmEdit.rcmPlugins.removeTextEdits(pluginContainer);
        $(pluginContainer).find("[data-richedit]").find("div").each(function(){
            var currentHtml = $(this).html();
            $(this).parent().html(currentHtml);
        })

        rcmEdit.rcmPlugins.initPluginRichEdits(pluginContainer);
        rcmEdit.rcmPlugins.initHtml5Edits(pluginContainer);


    }

    me.refreshTabs = function(sortable) {

        try{
            $("#RcmRealPage .rcmTabs_"+instanceId).tabs("destroy");
        } catch (err) {}

        var tabs = $("#RcmRealPage .rcmTabs_"+instanceId).tabs();

        if (sortable){
            tabs.find( ".ui-tabs-nav" ).sortable({
                axis: "x",
                stop: function() {
                    tabs.tabs( "refresh" );
                }
            });
        }

        me.disablePropigationFromTitles();
    };

};