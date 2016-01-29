(function ($) {

    // here we go!
    $.ui_autocompletetext = function (element, options) {

        // plugin's default options
        var defaults = {
            data:[],
            typeahead_option:{}

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
            typeahead_option=plugin.settings.typeahead_option;
            $element.typeahead(
                typeahead_option,
                {
                    name: 'autocompletetext',
                    source: plugin.substringMatcher(plugin.settings.data)
                });


        }

        plugin.substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });

                cb(matches);
            };
        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_autocompletetext = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_autocompletetext')) {
                var plugin = new $.ui_autocompletetext(this, options);
                $(this).data('ui_autocompletetext', plugin);

            }

        });

    }

})(jQuery);





