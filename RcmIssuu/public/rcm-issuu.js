$(function(){
    $.getJSON('https://issuu.com/oembed?url=http%3A%2F%2Fissuu.com%2Fiscience%2Fdocs%2Fissue23&format=json&jsonCallback=?', function(data) {
        console.log(data);
    })
});

