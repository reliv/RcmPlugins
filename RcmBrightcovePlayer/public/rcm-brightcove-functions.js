/*
   function singleEmbedDropdownList
 */
function singleEmbedDropdownList(callback) {

    var items = [];

    function processSearchVideoResponse(data) {

        items = items.concat(data.items);

        var nextPage = (data['page_number'] + 1);
        var newNum = (nextPage * data['page_size']);
        if (newNum < data['total_count']) {
            requestPage(nextPage);
        } else {
            callback(items);
        }
    }

    function requestPage(page) {
        console.log(page, '=============================================== PAGE: ' + page + ' =================================================');
        var data = $.ajax({
            url: 'http://api.brightcove.com/services/library?command=search_videos&video_fields=id,name,thumbnailURL&page_size=100&sort_by=publish_date:desc&page_number=' + page + '&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
            dataType: 'jsonp',
            success: processSearchVideoResponse
        });
    }

    requestPage(0);


}

function requestPlaylist(callback) {
    console.log('=============================================== INSIDE requestPlaylist() =================================================');
    var data = $.ajax({
        type: 'POST',
        url: 'http://api.brightcove.com/services/library?command=find_all_playlists&video_fields=id,name,thumbnailURL&page_size=100&page_number=0&get_item_count=true&token=FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
        dataType: 'jsonp',
        success: callback
    });
    console.log('%c^*^*^*^*^*^*^*^*^*^*^*^*  PLAYLIST data.items RETURNED ^*^*^*^*^*^*^*^*^*^*^*^*^*^*', "background: blue; color:white; font-size: large");
}

