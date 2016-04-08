//huong dan su dung
/*
 $('.view_importproductvatgia').view_importproductvatgia();

 view_importproductvatgia=$('.view_importproductvatgia').data('view_importproductvatgia');
 console.log(view_importproductvatgia);
 */

// jQuery Plugin for SprFlat admin view_importproductvatgia
// Control options and basic function of view_importproductvatgia
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_importproductvatgia = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_importproductvatgia
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
            $element.find('.importproductvatgia').click(function(){
                /*alert('you cannot import again');
                return false;*/
                plugin.importproductvatgia();
            });
        }

        plugin.importproductvatgia = function() {
            var vatgia_category_id=$('input[name="vatgia_category_id"]').val();
            if(vatgia_category_id==''||!$.isNumeric(vatgia_category_id))
            {
                alert('please input category vatgia');
                return false;
            }
            $.ajax({
                type: "GET",
                url: 'index.php',
                //dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_virtuemart',
                        controller:"utilities",
                        task: 'importproductvatgia',
                        vatgia_category_id: vatgia_category_id

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    $('.div-loading').css({
                        display: "block"
                    });
                },
                success: function (response) {

                    $('.div-loading').css({
                        display: "none"


                    });
                    $('.response').html(response);
                    //plugin.importproductvatgia();


                }
            });

        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_importproductvatgia = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_importproductvatgia')) {
                var plugin = new $.view_importproductvatgia(this, options);

                $(this).data('view_importproductvatgia', plugin);

            }

        });

    }

})(jQuery);
