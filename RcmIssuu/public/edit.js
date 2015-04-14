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
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmIssuuEdit = function (instanceId, container) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     */
    var me = this;
    me.instanceId = instanceId;
    me.container = container;

    me.initEdit = function () {

        //Double clicking will show properties dialog
        container.delegate('div', 'dblclick', function () {
            me.showEditDialog();
        });

        //Add right click menu
        $.contextMenu({
            selector: '[data-rcmPluginInstanceId="' + instanceId + '"]',
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


    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {

        return {
            'rssFeedUrl': me.feedUrl,
            'rssFeedLimit': me.feedLimit
        }
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    me.showEditDialog = function () {

        var feedUrl = $.dialogIn(
            'text', 'Search', me.feedUrl
        );

        var searchButton = $('<input type="button" class="searchButton" value="Search" />');

        var docListHolder = $('<div class="docListHolder" />');

        searchButton.click(function(){
            me.fetchList(feedUrl.val(), docListHolder);
        });


        //Create and show our edit dialog
        var form = $('<form>')
            .append(feedUrl, searchButton, docListHolder)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        //Grab the non-jquery form so we can get its fields
                        var domForm = form.get(0);

                        //Get user-entered data from form
                        me.feedUrl = feedUrl.val();
                        me.feedLimit = feedLimit.val();

                        new RssReader(
                            me.feedProxy,
                            me.instanceId,
                            $(".rss-feed-" + me.instanceId),
                            me.feedUrl,
                            me.feedLimit
                        );

                        $(this).dialog("close");
                    }
                }
            });

    };

    me.fetchList = function (search, holderDiv) {
        $.getJSON('https://search.issuu.com/api/2_0/document?q='+search+'&username=reliv&pageSize=30&jsonCallback=?', function(data){
            me.handleResponse(data.response, holderDiv);
        });
    };

    me.handleResponse = function ( response, holderDiv ) {
        var start = response.start;
        var totalCount = response.numFound;
        var numReturned = response.docs.length;

        $.each(response.docs, function(i, v) {
            me.handleDocument(v, holderDiv);
        });
    };

    me.handleDocument = function( document, holderDiv ) {
        console.log(document);
        var radio = $('<input type="radio" name="docSelection" class="docSectionRadio" value="'+document.documentId+'" />');
        var docName = $('<span class="docName">'+document.docname+'</span>');
        var docUserName = $('<span class="docUserName">'+document.username+'</span>');

        var documentDivContainer = $('<div class="docDivContainer" />');

        documentDivContainer.append(radio, docName, docUserName);

        holderDiv.append(documentDivContainer)
    };

};