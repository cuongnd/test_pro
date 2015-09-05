jQuery(document).ready(function($){

    var $win = $(window)
        , $nav = $('.scroll-div-screen-size')
        , scroll_div_screen_size = $('.scroll-div-screen-size').length && $('.scroll-div-screen-size').offset().top-55
        , isFixed = 0;
    processScroll();


    $nav.on('click', function () {
        if (!isFixed) {
            setTimeout(function () {
                $win.scrollTop($win.scrollTop())
            }, 10)
        }
    });

    $win.on('scroll', processScroll);
    $(document).on("scrollstop",function(){
        alert("Stopped scrolling!");
    });
    function setPositionProperties()
    {
        propertiesPosition = $('.block-properties').position();
        var i, scrollTop = $win.scrollTop();
        if (scrollTop >= propertiesPosition.top) {
            $('.block-properties').addClass('fixed');
        } else if (scrollTop <= propertiesPosition.top) {
            $('.block-properties').removeClass('fixed');
        }

    }
    function setPositionToolStyle()
    {
        toolStylePosition = $('.tool-edit-style').position();
        var i, scrollTop = $win.scrollTop();
        if (scrollTop >= toolStylePosition.top) {
            $('.tool-edit-style').addClass('fixed');
        } else if (scrollTop <= toolStylePosition.top) {
            $('.tool-edit-style').removeClass('fixed');
        }

    }
    function processScroll() {
       //setPositionProperties();
       //setPositionToolStyle();
      /*  var i, scrollTop = $win.scrollTop();
        if (scrollTop >= scroll_div_screen_size && !isFixed) {
            isFixed = 1;
            $nav.addClass('scroll-div-screen-size-subhead-fixed');
        } else if (scrollTop <= scroll_div_screen_size && isFixed) {
            isFixed = 0;
            $nav.removeClass('scroll-div-screen-size-subhead-fixed');
        }*/
    }
});