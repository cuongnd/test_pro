(function ( $ ) {
    $.fn.editstyletool = function(opts) {
        $('body').prepend($(this));
        $(this).draggable({
            handle: '.panel-heading'
        });
        $('.allow-edit-style')
            .hover(function () {
                $('.allow-edit-style').removeClass('rotate');
                $('.allow-edit-style').find('.line').remove();
                $(this).addClass('rotate');
                html_line='<div class="line"><i></i></div>';
                $(this).prepend($(html_line));
                $(this).prepend($(html_line));
                $(this).prepend($(html_line));
                $(this).prepend($(html_line));

            })
            .click(function () {
                $('.allow-edit-style').removeClass('selected');
                $(this).addClass('selected');
            });

        $(this).find('.list-icon-edit li a').click(function () {
            $('.list-icon-edit li a').removeClass('selected');
            $(this).addClass('selected');
            li = jQuery(this).parent('li');
            $('.list-icon-edit').find('.tab-edit-tool').addClass('hide');
            li.find('.tab-edit-tool').removeClass('hide');
            if (!$(this).hasClass('pointer')) {
                $('.allow-edit-style').removeClass('editing-selected');
            }


        });
        $(document).on('click','.screen-size',function(){
             if($(this).hasClass('selected'))
             {
                 $('.list-screen').show();
             }else{
                 $('.list-screen').hide();
             }
        });


    };
}( jQuery ));
jQuery(document).ready(function($){
    //show edit tool
    $('.tool-edit-style').editstyletool();
});
