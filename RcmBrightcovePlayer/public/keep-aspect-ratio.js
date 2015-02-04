/**
 * This script keeps the height set on elements with regard to their
 * width for a given aspect ratio. Great for "display:block" elements
 *
 * Example usage:
 *
 * <div data-keep-aspect-ratio="16:9">
 *
 * Author: Rod McNew
 * License: BSD
 */
new function () {
    var setHeights = function () {
        $.each($('[data-keep-aspect-ratio]'), function () {
            var ele = $(this);
            var ratioParts = ele.attr('data-keep-aspect-ratio').split(':');
            var ratioWidth = ratioParts[0];
            var ratioHeight = ratioParts[1];
            var width = ele.width();
            var newHeight = width * ratioHeight / ratioWidth;
            ele.css('height', newHeight);
        })
    };
    //Run onReady
    $(setHeights);
    //Run when window is resized
    $(window).resize(setHeights);
    //Run when target elements are resized without a window resize
    $('body').delegate('[data-keep-aspect-ratio]', 'resize', setHeights);
};