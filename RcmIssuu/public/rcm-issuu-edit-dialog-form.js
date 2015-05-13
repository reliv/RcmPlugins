/**
 * Dialog Form for Rcm Issuu
 *
 * @param {RcmIssuuApiProcessor} apiProcessor Api Processor
 * @constructor
 */
var RcmIssuuEditDialogForm = function( apiProcessor ) {
    var me = this;

    me.apiProcessor = apiProcessor;

    me.currentDocument = new RcmIssuuDocument();

    me.currentSubject = '';
    me.currentUsername = '';

    me.listHolder = $('<div class="docListHolder" />');
    me.currentlySelectedHolder = $('<div class="currentlySelectedBox" />');
    me.nextprevBox = $('<div class="nextPrevBox" style="display: none;" />');

    me.subjectSearchBox = $.dialogIn('text', 'Subject', '');
    me.subjectSearchBox.addClass('subjectSearchInput');
    me.subjectSearchBox.keypress(me.searchKeyPress);

    me.userNameSearchBox = $.dialogIn('text', 'User Name', '');
    me.userNameSearchBox.addClass('userNameSearchInput');
    me.userNameSearchBox.keypress(me.searchKeyPress);

    me.searchButton = $('<input type="button" class="documentSearchButton" value="Search" />');
    me.searchButton.click(function(){me.doSearch(0);});

    me.docSearchBar = $('<div class="docSearchBar" />');
    me.docSearchBar.append(me.subjectSearchBox, me.userNameSearchBox, me.searchButton, $('<div style="clear: both;" />'));

    me.form = $('<form>').append(me.currentlySelectedHolder, me.docSearchBar, me.nextprevBox, me.listHolder);

    me.searchKeyPress = function(e) {
        if(e.which == 13) {
            me.doSearch(0);
        }
    };

    me.doSearch = function (start) {
        me.listHolder.empty();
        me.nextprevBox.empty();
        me.nextprevBox.hide();

        me.currentSubject = me.subjectSearchBox.val();
        me.currentUsername = me.userNameSearchBox.val();
        me.apiProcessor.fetchList(me.currentSubject, me.currentUsername, start, 30, me);
    };

    me.changeSelected = function( document ) {
        me.apiProcessor.getDocInfo(document, function(document) {
            me.currentDocument = document;
            me.showCurrentlySelected();
       });
    };

    /**
     * Add a document to the list box
     *
     * @param {RcmIssuuDocument} document Rcm Issuu Document object
     * @return {void}
     */
    me.addDocumentToListing = function( document ) {
        var radioButton = $('<input ' +
            'type="radio" ' +
            'name="docSelection" ' +
            'class="docSectionRadio" ' +
            'id="'+document.getId()+'" ' +
            'value="'+document.getId()+'" />'
        );

        var docName = $('<span class="docName">'+document.getName()+'</span>');
        var docUserName = $('<span class="docUserName">'+document.getUserName()+'</span>');

        var documentLabelContainer = $('<label for="'+document.getId()+'" class="docDivContainer" />');
        documentLabelContainer.append(docName, docUserName);

        radioButton.click(function(){
            var radioId = radioButton.attr('id');
            radioButton.parent().find('label').removeClass('radioBoxSelected');
            radioButton.parent().find('label[for='+radioId+']').addClass('radioBoxSelected');
            me.changeSelected(document);
        });

        me.listHolder.append(radioButton, documentLabelContainer);
    };

    me.showNextButton = function ( nextStartIndex ) {
        var nextButton = $('<a class="docSearchNext">Next</a>').click(function(){
            me.doSearch(nextStartIndex);
        });

        me.nextprevBox.append(nextButton);
        me.nextprevBox.show();
    };

    me.showPrevButton = function ( prevStartIndex ) {
        var prevButton = $('<a class="docSearchPrev">Previous</a>').click(function(){
            me.doSearch(prevStartIndex);
        });

        me.nextprevBox.append(prevButton);
        me.nextprevBox.show();
    };

    me.showCurrentlySelected = function () {
        me.currentlySelectedHolder.empty();

        var image = $("<img />").attr('src', me.currentDocument.getThumbnail());
        var userName = $("<span />").html(me.currentDocument.getUserName());
        var title = $("<span />").html(me.currentDocument.getTitle());

        me.currentlySelectedHolder.append(image, userName, title);
    };

    me.getForm = function() {
        return me.form
    };

    me.getCurrentDocument = function() {
        return me.currentDocument;
    };

    me.initSelected = function(userName, name) {
        var document = new RcmIssuuDocument();
        document.setName(name);
        document.setUsername(userName);

        me.changeSelected(document);
    }
};