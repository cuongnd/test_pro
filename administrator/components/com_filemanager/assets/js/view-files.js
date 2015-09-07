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


    function autoTreenode()
    {
        node=null;
        for(var i=0;$('a.treenode').length;i++)
        {
            data_loaded=$('a[data-type="folder"].treenode:eq('+i+')').attr('data-loaded');
            if(typeof data_loaded=="undefined")
            {
                node=$('a.treenode:eq('+i+')');
                break;
            }
        }
        if(typeof node==null)
        {
            return;
        }
        topPosition = node.offset().top;
        $('html, body').animate({scrollTop:(topPosition-150)}, 'slow');
        node.css({
            color:"red"
        });
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
                node.css({
                    color:"inherit"
                });
                renderItem(response,node);
                autoTreenode();
            }
        });

    }
    function showHideSubFolder(node)
    {
        tr=node.closest('.item-folder');
        rootTable=node.closest('table.foldersfiles');
        if(rootTable.hasClass('foldersfileslocal'))
        {
            showHideSubFolderLocalServer(tr,0);
            tr_index= tr.index('table.foldersfiles:eq(0) tbody tr');
            tr_server=$('table.foldersfiles:eq(1) tbody tr.item:eq('+(tr_index)+')');
            showHideSubFolderLocalServer(tr_server,1);

        }else if(rootTable.hasClass('foldersfilesserver'))
        {
            showHideSubFolderLocalServer(tr,1);
            tr_index= tr.index('table.foldersfiles:eq(1) tbody tr');
            tr_local=$('table.foldersfileslocal:eq(0) tbody tr.item:eq('+(tr_index)+')');
            showHideSubFolderLocalServer(tr_local,0);

        }
    }
    function showHideSubFolderLocalServer(tr,tableInex)
    {
        dataNode=tr.attr('data-node');
        dataNode=dataNode+'.';
        tr_index= tr.index('table.foldersfileslocal:eq('+tableInex+') tbody tr.item');
        for(var i=tr_index;i<$('table.foldersfiles:eq('+tableInex+') tbody tr.item').length;i++)
        {
            next_tr=$('table.foldersfiles:eq('+tableInex+') tbody tr.item:eq('+i+')');
            data_sub_node=next_tr.attr('data-node');
            data_sub_node=data_sub_node.substring(0,dataNode.length);
            if(data_sub_node==dataNode)
            {
                next_tr.joomlaSwitchClass('show_sub_child','hide_sub_child');
            }
        }
        tr.find('i.icon-plus-minus').joomlaSwitchClass('icon-plus-sign','icon-minus-sign');
    }
    function renderItem(response,node)
    {
        response= $.parseJSON(response);
        tr=node.closest('.item-folder');
        rootTable=node.closest('table.foldersfiles');
        if(rootTable.hasClass('foldersfileslocal'))
        {
            renderItemHtmlLocalAndServer(response,tr,'local');
            tr_index= tr.index('table.foldersfiles:eq(0) tbody tr');
            tr_server=$('table.foldersfilesserver tbody tr.item:eq('+(tr_index)+')');
            renderItemHtmlLocalAndServer(response,tr_server,'server');
        }
        else if(rootTable.hasClass('foldersfilesserver'))
        {

            renderItemHtmlLocalAndServer(response,tr,'server');
            tr_index= tr.index('table.foldersfiles:eq(1) tbody tr');
            tr_local=$('table.foldersfileslocal tbody tr.item:eq('+(tr_index)+')');
            renderItemHtmlLocalAndServer(response,tr_local,'local');
        }




    }
    Joomla.submitbutton = function(task)
    {

        if (task == 'autotreenode')
        {
            autoTreenode();
        }
        if (task == 'server_calculator_files')
        {
            serverCalculatorFiles();
        }
    };
    function serverCalculatorFiles()
    {
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function () {

                dataPost = {
                    option: 'com_filemanager',
                    task: 'files.AjaxServerCalculatorFiles'

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
                node.css({
                    color:"inherit"
                });

            }
        });

    }
    function renderItemHtmlLocalAndServer(response,tr,key)
    {
        dataNode=tr.attr('data-node');
        space_padding_left=tr.find('span.space').css('padding-left');
        space_padding_left=parseInt(space_padding_left);
        console.log(space_padding_left);
        for(var i=response.length-1;i>=0;i--)
        {
            item=response[i][key];
            clone_tr=tr.clone();
            link= clone_tr.find('a.treenode');
            clone_tr.attr('data-node',dataNode+'.'+ i.toString());
            clone_tr.addClass('show_sub_child');
            link.html(item!=null?item.name:'');
            data_path=link.attr('data-path');
            clone_tr.find('span.space').css({
                "padding-left":(space_padding_left+20).toString()+'px'
            }).html('|--');
            clone_tr.find('td.item-size').html(item!=null?item.size:'');
            clone_tr.find('td.item-mtime').html(item!=null?item.mtime:'');
            if(item!=null&&item.type=='file')
            {
                link.removeClass('treenode');
            }
            link.attr('data-path',data_path+'/'+(item!=null?item.name:''));
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