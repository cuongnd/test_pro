// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_interger = function (element, options) {




        // plugin's default options
        var defaults = {
            enableEditWebsite:false,
            enable_select2:1,
            trigger_block_when_on_change:[]
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var element_id=$element.attr('id');
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            trigger_block_when_on_change=plugin.settings.trigger_block_when_on_change;
            $element.change(function(){
                self=$(this);
                plugin.trigger_block_when_on_change(self);
            })

            if(plugin.settings.enable_select2)
            {
                $element.select2(
                    {
                        width:'resolve'
                    }
                );
            }

        }
        plugin.trigger_block_when_on_change=function(self){
            value=self.val();
            element_name=plugin.settings.element_name;
            trigger_block_when_on_change=plugin.settings.trigger_block_when_on_change;
            if(trigger_block_when_on_change !=null && trigger_block_when_on_change.length>=1)
            {
                $.each(trigger_block_when_on_change,function(index,block_id){
                    $this_element=$('.block-item[data-block-id="'+block_id+'"]');
                    var element_type=$this_element.attr('element-type');
                    instant=$this_element.data('ui_'+element_type);
                    if (typeof instant.update_data_by_key === "function") {
                        var list_key_value={};
                        list_key_value[element_name]=value;
                        instant.update_data_by_key(list_key_value);
                    }
                });
            }
        }
        plugin.example_function = function (self) {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_interger = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_interger')) {
                var plugin = new $.ui_interger(this, options);
                $(this).data('ui_interger', plugin);

            }

        });

    }

})(jQuery);
