(function ($) {

    // here we go!
    $.ui_radiobuttongroup = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:false,
            block_id:0,
            trigger_change:[],
            element_name:'',

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            element_name= plugin.settings.element_name;
            $element.find('input[type=radio]').change( function() {
                value=$(this).val();
                var trigger_change=plugin.settings.trigger_change;
                if(trigger_change.length>=1)
                {
                    $.each(trigger_change,function(index,block_id){
                        var instants=$('.block-item[data-block-id="'+block_id+'"]').data();
                        $.each(instants,function(key,instant){
                            key=key.toLowerCase();
                            if(key.indexOf("ui_") > -1)
                            {
                                if (typeof instant.update_data_by_key === "function") {
                                    var list_key_value={};
                                    list_key_value[element_name]=value;
                                    instant.update_data_by_key(list_key_value);
                                }


                            }
                        })
                    });
                }
            });
            Joomla_post.list_function_run_befor_submit.push(plugin.update_data);


        }

        plugin.update_data=function(data_submit){
            $input_radio=$element.find('input[type=radio]:checked');
            var name_attr=$input_radio.attr('name');
            if(!$.isArray(data_submit[name_attr]))
            {
                data_submit[name_attr]=[];
            }
            data_submit[name_attr].push($input_radio.val());

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_radiobuttongroup = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_radiobuttongroup')) {
                var plugin = new $.ui_radiobuttongroup(this, options);
                $(this).data('ui_radiobuttongroup', plugin);

            }

        });

    }

})(jQuery);





