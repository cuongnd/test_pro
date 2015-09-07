jQuery(document).ready(function($){
    console.log('hello 123 element_ui_dropdown');
    element_ui_dropdown={
        init_ui_dropdown:function()
        {
             $('.control-element.control-element-dropdown').each(function(){
                 self=$(this);
                 self.parent().addClass('dropdown fixed-dropdown');
                 block_id=self.attr('data-block-id');
                 block_parent_id=self.attr('data-block-parent-id');
                 block_content=$('.block-content[data-block-id="'+block_id+'"]');
                 block_content.insertAfter(self);
                 $('.block-content[data-block-id="'+block_id+'"] a.block-item.block-item-dropdown').unwrap();
                 self.hide();

                 $('.dropdown-menu[data-block-id="'+block_id+'"]').click(function(event){
                     event.stopPropagation();
                 });


             });


        },
        add_row:function(self){
            self=$(self);
            block_id=self.attr('data-block-id');
            ajax=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertRow',
                        parentColumnId:block_id,
                        menuItemActiveId:menuItemActiveId,
                        ajaxgetcontent:1,
                        screenSize:screenSize

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {
                }
            });
        },
        add_control_element:function(self){
            block_id = self.attr('data-block-id');
            block_parent_id = self.attr('data-block-parent-id');
            control_element = $('.control-element.control-element-dropdown[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]');
            enable_add_control=self.attr('enable-add-control');

            enable_add_control=(typeof enable_add_control=='undefined')?1:enable_add_control;

            if(enable_add_control=="1") {

                block_content = $('<div class="block-content" data-block-id="' + block_id + '" data-block-parent-id="' + block_parent_id + '"></div>');
                block_content.append(self);
                block_content.append($('.block-item.block-item-dropdown[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]'));
                control_element.append(block_content);
                control_element.show();
                self.attr('enable-add-control', '0');
            }else{
                block_content=$('.block-content[data-block-id="'+block_id+'"][data-block-parent-id="' + block_parent_id + '"]');
                block_content.insertAfter(control_element);
                $('.block-content[data-block-id="'+block_id+'"] a.block-item.block-item-dropdown').unwrap();
                control_element.hide();
                self.attr('enable-add-control', '1');
            }

        }
    };
    $('.view_item_drop_down').draggable({
        handle: ".move-dropdown"
    });

    $('.a_drop_down').click(function show_drop_down_and_swich_control(e){
        if (e.ctrlKey)
        {
            element_ui_dropdown.add_control_element($(this));
        }else {
            selft = $(this);
            block_id = selft.attr('data-block-id');
            block_parent_id = selft.attr('data-block-parent-id');
            view_item_drop_down = $('.view_item_drop_down[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]');
            view_item_drop_down.show();
            $('.container-website').addClass('view_dropdown');
        }
    });
    $('a.hide-element-dropdow').click(function(){

        selft=$(this);
        block_id=selft.attr('data-block-id');
        block_parent_id=selft.attr('data-block-parent-id');
        $('.container-website').removeClass('view_dropdown');
        view_item_drop_down =selft.closest('.view_item_drop_down');
        view_item_drop_down.hide();

    });




});
