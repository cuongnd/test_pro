//huong dan su dung
/*
 $('.view_importcategoryvatgia').view_importcategoryvatgia();

 view_importcategoryvatgia=$('.view_importcategoryvatgia').data('view_importcategoryvatgia');
 console.log(view_importcategoryvatgia);
 */

// jQuery Plugin for SprFlat admin view_importcategoryvatgia
// Control options and basic function of view_importcategoryvatgia
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_importcategoryvatgia = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_importcategoryvatgia
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
            $element.find('.importcategoryvatgia').click(function(){
                alert('you cannot import again');
                return false;
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller:"utilities",
                            task: 'importcategoryvatgia'

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



                    }
                });

            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_importcategoryvatgia = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_importcategoryvatgia')) {
                var plugin = new $.view_importcategoryvatgia(this, options);

                $(this).data('view_importcategoryvatgia', plugin);

            }

        });

    }

})(jQuery);
