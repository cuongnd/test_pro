jQuery(document).ready(function ($) {

    element_ui_grid = {
        int_ui_grid: function () {

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
                               /* var dataSource = new kendo.data.DataSource({
                                    data:response.models
                                });*/




                            }
                        });
                    }
                });
                //end setup kendoSortable
                //setup checkbox
                enable_select_item_by_checked  = self.attr('data-enable-select-item-by-checked');
            });
        }

    };


});