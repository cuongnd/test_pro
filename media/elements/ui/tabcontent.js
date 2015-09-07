jQuery(document).ready(function($){

    element_ui_tab_content={
        init_tab_content:function(){
            $('.control-element.control-element-tabcontent .block-item.block-item-tabcontent').each(function(){
                self=$(this);
                self.sortable({
                    handle: ".move-sub-row",
                    axis: "y",
                    items: "> .row-content.block-item",
                    stop: function (event, ui) {
                        var screenSize = $('select[name="smart_phone"] option:selected').val();
                        screenSize = screenSize.toLowerCase();
                        var list_row = {};
                        $('.block-item.block-item-tabcontent[data-block-id="' + ui.item.attr('data-block-parent-id') + '"] > .row-content.show-grid-stack-item:visible').each(function (index) {

                            list_row[$(this).attr('data-block-id')] = {
                                ordering: index,
                                screenSize: screenSize
                            }

                        });

                        if (typeof ajax_web_design !== 'undefined') {
                            ajax_web_design.abort();
                        }
                        ajax_web_design = $.ajax({
                            type: "GET",
                            url: this_host + '/index.php',
                            data: (function () {

                                dataPost = {
                                    option: 'com_utility',
                                    task: 'utility.aJaxUpdateRowsInScreen',
                                    listRow: list_row,
                                    menuItemActiveId: menuItemActiveId

                                };
                                return dataPost;
                            })(),
                            beforeSend: function () {

                                // $('.loading').popup();
                            },
                            success: function (response) {


                            }
                        });


                    }

                });
            });

        }
    }
});