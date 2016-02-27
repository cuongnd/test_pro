(function ($) {

    // here we go!
    $.project_realtion_ship = function (element, options) {

        // plugin's default options
        var defaults = {
            source_id: 0,
            ajaxgetcontent: 0,
            field_name: '',
            list_table: []
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
            document.title = 'edit project relation ship';
            $element.find('.list_table').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 1,
                    limit:10
                },
                {
                    name: 'datasource',
                    source: plugin.substringMatcher(plugin.settings.list_table),
                    limit:10
                });

            $area=$element.find('#area');
            plugin.SQL_Designer = new SQL.Designer($area);
            $element.find('.cancel-project-ralationship').click(function(){
                window.close();
            });
            $(document).on('keydown', null, 'ctrl+s', function(){
                $element.find('.save-project-ralationship').trigger( "click" );
            });
            $element.find('.save-project-ralationship').click(function(){
                var xml=plugin.SQL_Designer.io.getXML();
                xml=base64.encode(xml);
                close_window=$(this).hasClass('save-close-project-ralationship');
                website_design = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: this_host + '/index.php',

                    data: (function () {

                        dataPost = {
                            option: 'com_phpmyadmin',
                            task: 'datasource.ajax_save_project_relationship',
                            xml: xml

                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {


                        // $('.loading').popup();
                    },
                    success: function (response) {
                        if(response.e==1)
                        {
                            alert(response.m);
                        }else
                        {
                            alert(response.m);
                            if(close_window)
                            {
                                window.close();
                            }
                        }
                    }
                });

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
    $.fn.project_realtion_ship = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('project_realtion_ship')) {
                var plugin = new $.project_realtion_ship(this, options);
                $(this).data('project_realtion_ship', plugin);

            }

        });

    }

})(jQuery);
