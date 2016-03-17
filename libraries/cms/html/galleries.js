//huong dan su dung
/*
 $('.jhtml_galleries').jhtml_galleries();

 jhtml_galleries=$('.jhtml_galleries').data('jhtml_galleries');
 console.log(jhtml_galleries);
 */

// jQuery Plugin for SprFlat admin jhtml_galleries
// Control options and basic function of jhtml_galleries
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.jhtml_galleries = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for jhtml_galleries
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
            $element.find( ".sortable" ).sortable();
            $element.find( ".sortable" ).disableSelection();


            plugin.featherEditor = new Aviary.Feather({
                apiKey: '44389dd71b1649c0a71e96212b1edc47',
                onSave: function(imageID, newURL) {
                    var img = document.getElementById(imageID);
                    img.src = newURL;
                    plugin.featherEditor.close();
                }
            });

            $element.find('.edit-image').click(function(){
                var $item=$(this).closest('.item');
                var src=$item.find('.image-item').attr('src');
                var id=$item.find('.image-item').attr('id');
                plugin.launchEditor(id,src);
            });



        };
        plugin.launchEditor=function(id, src) {
            plugin.featherEditor.launch({
                image: id,
                url: src
            });
            return false;
        };
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.jhtml_galleries = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('jhtml_galleries')) {
                var plugin = new $.jhtml_galleries(this, options);

                $(this).data('jhtml_galleries', plugin);

            }

        });

    }

})(jQuery);
