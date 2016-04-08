//huong dan su dung
/*
 $('.view_setcategoryproduct').view_setcategoryproduct();

 view_setcategoryproduct=$('.view_setcategoryproduct').data('view_setcategoryproduct');
 console.log(view_setcategoryproduct);
 */

// jQuery Plugin for SprFlat admin view_setcategoryproduct
// Control options and basic function of view_setcategoryproduct
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_setcategoryproduct = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_setcategoryproduct
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
            $element.find('.setcategoryproduct').click(function(){
                /*alert('you cannot import again');
                return false;*/
                plugin.setcategoryproduct();
            });
        }

        plugin.setcategoryproduct = function() {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_virtuemart',
                        controller:"utilitiesvatgia",
                        task: 'setcategoryproduct'

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    $('.div-loading').css({
                        display: "block"
                    });
                    $('.loading').html('loading data');
                },
                success: function (response) {

                    $('.div-loading').css({
                        display: "none"


                    });
                    if(response.e==1)
                    {
                        $('.loading').html('there are some errros');
                        $('.response').html(response.r);

                    }else
                    {
                        $('.response').html(response.r);
                        plugin.setcategoryproduct();
                    }


                }
            });

        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_setcategoryproduct = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_setcategoryproduct')) {
                var plugin = new $.view_setcategoryproduct(this, options);

                $(this).data('view_setcategoryproduct', plugin);

            }

        });

    }

})(jQuery);
