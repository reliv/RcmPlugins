rcmSocialButtonsReload = function(){
    stButtons.locateElements();
};

$(function(){

    var shareThisKey = $('.rcmShareThisKey');

    if(
        shareThisKey.length
        && typeof(window.rcmSocialButtonsJsLoaded) == 'undefined'
    ) {

        //Mark as loaded so we don't pull in their JS twice when we have two plugins
        window.rcmSocialButtonsJsLoaded = true;

        //ShareThis requires this code before loading their JS file
        var switchTo5x=true;

        //Figure out the path to the share-this js file
        var subDomain='w';
        if(window.location.protocol == 'https:'){
            subDomain='ws';
        }

        //Load the js file
        $.getScript('//'+subDomain+'.sharethis.com/button/buttons.js',

            //Callback after script is loaded
            function(){

                //ShareThis requires this code after loading their JS file
                stLight.options({
                    publisher: shareThisKey.first()
                        .attr('data-rcmShareThisKey'),
                    onhover: false
                });

            }
        );


    }

});