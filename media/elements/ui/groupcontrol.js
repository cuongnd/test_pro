jQuery(document).ready(function($){

    elementuigroupcontrolset={

        initFieldSet:function(){

        },

        changeStyleElement:function() {
            valselected = $('#jform_params_type_input').val();

        }
    };


    $('.edit_content_group_control').each(function(){
        $(this).popover({
            html : true,
            placement:'top',
            title:function(){
                return '<span class="text-info"><strong>title</strong></span>'+
                    '<button type="button" id="close" class="close" onclick="jQuery(this).closest(\'div.popover\').popover(\'hide\');">&times;</button>';
            },
            delay: { "show": 500, "hide": 100 },
            trigger:'click',
            container:'body',
            content: function() {
                return 'you can right click to copy,cut element, left click go to properties this element';
            }
        }).dblclick(function(){

        });
    });




    $('.edit_content_group_control').popline({position: "fixed"});
    $(document).delegate('#jform_params_type_input',"change",function(e){
        elementuigroupcontrolset.changeStyleElement();
    });




});