var RcmBrightcoveApiService = {

    bgReadToken: null,

    bgUrlToken: null,

    downloadUrls: [],

    items: [],
    /*
     function requestVideoList
     */
    requestVideoList: function (callback, refresh) {

        // only go to the server once for videos
        if (RcmBrightcoveApiService.items.length > 0 && !refresh) {
            callback(RcmBrightcoveApiService.items);
            return;
        }

        RcmBrightcoveApiService.items = [];

        function processSearchVideoResponse(data) {

            RcmBrightcoveApiService.items = RcmBrightcoveApiService.items.concat(data.items);

            var nextPage = (data['page_number'] + 1);
            var newNum = (nextPage * data['page_size']);
            if (newNum < data['total_count']) {
                requestPage(nextPage);
            } else {
                var sortedItems = [];
                $.each(RcmBrightcoveApiService.items, function () {
                    sortedItems.push(this);
                });
                sortedItems.sort(function (a, b) {
                    a = a.name.toLowerCase();
                    b = b.name.toLowerCase();
                    if (a < b) {
                        return -1;
                    } else if (a > b) {
                        return  1;
                    } else {
                        return 0;
                    }
                });
                callback(sortedItems);
            }
        }

        function requestPage(page) {
            var data = $.ajax(
                {
                    url: 'https://api.brightcove.com/services/library?command=search_videos&video_fields=id,name,thumbnailURL,shortDescription,renditions&page_size=100&sort_by=publish_date:desc&page_number=' + page + '&get_item_count=true&token=' + RcmBrightcoveApiService.bgReadToken,
                    dataType: 'jsonp',
                    success: processSearchVideoResponse
                }
            );
        }

        requestPage(0);
    },

    /**
     * requestPlaylist
     * @param callback
     */
    requestPlaylist: function (callback) {
        var data = $.ajax(
            {
                type: 'POST',
                url: 'https://api.brightcove.com/services/library?command=find_all_playlists&video_fields=id,name,thumbnailURL,shortDescription&page_size=100&page_number=0&get_item_count=true&token=' + RcmBrightcoveApiService.bgReadToken,
                dataType: 'jsonp',
                success: callback
            }
        );

    },

    /**
     * findPlaylistsById
     * @param playlistIds
     * @param callback
     */
    findPlaylistsById: function (playlistIds, callback) {
        var data = $.ajax(
            {
                type: 'POST',
                url: 'https://api.brightcove.com/services/library?command=find_playlists_by_ids&playlist_ids=' + playlistIds + '&video_fields=id,name,thumbnailURL,shortDescription&page_size=100&page_number=0&get_item_count=true&token=' + RcmBrightcoveApiService.bgReadToken,
                dataType: 'jsonp',
                success: callback
            }
        );
    },

    /**
     * getDownloadURL
     * @param videoId
     * @param callback
     */
    getDownloadURL: function (videoId, callback, refresh) {

        // only go to the server once for videos
        if (RcmBrightcoveApiService.downloadUrls[videoId] && !refresh) {

            callback(RcmBrightcoveApiService.downloadUrls[videoId]);
            return;
        }

        var data = $.ajax(
            {
                type: 'POST',
                url: 'https://api.brightcove.com/services/library?command=find_video_by_id&video_id=' + videoId + '&video_fields=FLVURL,renditions&token=' + RcmBrightcoveApiService.bgUrlToken + '&media_delivery=HTTP',
                dataType: 'jsonp',
                success: function (data) {

                    var renditions = data.renditions;

                    if (!data.renditions) {
                        return;//blank video
                    }

                    var biggestFrameWidth = 0;
                    var downloadUrl = null;

                    $.each(renditions, function () {
                        if (this.frameWidth > biggestFrameWidth) {
                            biggestFrameWidth = this.frameWidth;
                            downloadUrl = this.url;
                        }
                    });

                    RcmBrightcoveApiService.downloadUrls[videoId] = downloadUrl;

                    callback(RcmBrightcoveApiService.downloadUrls[videoId]);
                }
            }
        );
    }
}

