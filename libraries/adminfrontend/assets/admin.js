jQuery(document).ready(function($) {


    var previewScreenSize='';
    $(document).on('click','.preview',function(){


        screen_size_id=$('select[name="smart_phone"]').val();
        console.log(screen_size_id);
        screen_size_id=screen_size_id.toString();
        selected=screen_size_id.toLowerCase();
        selected = selected.split('x');
        url= $.param.querystring(window.location.href, 'preview=1&previewScreenSize='+screen_size_id.toString());
        console.log(url);
        //thêm tham so preview=1 lên thanh địa chỉ là được
        myWindow = window.open(url, "", "width="+selected[0].toString()+", height="+selected[1].toString());  // Opens a new window
        myWindow.previewScreenSize=screen_size_id;




    });


});
