jQuery(document).ready(function($) {


    var previewScreenSize='';
    $(document).on('click','.preview',function(){


        screenSize=$('select[name="smart_phone"]').val();
        console.log(screenSize);
        screenSize=screenSize.toString();
        selected=screenSize.toLowerCase();
        selected = selected.split('x');
        url= $.param.querystring(window.location.href, 'preview=1&previewScreenSize='+screenSize.toString());
        console.log(url);
        //thêm tham so preview=1 lên thanh địa chỉ là được
        myWindow = window.open(url, "", "width="+selected[0].toString()+", height="+selected[1].toString());  // Opens a new window
        myWindow.previewScreenSize=screenSize;




    });


});
