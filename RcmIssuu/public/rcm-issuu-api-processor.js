var RcmIssuuApiProcessor = function () {
    var me = this;

    me.defualtPageSize = 30;

    me.fetchList = function (subject, username, start, pageSize, form) {
        var url = 'https://search.issuu.com/api/2_0/document' +
            '?q=' + subject +
            '&username=' + username +
            '&pageSize=' + pageSize +
            '&startIndex='+ start +
            '&sortBy=username,docname' +
            '&jsonCallback=?';

        $.getJSON(url, function(data){
            me.handleResponse(data.response, form);
        });
    };

    me.handleResponse = function ( response, form ) {

        console.log(response);
        var start = response.start;
        var totalCount = response.numFound;

        var next = start + me.defualtPageSize;
        var prev = start - me.defualtPageSize;

        if (next+1 < totalCount) {
            form.showNextButton(next);
        }

        if (prev >= 0) {
            form.showPrevButton(prev);
        }

        $.each(response.docs, function(i, v) {
            var document = new RcmIssuuDocument();
            document.setId(v.documentId);
            document.setName(v.docname);
            document.setUsername(v.username);

            form.addDocumentToListing(document);
        });
    };

    me.getDocInfo = function ( document, callback ) {
        var url = '/issuu/'+ document.getUserName() +'/'+ document.getName();
        $.getJSON(url, function ( data ) {
            me.handleDocumentResponse(data, document, callback);
        });
    };

    me.handleDocumentResponse = function( data, document, callback ) {
        document.populate(data);
        callback(document);
    };
};