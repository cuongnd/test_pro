//huong dan su dung
/*
 $('.field_childrenproduct').field_childrenproduct();

 field_childrenproduct=$('.field_childrenproduct').data('field_childrenproduct');
 console.log(field_childrenproduct);
 */

// jQuery Plugin for SprFlat admin field_childrenproduct
// Control options and basic function of field_childrenproduct
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_childrenproduct = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_childrenproduct
            //be sure to be same as colors on main.css or custom-variables.less
            field:{
                name:''
            },
            list_product_category:[]

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.renew_layout = function () {
            var $list_children_product_item=$element.find('.list-children-product .children-product-item');
            $list_children_product_item.each(function(index){
                var $self=$(this);
                $self.find('.children-product-item-order').html(index+1);
                $self.find(':input').each(function(){
                    var $input=$(this);
                    var data_name=$input.attr('data-name');
                    if(typeof data_name!="undefined" && data_name.trim()!='')
                    {
                        $input.attr('name','list_children_product['+index+']['+data_name+']');
                    }
                });
            });
        };
        plugin.update_event = function () {
            var event_class='random-add-new-children-product';
            $element.find('.add-new-children-product').add_event_element('click',function(){
                var html_template_children_product_item=plugin.settings.html_template_children_product_item;
                var $last_children_product_item=$element.find('.list-children-product .children-product-item:last');
                $(html_template_children_product_item).insertAfter($last_children_product_item);
                plugin.renew_layout();
                plugin.update_event();
            },event_class);

            var event_class='random-remove-children-product';
            $element.find('.remove-children-product').add_event_element('click',function(){
                var $children_product_item=$element.find('.list-children-product .children-product-item');
                if($children_product_item.length==1)
                {
                    var notify = $.notify('you cannot remove all item children product', {
                            allow_dismiss: true,
                            type:"warning"
                        }
                    );
                    return false;
                }
                var $last_children_product_item=$element.find('.list-children-product .children-product-item:last');
                $last_children_product_item.remove();
            },event_class);

        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var list_product_category=plugin.settings.list_product_category;
            var html_template_children_product_item=$element.find('.children-product-item').getOuterHTML();
            plugin.settings.html_template_children_product_item=html_template_children_product_item;
            plugin.update_event();



        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_childrenproduct = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_childrenproduct')) {
                var plugin = new $.field_childrenproduct(this, options);

                 $(this).data('field_childrenproduct', plugin);

            }

        });

    }

})(jQuery);
