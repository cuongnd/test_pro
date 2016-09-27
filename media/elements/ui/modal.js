jQuery(document).ready(function($){

    element_ui_modal=$.extend({
        option_sortable:{
            items:'>.row-content',
            containment: "parent",
            handle:'.move-sub-row',
            axis: "y",
            scroll: false,
            //start: element_ui_div_row.on_start_moving,
            stop:function(event, ui) {
                parent_block_id = ui.item.attr("data-block-parent-id");
                screen_size_id = $('select[name="smart_phone"] option:selected').val();
                //screensize = screenSize.toLowerCase();
                listElement = {};
                $('.block-item.block-item-modal[enable-sortable="true"][data-block-id="' + parent_block_id + '"]').find('>.row-content[data-block-parent-id="' + parent_block_id + '"]').each(function (index) {

                    listElement[$(this).attr('data-block-id')] = {
                        ordering: index,
                        screenSize: screen_size_id
                    }

                });

                if (typeof ajaxUpdateElement !== 'undefined') {
                    ajaxUpdateElement.abort();
                }

                ajaxUpdateElement = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.aJaxUpdateElements',
                            listElement: listElement,
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
        },
        init_modal:function(){
            $('.block-item-modal[enable-sortable="true"]').sortable(element_ui_modal.option_sortable);
        },
        on_of_sortable:function(self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            if(self.val()==1)
            {
                $('.block-item-modal[enable-sortable="true"]').sortable(option_sortable);
            }else{
                $('.block-item-modal[enable-sortable="true"]').sortable("destroy");
            }
        },
        add_modal_content:function(self){
            object_id=$('.properties.block').attr('data-object-id');
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
                        pathElement:'media/elements/ui/modalcontent.php'

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
                    li=$('<li role="presentation"><a href="javascript:void(0)" data-block-parent-id="'+block_parent_id+'" data-block-id="'+block_id+'" class="remove-tab-content"><i class="glyphicon-remove glyphicon "></i></a><a aria-controls="'+href+'" href="#'+href+'" role="tab" data-toggle="tab" >tab-content-'+block_id+'</a></li>');
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
        show_modal:function(self){
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
        },
        moveModal:function(modal){
            controlElement=modal.closest('.main-container');
            controlElement.append(modal);

        }
    }, element_ui_element);
    if(enableEditWebsite) {
        $('.block-item-modal-content.modal').on('show.bs.modal', function (e) {
            $(this).prependTo('.main-container');
            $('body').addClass('control-element-modal-open');
            $(document).off('focusin.modal');
            $(".chzn-container").each(function () {
                $(this).attr('style', 'width: 100%');
            });
            console.log('hello open madal');
        })
        $('.block-item-modal-content.modal').on('hidden.bs.modal', function (e) {
            block_id = $(this).attr('data-block-id');
            $(this).appendTo('.control-element.control-element-modal[data-block-id="' + block_id + '"]');
            $('body').removeClass('control-element-modal-open');
            console.log('hello close modal');
        });
    }else{
        $('.modal').on('show.bs.modal', function (e) {
            $(".chzn-container").each(function () {
                $(this).attr('style', 'width: 100%');
            });
        })
    }


});