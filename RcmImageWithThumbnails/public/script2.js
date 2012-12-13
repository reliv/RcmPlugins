//uiygtiuygtiuy
$(function() {
    $(".RcmImageWithThumbnails").find(".image").click(function() {
        var newSrc = $(this).attr("rel");
        var mainImage = $(this).parent().find('.mainImage');
        mainImage.hide();
        mainImage.fadeIn('slow');
        mainImage.html('<img src="' + newSrc + '"/>');
        return false;
    });
});

