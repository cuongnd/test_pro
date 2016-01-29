jQuery(document).ready(function ($) {
    excel_block=[];
    element_ui_excel = {
        init_ui_excel: function () {
            $(".block-item.block-item-excel").each(function () {
                var self = $(this);
                var data_block_id=self.attr('data-block-id');
                var excel_input_hidden= self.find('.block-item.block-item-excel-input-hidden');
                var data = excel_input_hidden.val();
                data = $.base64.decode(data);
                data = $.parseJSON(data);
                var $container = self;
                excel_block[data_block_id] = new Handsontable($container[0], {
                    data: data,
                    colHeaders: true,
                    minSpareRows: 1,
                    maxRows: data.length,
                    formulas: true,
                    currentRowClassName: 'currentRow',
                    currentColClassName: 'currentCol',
                    cells: function (row, col, prop) {
                        data=this.instance.getData();
                        //console.log(data[row]);
                        //console.log(prop);
                        var cellProperties = {};
                        if (row === 0) {
                            cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
                        }
                        if (col === 0) {
                            cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
                        }
                        return cellProperties;
                    }

                });
                excel_block[data_block_id].loadData(data);
            });
            Joomla_post.list_function_run_befor_submit.push(element_ui_excel.update_data);

        },
        update_data: function (data_submit) {
            $(".block-item.block-item-excel").each(function () {
                var self = $(this);
                var input_hidden_excel = self.find('.block-item.block-item-excel-input-hidden[type="hidden"]');
                var input_hidden_excel_name = input_hidden_excel.attr('name');
                var data= input_hidden_excel.val();
                data = $.base64.decode(data);
                data = $.parseJSON(data);
                data_submit[input_hidden_excel_name] =data;
            });
            return data_submit;
        }

    };
    element_ui_excel.init_ui_excel();


});