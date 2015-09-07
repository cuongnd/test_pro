$=jQuery;
var backgroundPath='';
$(document).on('click','.background-image',function(){
    backgroundPath=$(this).attr('src');
    $('.grid-stack').css({
        "background":"url('"+backgroundPath+"')"
    });
    console.log(backgroundPath);
});
function SaveBackGround()
{
    $.ajax({
        type: "GET",
        url: this_host+'/index.php',
        data: (function () {

            dataPost = {
                option: 'com_website',
                task: 'background.aJaxSaveBackground',
                backgroundPath:"url('"+backgroundPath+"')"

            };
            return dataPost;
        })(),
        beforeSend: function () {
            $('.div-loading').css({
                display: "block"


            });
            // $('.loading').popup();
        },
        success: function (response) {
            $('.div-loading').css({
                display: "none"


            });


        }
    });

}