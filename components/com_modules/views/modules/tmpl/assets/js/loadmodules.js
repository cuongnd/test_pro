/**
 * Created by cuongnd on 29/9/2015.
 */
jQuery(document).ready(function($){
    $(".list-module .item-element").draggable({
        appendTo: 'body',
        helper: "clone"
        /* revert:true,
         proxy:'clone'*/
    });
    $(".list-module .item-element.module_item").each(function(){
        _this=$(this);
        _this.on("click", function nav_link_click(e) {
            var e_taget = $(e.target);
            if (e_taget.data('element-type')=="extension_module") {
                Joomla.design_website.module_config(e_taget);
            }
        });
    });

});
