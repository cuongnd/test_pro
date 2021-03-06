//huong dan su dung
/*
 $('.view_user_login').view_user_login();

 view_user_login=$('.view_user_login').data('view_user_login');
 console.log(view_user_login);
 */

// jQuery Plugin for SprFlat admin view_user_login
// Control options and basic function of view_user_login
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_user_login = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_user_login
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
            plugin.settings = $.extend({}, defaults, options);
            $element.find("#login-form").validate();
            $element.find("#register-form").validate({
                rules: {
                    password1: {
                        required: true,
                        minlength: 5
                    },
                    password_confirm: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password1"
                    }
                }
            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_user_login = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_user_login')) {
                var plugin = new $.view_user_login(this, options);

                $(this).data('view_user_login', plugin);

            }

        });

    }

})(jQuery);
