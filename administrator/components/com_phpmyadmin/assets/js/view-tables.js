jQuery(document).ready(function($){
    Joomla.submitbutton = function(task)
    {
        if (task == 'tables.synchronous')
        {
            console.log('hello');
        }
        if (task == 'tables.SynStructureAllTableLocalToServer')
        {
            SynStructureAllTableLocalToServer();
        }
    };
    function SynStructureAllTableLocalToServer()
    {
        cidFirst=$("input[name='cid[]']:checked").filter(':first');
        table=cidFirst.val();
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function () {
                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'tables.SynStructureAllTableLocalToServer',
                    table:table

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
                //SynStructureAllTableLocalToServer();


            }
        });

    }

});