/**
 * Created by Son on 3/13/2015.
 */
(function ($) {
    $.fn.bookproedittable = function (options) {
        var settings = $.extend({
            // These are the defaults.
            class_delete_row: 'delete-row',//class icon bottom delete
            class_edit_row: 'edit-row',//class icon bottom edit
            class_enable_edit: 'enable-row',//class td enable edit
            class_update_row: 'update-row',//class icon bottom update
            class_save_row: 'save-row',//class icon bottom save
            attr_column_name: 'data-column-name',//name of input when editing
            link_delete: '',//link delete ajax process
            link_save: '',//link save ajax process
            link_update: '',//link update ajax process
            enable_multi_edit_row: true,//enable multi edit row
            class_delete_data_row:'deleterow',//
            class_new_row: 'new-row',
            input_id_edit:'',
            column_class_empty_when_editing:'',
            class_show_hide_icon:'show-hide',//class show icon save and hide icon update
            class_icon_country: 'icon-country',
            attr_data_path:"data-path",//data path to change image
            class_get_value_img:"value-img"//get data path img from input

        }, options);
        //console.log(settings);

        bookproedittableutil = {
            template_edit_buttom:function(id,path){
                html_template_edit_buttom=' '+
                '<div class="input-prepend input-append form_change_change_image" style="width: 210px">'+
                    '<div class="media-preview add-on" id="path_url" style="line-height: 0">'+
                        //'<span title="" class="hasTipPreview"><i class="icon-eye"></i></span>'+
                    '</div>'+
                    '<input type="text" class="input-small get-value-img" readonly="readonly" value="images/icon_country/ad.png" id="jform_path_'+id+'" name="path">'+
                    '<a rel="{handler: \'iframe\', size: {x: 800, y: 500}}" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;author=&amp;fieldid=jform_path_'+id+'&amp;folder='+path+'"'+
                    'title="Select" class="modal btn" style="line-height: 20px">'+
                    'Select</a><a onclick="jInsertFieldValue(\'\', \'jform_path_'+id+'\');return false;" href="#" title="" class="btn hasTooltip" data-original-title="Clear" style="line-height: 20px">'+
                    '<i class="icon-remove"></i></a>'+
                '</div>';
                return html_template_edit_buttom;
            },
            delete_row: function (self) {
                tr = self.closest('tr');
                var c = confirm('Do you want delete?');
                if(c == true){
                    tr.find('.' + settings.class_delete_data_row + ' .icheckbox_flat-blue' + ' #cb').each(function () {
                        this_td=$(this);
                        var delete_id = this_td.val();
                        ajaxSavePosition = $.ajax({
                            type: "GET",
                            url: settings.link_delete,
                            data: (function () {
                                dataPost = {
                                    id: delete_id
                                };
                                return dataPost;
                            })(),
                            beforeSend: function () {
                                // $('.loading').popup();
                            },
                            success: function (response) {
                                if(response == 'ok'){
                                    tr.remove();
                                    alert('Deleted successfully');
                                }else{
                                    alert(response);
                                }
                            }
                        });
                    })
                }else{
                };
            },
            revert_row: function (self) {
                tr = self;
                tr.find('td' + '.' + settings.class_enable_edit).each(function () {
                    td = $(this);
                    temp=td.find('.temp');
                    td.empty();
                    td.append(temp.html());
                });
                tr.find('.' + settings.class_icon_country).each(function(){
                    this_td=$(this);
                    this_td.find('.form_change_change_image').remove();
                    this_td.find('.img-icon').show();
                })
                tr.removeClass('row_editing');
            },
            btn_new_row: function (self) {
               // alert('ok');
                if(self.find('tbody tr').hasClass('adding-new'))
                {
                    return;
                }
                //form=self.parent().children().find('form')
                newTr=self.find('tbody tr:first').clone(true);
                newTr.find(settings.input_id_edit).val(0);
                newTr.find('.'+settings.column_class_empty_when_editing).html('');
                newTr.find('.'+settings.class_enable_edit).html('');
                newTr.addClass('adding-new');
                self.find('tbody').prepend(newTr);
                btnEdit=newTr.find('.' + settings.class_enable_edit);
                bookproedittableutil.new_row(btnEdit);
            },
            //self is table
            remove_row_not_id:function(self)
            {
                self.find('tbody tr.adding-new').each(function(){
                    tr=$(this);
                    if(tr.find(settings.input_id_edit).val()==0)
                    {
                        tr.remove();
                        //alert('remove');
                    }
                });
            },
            new_row: function (self) {
                //alert('ok');
                tr = self.closest('tr');
                tr.find('.' + settings.class_enable_edit).each(function () {
                    this_td = $(this);
                    name = this_td.attr(settings.attr_column_name);
                    content_this = this_td.text().trim();
                    input=$('<input type="text" name="' + name + '"  style="width:100%" value="' + content_this + '">');
                    this_td.append(input);
                });
                tr.find('.' + settings.class_update_row + ':first').each(function () {
                    this_td = $(this);
                    this_td.hide();
                });
                tr.find('.' + settings.class_edit_row + ':first').each(function () {
                    this_td = $(this);
                    this_td.hide();
                });
                tr.find('.' + settings.class_delete_row + ':first').each(function () {
                    this_td = $(this);
                    this_td.hide();
                });
                tr.find('.' + settings.class_save_row + ':first').each(function () {
                    this_td = $(this);
                    this_td.attr('type','button');
                 });

            },
            //self is bottom edit
            edit_row: function (self) {
                tr = self.closest('tr');
                id=tr.find(settings.input_id_edit).val();
                table = self.closest('table');
                table.find('thead th:eq(2)').attr('width','12%');
                bookproedittableutil.remove_row_not_id(table);
                if (tr.hasClass('row_editing')) {
                    return;
                }
                enable_multi_edit_row = settings.enable_multi_edit_row;
                if (!enable_multi_edit_row) {
                    table.find('tbody tr.row_editing').each(function () {
                        bookproedittableutil.revert_row($(this));
                    });
                }
                tr = self.closest('tr');
                tr.addClass('row_editing');
                tr.find('.' + settings.class_icon_country).each(function(){
                    this_td=$(this);
                    this_td.append(bookproedittableutil.template_edit_buttom(id,settings.attr_data_path));
                 });
                tr.find('td .img-icon').hide();
                tr.find('.' + settings.class_icon_country).each(function(){
                    this_td = $(this);
                    get_input_url = this_td.find('.' + settings.class_get_value_img).val();
                    input=$('<img src="'+this_host+'/'+get_input_url+'" style="margin-top:2px;width: 20px;height: 17px">');
                    this_td.find('#path_url').append(input);
                });
                tr.find('.' + settings.class_enable_edit).each(function () {
                    this_td = $(this);
                    temp=$('<div class="temp"></div>');
                    temp.hide();
                    name = this_td.attr(settings.attr_column_name);
                    content_this = this_td.text().trim();
                    temp.append(this_td.html());
                    this_td.empty();
                    this_td.append(temp);
                    input=$('<input type="text" name="' + name + '"  style="width:100%" value="' + content_this + '">');
                    this_td.append(input);
                });
                SqueezeBox.initialize({});
                SqueezeBox.assign($('a.modal').get(), {
                    parse: 'rel'
                });
            },
            save_row: function (self) {
                tr = self.closest('tr');
                console.log(tr);
                if (typeof ajaxSaveRow !== 'undefined') {
                    ajaxSaveRow.abort();
                }
                ajaxSaveRow = $.ajax({
                    type: "GET",
                    url: settings.link_save,
                    data: $.param(tr.find(':input'), false),
                    beforeSend: function () {
                        // $('.loading').popup();
                    },
                    success: function (response) {
                        alert('Saved successfully');
                        location.reload();
                    }
                });

            },
            update_row: function (self) {
                tr = self.closest('tr');
                table = self.closest('table');
                if (typeof ajaxUpdateRow !== 'undefined') {
                    ajaxUpdateRow.abort();
                }
                ajaxUpdateRow = $.ajax({
                    type: "GET",
                    url: settings.link_update,
                    data: $.param(tr.find(':input'), false),
                    beforeSend: function () {
                        // $('.loading').popup();
                    },
                    success: function (response) {
                        alert('Updated successfully');
                        tr.find('.' + settings.class_enable_edit).each(function () {
                            this_td=$(this);
                            var dataval=this_td.find('input').val();
                            this_td.html(dataval);
                        });
                        tr.find('.' + settings.class_icon_country).each(function(){
                            this_td=$(this);
                            this_td.find('.form_change_change_image').remove();
                            this_td.find('.img-icon').show();
                        })
                        tr.removeClass('row_editing');
                        //location.reload();
                    }
                });

               /* $(document).ajaxStop(function(){
                    window.location.reload();
                });*/
            },
            comparer: function comparer(index) {
                return function (a, b) {
                    var valA = Util.getCellValue(a, index), valB = bookproedittableutil.getCellValue(b, index)
                    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
                }
            },
            bookproedittable: function (self, sortype) {
                th_sorting = self.closest('th');
                position = th_sorting.find('.icon-sorting').attr('position');

                var table_sorting = self.closest('.bookproedittable');
                var rows = table_sorting.find('tr:gt(0)').toArray().sort(bookproedittableutil.comparer(position));
                if (sortype == 'desc') {
                    rows = rows.reverse();
                }
                for (var i = 0; i < rows.length; i++) {
                    table_sorting.append(rows[i])
                }
            }

        };


        bookpro_editTable_self = $(this);
        bookpro_editTable_self.find('.' + settings.class_delete_row).click(function () {
            bookproedittableutil.delete_row($(this));
        });
        bookpro_editTable_self.find('.' + settings.class_edit_row).click(function () {
            bookproedittableutil.edit_row($(this));
        });
        bookpro_editTable_self.find('.' + settings.class_new_row).click(function () {
            bookproedittableutil.btn_new_row(bookpro_editTable_self);
        });

        var ajaxUpdateRow;
        bookpro_editTable_self.find('.' + settings.class_update_row).click(function () {
            bookproedittableutil.update_row($(this));
        });

        var ajaxSaveRow;
        bookpro_editTable_self.find('.' + settings.class_save_row).click(function () {
            bookproedittableutil.save_row($(this));
        });
    };

}(jQuery));

