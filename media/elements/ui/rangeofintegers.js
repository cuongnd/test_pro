jQuery(document).ready(function($){

    element_ui_rangeofintegers={
        init_ui_rangeofintegers:function(){
            $(".block-item.block-item-rangeofintegers").each(function(){
                self=$(this);
                type=self.attr('data-type');
                min=self.attr('data-min');
                max=self.attr('data-max');

                from=self.find('input.block-item-rangeofintegers-from').val();
                to=self.find('input.block-item-rangeofintegers-to').val();
                self.ionRangeSlider({
                    type: "double",
                    min: min,
                    max: max,
                    from: from,
                    to: to,
                    keyboard: true,
                    onFinish: function (data) {
                        self=data.input;
                        input_from=self.find('input.block-item-rangeofintegers-from');
                        input_to=self.find('input.block-item-rangeofintegers-to');
                        input_from.val(data.fromNumber);
                        input_to.val(data.toNumber);
                    }
                });

            });
            Joomla_post.list_function_run_befor_submit.push(element_ui_rangeofintegers.update_data);
        },
        update_data:function(data_submit)
        {
            $(".block-item.block-item-rangeofintegers").each(function(){
                self=$(this);
                input_from= self.find('input.block-item-rangeofintegers-from');
                input_to= self.find('input.block-item-rangeofintegers-to');
                name_input_from=input_from.attr('name');
                name_input_to=input_to.attr('name');
                data_submit[name_input_from]=input_from.val();
                data_submit[name_input_to]=input_to.val();

            });
            return data_submit;

        }

    };
    element_ui_rangeofintegers.init_ui_rangeofintegers();


});