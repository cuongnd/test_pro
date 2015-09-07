/**
 * Created by THANHTIN on 4/21/2015.
 */
jQuery(document).ready(function($){
    //alert('ok');
    $('.class-icon').click(function(){
        //alert('ok');
        $('.class-icon').attr('display','block');
        data=$('.class-ul').toggle('slow').css('margin-left','12%');
        $('.container').append(data);
    });
    $('body').click(function(){
        $('.class-ul').attr('display','none');
    });

});
//.css('margin-left','12%')