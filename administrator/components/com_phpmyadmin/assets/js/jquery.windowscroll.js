jQuery(document).ready(function($){

    var $win = $(window)
        , $stencil_container = $('.stencil-container')
        , scroll_stencil_container = $('.stencil-container.stencil-subhead-fixed').length && $('.stencil-container.stencil-subhead-fixed').offset().top-55
        , isStencilFixed = 0;
    processScroll();


    $stencil_container.on('click', function () {
        if (!isFixed) {
            setTimeout(function () {
                $win.scrollTop($win.scrollTop())
            }, 10)
        }
    });

    $win.on('scroll', processScroll);

    function processScroll() {

        var i, scrollTop = $win.scrollTop();
        $('.stencil-container').css({
            "margin-top":scrollTop
        });
        $('.inspector-container').css({
            "margin-top":scrollTop
        });
       /* if (scrollTop >= scroll_stencil_container && !isStencilFixed) {
            isStencilFixed = 1;
            $stencil_container.addClass('stencil-subhead-fixed');
            console.log($win.scrollTop());
            $('.stencil-container.stencil-subhead-fixed').css({
                "margin-top":scrollTop
            });
        } else if (scrollTop <= scroll_stencil_container && isStencilFixed) {
            isStencilFixed = 0;
            $stencil_container.removeClass('stencil-subhead-fixed');
        }*/
    }
});