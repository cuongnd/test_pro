jQuery(document).ready(function ($) {

    element_ui_daterangepicker = {
        init_ui_daterangepicker: function () {
            $(".block-item.block-item-daterangepicker").each(function () {
                self = $(this);

                input_from=self.find('input.block-item-daterangepicker-from[type="hidden"]');
                input_to=self.find('input.block-item-daterangepicker-to[type="hidden"]');
                startDate=input_from.val();
                endDate=input_to.val();
                format='YYYY-MM-DD';
                self.daterangepicker(
                    {
                        format: format,
                        startDate: startDate,
                        endDate: endDate,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        }
                    },
                    function (start, end, label) {
                    }).on('apply.daterangepicker', function(ev, picker) {
                        self=$(picker.element);
                        input_from_to=self.find('input.block-item-daterangepicker-from-to');
                        input_from=self.find('input.block-item-daterangepicker-from[type="hidden"]');
                        input_to=self.find('input.block-item-daterangepicker-to[type="hidden"]');
                        input_from_to.val(picker.startDate.format('YYYY-MM-DD')+';'+picker.endDate.format('YYYY-MM-DD'));
                        input_from.val(picker.startDate.format('YYYY-MM-DD'));
                        input_to.val(picker.endDate.format('YYYY-MM-DD'));
                    });


            });
            Joomla_post.list_function_run_befor_submit.push(element_ui_daterangepicker.update_data);

        },
        update_data: function (data_submit) {
            $(".block-item.block-item-daterangepicker").each(function () {
                self=$(this);
                input_from= self.find('input.block-item-daterangepicker-from[type="hidden"]');
                input_to= self.find('input.block-item-daterangepicker-to[type="hidden"]');
                name_input_from=input_from.attr('name');
                name_input_to=input_to.attr('name');
                data_submit[name_input_from]=input_from.val();
                data_submit[name_input_to]=input_to.val();
            });
            return data_submit;
        }

    };
    element_ui_daterangepicker.init_ui_daterangepicker();


});