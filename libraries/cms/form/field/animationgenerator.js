//huong dan su dung
/*
 $('.animation_generator').animation_generator();

 animation_generator=$('.animation_generator').data('animation_generator');
 console.log(animation_generator);
 */

// jQuery Plugin for SprFlat admin animation_generator
// Control options and basic function of animation_generator
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.animation_generator = function(element, options) {

        // plugin's default options
        var defaults = {
            field:{
                name:''
            }
            //main color scheme for animation_generator
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        plugin.test_animation=function(animation) {
            $element.find('input[name="'+plugin.settings.field.name+'"]').val(animation);
            $element.find('#animationSandbox').removeClass().addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass();
            });
        };
        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

            $element.find('.js--triggerAnimation').click(function (e) {

                e.preventDefault();
                var animation = $('.js--animations').val();
                plugin.test_animation(animation);
            });
            var animation=$element.find('input[name="'+plugin.settings.field.name+'"]').val();
            $element.find('.js--animations').val(animation);
            $element.find('.js--animations').change(function () {
                var animation = $(this).val();
                plugin.test_animation(animation);
            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.animation_generator = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('animation_generator')) {
                var plugin = new $.animation_generator(this, options);

                $(this).data('animation_generator', plugin);

            }

        });

    }

})(jQuery);
