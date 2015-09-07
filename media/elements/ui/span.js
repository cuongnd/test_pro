jQuery(document).ready(function($){

    elementuispan={

        initSpan:function(){
            for(var i=0;i<$('.tabs[data-block-parent-id!="0"][data-block-id!="0"]').length;i++){
                tab=$('.tabs[data-block-parent-id!="0"][data-block-id!="0"]:eq('+i+')');
                block_id=tab.attr('data-block-id');
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
                tab_pane=tab.find('>.tab-pane[data-block-parent-id="'+block_id+'"]');
                tab_pane.each(function(){
                    currentTab=$(this);

                    block_id=currentTab.attr('data-block-id');
                    block_parent_id=currentTab.attr('data-block-parent-id');
                    currentTitleTab=currentTab.attr('data-tab-title');
                    if(typeof currentTitleTab==='undefined')
                    {
                        currentTab.attr('data-tab-title','tab-content-'+block_id);
                    }
                    href='tab_content'+block_id;
                    li=$('<li data-block-parent-id="'+block_parent_id+'" data-block-id="'+block_id+'" role="presentation"><a href="javascript:void(0)" data-block-parent-id="'+block_parent_id+'" data-block-id="'+block_id+'" class="remove-tab-content"><i class="glyphicon-remove glyphicon r"></i></a><a aria-controls="'+href+'" href="#'+href+'" role="tab" data-toggle="tab" >'+currentTab.attr('data-tab-title')+'</a></li>');
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
                tab.tab();
            }


        },
        add_block:function(self,object_id){
            ajaxInsertElement=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertRow',
                        parentColumnId:object_id,
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
        }
    };


});