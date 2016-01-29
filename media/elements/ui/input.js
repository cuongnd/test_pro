// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_input = function (element, options) {




        // plugin's default options
        var defaults = {
            enableEditWebsite:false,
            required:false,
            required_message:"This field is required",
            trigger_block_when_on_change:[],
            block_id:0,
            random_string:''
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
            $element.blur(function(e){
                self=$(this);
                if(!plugin.validate())
                {
                    return false;
                }

                plugin.trigger_block_when_on_change(self);
            });
            Joomla_post.list_function_run_befor_submit.push(plugin.update_data);
            Joomla_post.list_function_run_validate_befor_submit.push(plugin.validate_befor_submit);


        }
        plugin.validate_befor_submit=function(){
            if(!plugin.validate())
            {
                $element.focus();
                return false;
            }else{
                return true;
            }

        }
        plugin.update_data=function(data_submit){
            var name_attr=$element.attr('name');
            if(!$.isArray(data_submit[name_attr]))
            {
                data_submit[name_attr]=[];
            }
            data_submit[name_attr].push($element.val());

        }
        plugin.validate=function(){

            var $wapper_input=$element.closest('.wapper_input');
            var required=plugin.settings.required;
            var required_message=plugin.settings.required_message;
            var random_string=plugin.settings.random_string;

            var block_id=plugin.settings.block_id;
            $error_alert=$('<div class="alert input-alert alert-danger" '+(random_string!=''?'random-string="'+random_string+'"':'') +' data-block-id="'+block_id+'"><i class="im-notification"></i>'+required_message+'</div>');
            var $element_alert=$('.alert[data-block-id="'+block_id+'"]'+(random_string!=''?'[random-string="'+random_string+'"]':''));
            if(!required)
            {
                $element_alert.remove();
                return true;
            }else {
                value = $element.val();
                if(value.trim()=='')
                {

                    if($element_alert.length==0)
                    {
                        $error_alert.insertAfter($wapper_input);
                    }
                    return false;
                }else{
                    $element_alert.remove();
                    return true;
                }
            }
        }
        plugin.trigger_block_when_on_change=function(self){
            value=self.val();
            element_name=plugin.settings.element_name;
            trigger_block_when_on_change=plugin.settings.trigger_block_when_on_change;
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
        plugin.example_function = function (self) {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_input = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_input')) {
                var plugin = new $.ui_input(this, options);
                $(this).data('ui_input', plugin);

            }

        });

    }

})(jQuery);
