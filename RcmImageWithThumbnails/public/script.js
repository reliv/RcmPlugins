var RcmImageWithThumbnails = function (instanceId) {

    /**
     * Plugin container div jQuery object
     * @type {Object}
     */
    var container = rcm.getPluginContainer(instanceId);

    var thumbWrapper = $('.thumbWrapper');

    container.find('.thumbWrapper img').click(
        function(){
            // console.log($(this).attr('src'));
            var newSrc=$(this).attr('data-mainImage');
            container.find('.rcmImageWithThumbnails_mainImage img').attr('src',newSrc)
        }
    );
};