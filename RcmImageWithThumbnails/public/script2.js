//script
$(function() {
    $(".RcmImageWithThumbnails").find(".image").click(function() {
        var container = $(this).closest('.rcmPluginContainer');
        var newSrc = $(this).attr("rel");
        var mainImage = container.find('.mainImage');
        //mainImage.hide();
        //mainImage.fadeIn('fast');
        mainImage.find('img').attr('src', newSrc);
        return false;
    });
});

