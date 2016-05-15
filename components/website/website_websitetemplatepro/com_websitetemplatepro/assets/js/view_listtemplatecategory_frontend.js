//huong dan su dung
/*
 $('.view_listtemplatecategory_frontend').view_listtemplatecategory_frontend();

 view_listtemplatecategory_frontend=$('.view_listtemplatecategory_frontend').data('view_listtemplatecategory_frontend');
 console.log(view_listtemplatecategory_frontend);
 */

// jQuery Plugin for SprFlat admin view_listtemplatecategory_frontend
// Control options and basic function of view_listtemplatecategory_frontend
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_listtemplatecategory_frontend = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_listtemplatecategory_frontend
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.set_menu = function () {
            $element.find('.u-vmenu').vmenuModule({
                Speed: 200,
                autostart: false,
                autohide: true
            });
        };
        plugin.load_website_template_by_category = function () {
            $element.find('.u-vmenu a').click(function(){
                var option_click= {
                    option: 'com_websitetemplatepro',
                    task: 'listtemplatecategory.ajax_get_website_template_by_category',

                };
                option_click= $.param(option_click);
                var data_submit={};
                data_submit.category_id=$(this).data('category_id');
                ajax_web_design=$.ajax({
                    contentType: 'application/json',
                    type: "POST",
                    dataType: "json",
                    url: this_host+'/index.php?'+option_click,
                    data: JSON.stringify(data_submit),
                    beforeSend: function () {
                        $('.div-loading').css({
                            display: "block"


                        });
                    },
                    success: function (response) {
                        $('.div-loading').css({
                            display: "none"


                        });
                        if(response.e==0)
                        {



                        }else if(response.e==1){

                        }



                    }
                });

            });
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            plugin.set_menu();
            plugin.load_website_template_by_category();


        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_listtemplatecategory_frontend = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_listtemplatecategory_frontend')) {
                var plugin = new $.view_listtemplatecategory_frontend(this, options);

                $(this).data('view_listtemplatecategory_frontend', plugin);

            }

        });

    }

})(jQuery);
