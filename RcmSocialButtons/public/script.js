$(function(){

    var socialButtonsWrapper = $('.rcmSocialButtonsWrapper');

    if(
        socialButtonsWrapper.length
        && typeof(window.rcmSocialButtonsJsLoaded) == 'undefined'
    ) {

        //Mark as loaded so we don't pull in their JS twice when we have two plugins
        window.rcmSocialButtonsJsLoaded = true;

        //ShareThis requires this code before loading their JS file
        var switchTo5x=true;

        //console.log('RCM SHARE THIS: getting script....');

        //Load the js file
        $.getScript('//w.sharethis.com/button/buttons.js',

            //Callback after script is loaded
            function(){

                //console.log('RCM SHARE THIS: Setting API key...');

                //ShareThis requires this code after loading their JS file
                stLight.options({
                    publisher: socialButtonsWrapper.first()
                        .attr('data-rcmSocialButtonsPublisherKey'),
                    onhover: false
                });

                //console.log('RCM SHARE THIS: Done setting API key');

            }
        );


    }

});