jQuery(document).ready(function($) {
    var $win = $(window)
        , $nav    = $('.subhead')
        , navTop  = $('.subhead').length && $('.subhead').offset().top
        , isFixed = 0;

    processScroll();


    $nav.on('click', function()
    {
        if (!isFixed) {
            setTimeout(function()
            {
                $win.scrollTop($win.scrollTop() - 0)
            }, 10)
        }
    });

    $win.on('scroll', processScroll);
    function processScroll()
    {
        console.log('processScroll');
        var i, scrollTop = $win.scrollTop(),
            scrollLeft = $win.scrollLeft()
            ;

        $('.subhead').css({
            ///"margin-left":scrollLeft.toString()+'px'
        });


        if (scrollTop >= navTop && !isFixed) {
            isFixed = 1;
           // $('.subhead').addClass('subhead-fixed');
        } else if (scrollTop <= navTop && isFixed) {
            isFixed = 0;
           // $('.subhead').removeClass('subhead-fixed');
        }
    }
    $(document).on('click','.preview',function(){
        myWindow = window.open(url_root, "", "width=100, height=100");  // Opens a new window
    });


});
