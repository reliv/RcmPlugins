var RcmIssuuDocument = function() {
    var me = this;

    me.id = '';
    me.name = '';
    me.title = '';
    me.username = '';
    me.thumbnail = '';
    me.embedHtml = '';

    me.setId = function ( id ) {
        me.id = id;
    };

    me.getId = function() {
        return me.id;
    };

    me.setName = function ( name ) {
        me.name = name;
    };

    me.getName = function() {
        return me.name;
    };

    me.setTitle = function( title ) {
        me.title = title;
    };

    me.getTitle = function() {
        return me.title;
    };

    me.setUsername = function( username ) {
        me.username = username;
    };

    me.getUserName = function() {
        return me.username;
    };

    me.setThumbnail = function( thumbnail ) {
        var fixed = thumbnail.replace(/http:|https:/i, '');;
        me.thumbnail = fixed;
    };

    me.getThumbnail = function() {
        return me.thumbnail;
    };

    me.setEmbedHtml = function( embedHtml ) {
        me.embedHtml = embedHtml;
    };

    me.getEmbedHtml = function() {
        return me.embedHtml;
    };

    me.populate = function ( data ) {
        me.setEmbedHtml(data.html);
        me.setThumbnail(data.thumbnail_url);
        me.setUsername(data.author_name);
        me.setTitle(data.title);
    }
};