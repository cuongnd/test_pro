/**
 * Created by cuongnd on 29/9/2015.
 */
jQuery(document).ready(function($){
    $(".load_element .item-element").draggable({
        appendTo: 'body',
        helper: "clone"
        /* revert:true,
         proxy:'clone'*/
    });
    $(".load_element .item-element").each(function(){
        _this=$(this);
        _this.on("click", function nav_link_click(e) {
            var e_taget = $(e.target);
            if (e_taget.hasClass('fa-list-alt') && e_taget.attr('element-config') !== undefined) {
                Joomla.design_website.element_config(e_taget);
            }
        });
    });
});
