
jQuery(document).ready(function($){

    stylegenerator={
        init_stylegenerator:function(){
            $("#preview").sticky({topSpacing:0});
        },
        update_style:function(){
            jquery_stylegenerator=$('.stylegenerator').data('stylegenerator');
            jquery_stylegenerator.updateStyles();
        }
    }





});