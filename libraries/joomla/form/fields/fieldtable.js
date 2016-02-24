// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.field_table = function (element, options) {

        // plugin's default options
        var defaults = {
            data:[],
            binding_source_name:'',
            block_id:0,
            list_input:[]
            //main color scheme for template
            //be sure to be same as colors on main.css or custom-variables.less

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
            var binding_source_name=plugin.settings.binding_source_name;
            binding_source_name = binding_source_name.replace(/\./g, '_');

            $('#jform_'+binding_source_name).change(function(){
                var data_source=$(this).val();
                var block_id= plugin.settings.block_id;
                var list_input= plugin.settings.list_input;
                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'datasource.ajax_get_list_field_by_data_source',
                    data_source: data_source,
                    block_id:block_id
                };
                dataPost = $.extend(list_input,dataPost);

                ajax_web_design=$.ajax({
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    url: this_host+'/index.php',
                    data: (function () {

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
                        $ttTypeahead=$element.data('ttTypeahead');
                        $ttTypeahead.menu.datasets[0].source= plugin.substringMatcher(response);
                        console.log($ttTypeahead.menu.datasets[0]);



                    }
                });




            });
            current_data_source=$('#jform_'+binding_source_name).val();

            $element.typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 0
                },
                {
                    name: 'datasource',
                    source: plugin.substringMatcher(plugin.settings.data)
                });

            /**
             * Accordion functionality
             */
            ;

            // click a "more" link

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
    $.fn.field_table = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_table')) {
                var plugin = new $.field_table(this, options);
                $(this).data('field_table', plugin);

            }

        });

    }

})(jQuery);
