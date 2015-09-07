jQuery(document).ready(function($){
    $(document).on('click','.treenode',function(){
        node=$(this);
        data_type=node.attr('data-type');
        if(data_type=='file')
        {
            return;
        }
        data_loaded=node.attr('data-loaded');
        if(data_loaded=="1")
        {
            showHideSubFolder(node);
            return;
        }
        data_path=node.attr('data-path');
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function () {

                dataPost = {
                    option: 'com_filemanager',
                    task: 'files.AjaxGetFoldersFiles',
                    data_path:data_path

                };
                return dataPost;
            })(),
            beforeSend: function () {
                $('.div-loading').css({
                    display: "block"


                });
                // $('.loading').popup();
            },
            success: function (response) {
                $('.div-loading').css({
                    display: "none"


                });
                renderItem(response,node);
            }
        });

    });
    function showHideSubFolder(node)
    {
        tr=node.closest('.item-folder');
        dataNode=tr.attr('data-node');
        dataNode=dataNode+'.';
        tr_index= tr.index('tr');
        console.log($('#foldersfiles tbody tr.item').length);
        for(var i=tr_index;i<$('#foldersfiles tbody tr.item').length;i++)
        {
            next_tr=$('#foldersfiles tbody tr.item:eq('+i+')');
            data_sub_node=next_tr.attr('data-node');
            data_sub_node=data_sub_node.substring(0,dataNode.length);
            if(data_sub_node==dataNode)
            {
                next_tr.joomlaSwitchClass('show_sub_child','hide_sub_child');
            }
        }
        console.log(tr_index);
    }
    function renderItem(response,node)
    {
        response= $.parseJSON(response);
        console.log(response);
        tr=node.closest('.item-folder');
        dataNode=tr.attr('data-node');
        space_padding_left=tr.find('span.space').css('padding-left');
        space_padding_left=parseInt(space_padding_left);
        console.log(space_padding_left);
        for(var i=response.length-1;i>0;i--)
        {
            item=response[i];
            clone_tr=tr.clone();
            link= clone_tr.find('a.treenode');
            clone_tr.attr('data-node',dataNode+'.'+ i.toString());
            clone_tr.addClass('show_sub_child');
            link.html(item.name);
            data_path=link.attr('data-path');
            clone_tr.find('span.space').css({
                "padding-left":(space_padding_left+20).toString()+'px'
            }).html('|--');
            clone_tr.find('td.item-size').html(item.size);
            clone_tr.find('td.item-mtime').html(item.mtime);
            link.attr('data-path',data_path+'/'+item.name);
            tr.after(clone_tr);
        }
        tr.find('i.icon-plus-minus').joomlaSwitchClass('icon-plus-sign','icon-minus-sign');
        tr.find('a.treenode').attr('data-loaded',"1");

    }
    $.fn.joomlaSwitchClass = function(classA,classB) {
        if($(this).hasClass(classA))
        {
            $(this).removeClass(classA).addClass(classB);
        }
        else
        {
            $(this).removeClass(classB).addClass(classA);
        }
    }
});