(function ($) {

    // here we go!
    $.ui_grid = function (element, options) {

        // plugin's default options
        var defaults = {
            mode_select_column_template:[],
            hide_footer:false,
            grid_option:{
                groupable: true
            }
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
            grid_option=plugin.settings.grid_option;
            mode_select_column_template=plugin.settings.mode_select_column_template;

            grid_option.columns=mode_select_column_template;
            var hide_footer=plugin.settings.hide_footer;
            if(hide_footer)
            {
                grid_option.pageable=false;
            }
            plugin.ui_grid=$element.kendoGrid(grid_option);


/*
            $('.k-grid.k-widget').each(function () {
                self = $(this);
                attr_id = self.attr('id');
                //setup kendoSortable
                var kendo_grid = self.data("kendoGrid");
                console.log('hello int_ui_grid');
                kendo_grid.table.kendoSortable({
                    filter: ">tbody >tr",
                    hint: $.noop,
                    axis: "y",
                    cursor: "move",
                    ignore: "input,a.k-button,.k-dropdown",
                    placeholder: function (element) {
                        return element.clone().addClass("k-state-hover").css("opacity", 0.65);
                    },
                    container: "#" + attr_id + " tbody",
                    change: function (e) {
                        console.log('hello change');
                        var kendo_grid=e.item.closest('.k-grid.k-widget');
                        var block_id=kendo_grid.attr('data-block-id');
                        kendo_grid= kendo_grid.data("kendoGrid");

                        var skip = kendo_grid.dataSource.skip(),
                            oldIndex = e.oldIndex + skip,
                            newIndex = e.newIndex + skip,
                            data = kendo_grid.dataSource.data(),
                            dataItem = kendo_grid.dataSource.getByUid(e.item.data("uid"));
                        kendo_grid.dataSource.remove(dataItem);
                        //console.log(e);
                        kendo_grid.dataSource.insert(e.newIndex, dataItem);
                        data = kendo_grid.dataSource.data();
                        console.log(data);
                        $.each(data, function( index, value ) {
                            if(typeof value.ordering!=="undefined")
                            {
                                data[index].ordering= index;
                            }
                        });
                        var dataSource = new kendo.data.DataSource({
                            data:data
                        });

                        kendo_grid.setDataSource(dataSource);
                        if(typeof web_design !== 'undefined'){
                            web_design.abort();
                        }
                        web_design = $.ajax({
                            type: "POST",
                            dataType: "json",
                            contentType: "application/json",
                            url: this_host + '/index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=type&block_id='+block_id,
                            data: (function () {
                                dataPost = {
                                    models: data.toJSON()
                                };
                                return JSON.stringify(dataPost) ;
                            })(),
                            beforeSend: function () {
                                $('.div-loading').css({
                                    display: "block"


                                });

                                // $('.loading').popup();
                            },
                            success: function (response) {
                                $('.div-loading').css({
                                    display: "none"


                                });
                                /!* var dataSource = new kendo.data.DataSource({
                                 data:response.models
                                 });*!/




                            }
                        });
                    }
                });
                //end setup kendoSortable
                //setup checkbox
                enable_select_item_by_checked  = self.attr('data-enable-select-item-by-checked');
            });
*/

        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_grid = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_grid')) {
                var plugin = new $.ui_grid(this, options);
                $(this).data('ui_grid', plugin);

            }

        });

    }

})(jQuery);



