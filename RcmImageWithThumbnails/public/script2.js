//script
$(function() {
    $(".RcmImageWithThumbnails").find(".image").click(function() {
        var container = $(this).closest('.rcmPluginContainer');
        var newSrc = $(this).attr("rel");
        var mainImage = container.find('.mainImage');
        container.find('.thumb').removeClass('selected');
        mainImage.find('img').attr('src', newSrc);
        $(this).find('.thumb').addClass('selected');

        return false;
    });
});

