jQuery(document).ready(function($){

    elementuipanel={

        initpanel:function(){


        },
        add_body_panel:function(self){
            object_id=self.closest('.properties').attr('data-object-id');
            ajaxInsertElement=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertRow',
                        parentColumnId:object_id,
                        menuItemActiveId:menuItemActiveId,
                        ajaxgetcontent:1,
                        screenSize:screenSize

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {
                }
            });
        }
    }
});