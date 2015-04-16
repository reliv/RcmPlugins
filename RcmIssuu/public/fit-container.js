
var fitContainer = function () {
    $.each($('.fit-container'), function () {
        var problemDiv = $(this);

        var containerDiv = $(problemDiv).parent().closest(".rcmPlugin");

        var containerWidth = $(containerDiv).width();

        var currentWidth = problemDiv.width();
        var currentHeight = problemDiv.height();

        var newHeight = containerWidth / currentWidth * currentHeight;

        problemDiv.width(containerWidth);
        problemDiv.height(newHeight);
    })
};


//Run onReady
$(fitContainer);
//Run when window is resized
$(window).resize(fitContainer);