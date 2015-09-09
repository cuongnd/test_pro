jQuery(document).ready(function($){

    element_ui_quick_edit={

        init_ui_quick_edit:function(){
            $('.quick_edit[editable="true"]').each(function(){
                $(this).editable({
                    type: 'text',
                    pk: 1,
                    url: 'index.php?option=com_utility&task=utility.updateData',
                    title: function()
                    {
                        return $(this).attr('data-title');
                    }
                });

            });

        },
        change_state_edit_able:function(self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            if(self.val()==1)
            {
                $('.quick_edit[data-block-id="'+block_id+'"]').editable({
                    disabled:false
                }).attr('editable','true');
            }else
            {
                $('.quick_edit[data-block-id="'+block_id+'"]').editable({disabled:true}).attr('editable','false');
            }


        },
        add_tab:function(self){
            object_id=self.closest('.properties.block').attr('data-object-id');
            tab=$('.tabs[data-block-id="'+object_id+'"]');
            //tab.html("<div style='background:red'>sadasdasdasdasdasdasdasd</div>")
            ajaxInsertElement=$.ajax({
                type: "GET",
                url: this_host+'/index.php',

                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertElement',
                        parentColumnId:object_id,
                        menuItemActiveId:menuItemActiveId,
                        ajaxgetcontent:1,
                        pathElement:'media/elements/ui/tabcontent.php'

                    };
                    return dataPost;
                })(),
                beforeSend: function () {


                    // $('.loading').popup();
                },
                success: function (response) {

                    response= $.parseJSON(response);
                    html=$(response.html);
                    html=$(html);
                    block_id=response.blockId;
                    block_parent_id=html.attr('data-block-parent-id');
                    href='tab_content'+block_id;
                    html.attr('id',href);
                    li=$('<li role="presentation">' +
                    '<a aria-controls="'+href+'" href="#'+href+'" role="tab" data-toggle="tab" >tab-content-'+block_id+'</a>' +
                    '</li>');

                    tab.find('.nav-tabs').append(li);
                    tab.find('.tab-content').append(html);
                    html.find('.row-content[data-block-parent-id="'+block_id+'"]').css({
                        display:"block"
                    });
                    html.find('.grid-stack[data-block-parent-id="'+block_id+'"]').gridstackDivRow(optionsGridIndex);

                }
            });
        },
        //self button delete
        remove_tab:function(self){
            block_id=self.attr('data-block-id');
            block_parent_id=self.attr('data-block-parent-id');
            tab=$('.tabs[data-block-id="'+block_parent_id+'"]');
            ajaxInsertElement=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxRemoveElement',
                        block_id:block_id

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {
                    tab.find('.nav-tabs li[data-block-id="'+block_id+'"]').remove();
                    tab.find('.tab-content div.tab-pane[data-block-id="'+block_id+'"]').remove();



                }
            });
        }
    };
    //$('.tab_ui .remove-tab-content').click(function(){
    $(document).delegate(".tab_ui .remove-tab-content","click",function(e){
        element_ui_quick_edit.remove_tab($(this));
    });


});