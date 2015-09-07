jQuery(document).ready(function($){

    element_ui_browse={
        init_ui_browse:function(){

        },
        open_file_server:function(self){
            self=$(self);
            var finder = new CKFinder();
            finder.basePath = this_host+'/images/stories/';
            output=$(self).data('output');

            finder.selectActionFunction = function(fileUrl){
                $(output).val(fileUrl);
            };
            finder.popup();
        }
    };



});