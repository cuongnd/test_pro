jQuery(document).ready(function ($) {

    element_ui_select2_dropdown = {

        init_ui_select2_dropdown: function () {
            $('.block-item.select2dropdown[editable="true"]').each(function () {
                self = $(this);
                var allow_clear = self.data('allow_clear');
                var block_id = self.attr('data-block-id');
                var source_key = self.attr('data-source_key');
                var source_value = self.attr('data-source_value');
                $(this).editable(
                    {
                        select2: {
                            placeholder: 'Select Country',
                            allowClear: true,
                            minimumInputLength: 3,
                            id: function (item) {
                                return item[source_key];
                            },
                            ajax: {
                                url: this_host+'/index.php?option=com_phpmyadmin&task=datasource.read_data_by_editable&block_id='+block_id,
                                dataType: 'json',
                                data: function (term, page) {
                                    return { keyword: term };
                                },
                                results: function (data, page) {
                                    return { results: data };
                                }
                            },
                            formatResult: function (item) {
                                return item[source_value];
                            },
                            formatSelection: function (item) {
                                return item[source_value];
                            },
                            initSelection: function (element, callback) {
                                return $.get(this_host+'/index.php?option=com_phpmyadmin&task=datasource.read_data_by_editable&block_id='+block_id, { key_value: element.val() }, function (data) {
                                    callback(data[0]);
                                });
                            }
                        }
                    });


            });

        },
        change_state_edit_able: function (self) {
            properties = self.closest('.properties.block');
            block_id = properties.attr('data-object-id');
            if (self.val() == 1) {
                $('.select2_dropdown[data-block-id="' + block_id + '"]').editable({
                    disabled: false
                }).attr('editable', 'true');
            } else {
                $('.select2_dropdown[data-block-id="' + block_id + '"]').editable({disabled: true}).attr('editable', 'false');
            }


        },
    };



});