jQuery(document).ready(function ($) {

    element_ui_datetimepicker = {
        init_ui_datetimepicker: function () {


            $(".block-item.block-item-datetimepicker").each(function () {
                self = $(this);
                self.datetimepicker({
                    defaultDate: "11/1/2013",
                    disabledDates: [
                        moment("12/25/2013"),
                        new Date(2013, 11 - 1, 21),
                        "11/22/2013 00:53"
                    ]
                });

            });
            Joomla_post.list_function_run_befor_submit.push(element_ui_datetimepicker.update_data);

        },
        update_data: function (data_submit) {
            $(".block-item.block-item-datetimepicker").each(function () {
                self=$(this);
                input_from= self.find('input.block-item-datetimepicker-from[type="hidden"]');
                input_to= self.find('input.block-item-datetimepicker-to[type="hidden"]');
                name_input_from=input_from.attr('name');
                name_input_to=input_to.attr('name');
                data_submit[name_input_from]=input_from.val();
                data_submit[name_input_to]=input_to.val();
            });
            return data_submit;
        }

    };
    element_ui_datetimepicker.init_ui_datetimepicker();


});