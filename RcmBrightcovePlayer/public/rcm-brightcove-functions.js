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
        var data = $.ajax({
            url: 'http://api.brightcove.com/services/library?command=search_videos&video_fields=id,name,thumbnailURL,shortDescription,renditions&page_size=100&sort_by=publish_date:desc&page_number=' + page + '&get_item_count=true&token=' + bgReadToken,
            dataType: 'jsonp',
            success: processSearchVideoResponse
        });
    }

    requestPage(0);


}

function requestPlaylist(callback) {
    var data = $.ajax({
        type: 'POST',
        url: 'http://api.brightcove.com/services/library?command=find_all_playlists&video_fields=id,name,thumbnailURL,shortDescription&page_size=100&page_number=0&get_item_count=true&token=' + bgReadToken,
        dataType: 'jsonp',
        success: callback
    });

}

function getDownloadURL(video_id, callback) {
    var data = $.ajax({
        type: 'POST',
        url: 'http://api.brightcove.com/services/library?command=find_video_by_id&video_id=' + video_id + '&video_fields=FLVURL,renditions&token=' + bgUrlToken + '&media_delivery=HTTP',
        dataType: 'jsonp',
        success: function (data) {
            renditions = data.renditions;

            biggestFrameWidth = 0;
            biggestUrl = '';


            $.each(renditions, function () {
                if (this.frameWidth > biggestFrameWidth) {
                    biggestFrameWidth = this.frameWidth;
                    biggestUrl = this.url;
                }
            });

            callback(biggestUrl);

        }
    });

}

