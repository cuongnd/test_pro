jQuery(document).ready(function($){

    elementuitab={

        innittab:function(){
            for(var i=0;i<=$('.tabs[data-block-parent-id!="0"][data-block-id!="0"]').length;i++){
                tab=$('.tabs[data-block-parent-id!="0"][data-block-id!="0"]:eq('+i+')');
                block_id=tab.attr('data-block-id');
                console.log(block_id);

                if(tab.hasClass('tab_'+block_id))
                    continue;
                tab.addClass('tab_'+block_id);
                tab.addClass('tab_ui');
                ul=tab.find('>ul.nav-tabs');
                if(!ul.length)
                {
                    ul=$('<ul class="nav nav-tabs nav-justified"></ul>');
                    tab.prepend(ul);
                }
                ul.attr('role','tablist');
                if(enableEditWebsite)
                {
                    tab_pane=tab.find('>.control-element.control-element-tabcontent[data-block-parent-id="'+block_id+'"]');
                }else
                {
                    tab_pane=tab.find('>.block-item.block-item-tabcontent[data-block-parent-id="'+block_id+'"]');
                }
                tab_pane.each(function(){
                    currentTab=$(this);

                    current_block_id=currentTab.attr('data-block-id');
                    block_parent_id=currentTab.attr('data-block-parent-id');
                    currentTitleTab=currentTab.attr('data-tab-title');
                    if(currentTitleTab=='')
                    {
                        currentTab.attr('data-tab-title','tab-content-'+current_block_id);
                    }
                    href='tab_content'+current_block_id;
                    li=$('<li data-block-parent-id="'+block_parent_id+'" data-block-id="'+current_block_id+'" role="presentation">' +
                    '<a enable-double-click-edit="true" data-block-field="params_text" aria-controls="'+href+'" href="#'+href+'" data-block-parent-id="'+block_parent_id+'" data-block-id="'+current_block_id+'"  role="tab" data-toggle="tab" >'+currentTab.attr('data-tab-title')+'</a>' +
                    '</li>');
                    ul.append(li);
                    currentTab.attr('id',href);
                    currentTab.attr('role','tabpanel');
                });
                tabContent=tab.find('>.tab-content');
                if(!tabContent.length)
                {
                    tabContent=$('<div class="tab-content"></div>');
                    tab.append(tabContent);
                }
                tabContent.append(tab_pane);
                tab.attr('role','tabpanel');
                console.log('.tabs.tab_'+block_id+' a[data-block-parent-id="'+block_id+'"]:first');
                var index_of= $.cookie('block_item_tab_'+block_id);
                if(typeof index_of==="undefined")
                {
                    index_of=0;
                }
                console.log(index_of);
                $('.tabs.tab_'+block_id+' a[data-block-parent-id="'+block_id+'"][role="tab"]:eq('+index_of+')').tab('show');

            }
            $('.block-item.block-item-tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target);
                var data_block_parent_id=target.attr('data-block-parent-id');
                var li=target.closest('li[data-block-parent-id="'+data_block_parent_id+'"]');
                $.cookie('block_item_tab_'+data_block_parent_id,li.index());
            });



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
        elementuitab.remove_tab($(this));
    });


});