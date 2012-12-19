$(function(){
    var imgContainers = $('.RcmRotatingImage .images');

    $.each(imgContainers,function(){

        var imgContainer = $(this);
        if(!imgContainer.attr('data-alreadyLoaded')){

            imgContainer.attr('data-alreadyLoaded',1);
            var images = imgContainer.children();
            var randomIndex = Math.floor(Math.random() * images.length);
            var winner = $(images[randomIndex]);

            //used by edit.js
            winner.addClass('winner');

            //Keeping it out of src till here prevents loading of unseen images
            winner.attr('src',winner.attr('data-src'));

            winner.closest('a').attr('href',winner.attr('data-href'));

            winner.show();
        }
    });

});