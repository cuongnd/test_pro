//huong dan su dung
/*
 $('.mod_languages').mod_languages();

 mod_languages=$('.mod_languages').data('mod_languages');
 console.log(mod_languages);
 */

// jQuery Plugin for SprFlat admin mod_languages
// Control options and basic function of mod_languages
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.mod_languages = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for mod_languages
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
            $element.polyglotLanguageSwitcher({
                openMode: 'click',
                selectedLang: function () {
                    return $('html').attr('lang');
                }
            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.mod_languages = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('mod_languages')) {
                var plugin = new $.mod_languages(this, options);

                $(this).data('mod_languages', plugin);

            }

        });

    }

})(jQuery);
