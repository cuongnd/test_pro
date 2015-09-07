/*!
 jQuery [name] plugin
 @name jquery.[name].js
 @author [author name] ([author email] or @[author twitter])
 @version 1.0
 @date 01/01/2013
 @category jQuery Plugin
 @copyright (c) 2013 [company/person name] ([company/person website])
 @license Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
 */
(function($){

    var element_list_checkbox, defaultOptions, __bind;

    __bind = function(fn, me) {
        return function() {
            return fn.apply(me, arguments);
        };
    };

    // Plugin default options.
    defaultOptions = {
        myvar1: 1,
        myvar2: 2,
        myvar3: 3,
        resizeDelay: 50
        //etc...
    };

    element_list_checkbox = (function(options) {

        function element_list_checkbox(handler, options) {
            this.handler = handler;

            // plugin variables.
            this.resizeTimer = null;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.update = __bind(this.update, this);
            this.onResize = __bind(this.onResize, this);
            this.init = __bind(this.init, this);
            this.clear = __bind(this.clear, this);

            // Listen to resize event if requested.
            if (this.autoResize) {
                $(window).bind('resize.element_list_checkbox', this.onResize);
            };
        };

        // Method for updating the plugins options.
        element_list_checkbox.prototype.update = function(options) {
            $.extend(true, this, options);
        };

        // This timer ensures that layout is not continuously called as window is being dragged.
        element_list_checkbox.prototype.onResize = function() {
            clearTimeout(this.resizeTimer);
            this.resizeTimer = setTimeout(this.resizeFunc, this.resizeDelay);
        };

        // Example API function.
        element_list_checkbox.prototype.resizeFunc = function() {
            //...do something when window is resized
        };

        // Main method.
        element_list_checkbox.prototype.init = function() {
            //...do something to initialise plugin
        };

        // Clear event listeners and time outs.
        element_list_checkbox.prototype.clear = function() {
            clearTimeout(this.resizeTimer);
            $(window).unbind('resize.element_list_checkbox', this.onResize);
        };

        return element_list_checkbox;
    })();

    $.fn.element_list_checkbox = function(options) {
        // Create a element_list_checkbox instance if not available.
        if (!this.myPluginInstance) {
            this.myPluginInstance = new element_list_checkbox(this, options || {});
        } else {
            this.myPluginInstance.update(options || {});
        }

        // Init plugin.
        this.myPluginInstance.init();

        // Display items (if hidden) and return jQuery object to maintain chainability.
        return this.show();
    };
})(jQuery);