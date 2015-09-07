jQuery(document).ready(function($){

    element_ui_select={
        select2_option: {
            ajax: {
                url: this_host + "/index.php?option=com_menus&task=item.ajax_get_list_icon",
                dataType: 'json',
                delay: 250,
                data: function (term, page) {
                    return {
                        keyword: term
                    };
                },

                results: function (data) {
                    return {results: data};
                },
                cache: true

            },
            initSelection: function (element, callback) {
                item = {
                    id: element.val(),
                    text: element.val()
                };
                return callback(item);
            },
            formatResult: function (result, container, query, escapeMarkup) {

                return '<span><i class="' + result.text + '"></i>' + result.text + '</span>';
            },

            formatSelection: function (data, container, escapeMarkup) {

                return '<span><i class="' + data.text + '"></i>' + data.text + '</span>';
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1
        },
        input_ui_select:function(){
            /*$('select.block-item.block-item-select').each(function(){
                self=$(this);
                self.select2(element_ui_select.select2_option);
            });*/
            element_ui_button.list_function_run_befor_submit.push(element_ui_select.update_data);
        },
        update_data:function(data_submit){
            $(".block-item.block-item-select").each(function(){
                select=$(this);
                name_select=select.attr('name');
                data_submit[name_select]=select.val();
            });
            return data_submit;
        }
    };
    element_ui_select.input_ui_select();


});