jQuery(document).ready(function($){
    utilityDataSource={
        init_utility_dataSource:function(){
           /* $('#window').draggable({
                handle: '#windowtitle'
            });*/
            //var d = new SQL.Designer();




        },
        getListTable:function(){
            var listTable={};
            $('.panel-database-table').each(function(index){
                //listTable[index]=$(this).attr('data-table-name');
                table_name=$(this).attr('data-table-name');
                listTable[table_name]={};
                $(this).find('.list-field .item-field').each(function(index2){
                    listTable[table_name][index2]=$(this).attr('data-table-field');
                });
            });
            return listTable;
        },
        updateTableInSelectTableAndFunction:function()
        {
            listTable=utilityDataSource.getListTable();
            $('.select-tables').find('option[value!="0"]').remove();
            $('.table-and-function .list-field').empty();

            $.each(listTable, function( index, $fields ) {
                $('.select-tables').append('<option value="'+index+'">'+index+'</option>');
                $.each($fields, function( index, field ) {
                    $('.table-and-function .list-field').append('<li data-table-field="'+field+'"><a href="javascript:void(0)">'+field+'</a></li>');
                });
            });



        }
    };
    $( ".item-table" ).draggable({
        appendTo: 'body',
        helper: "clone"
    }).css({
        'z-index':'auto'
    });
    $('.diagrams').droppable({
        accept: ".item-table",
        greedy: true,
        drop: function(ev,ui){
            uiDraggable=$(ui.draggable);
            droppable=$(this);
            renderTable(uiDraggable,droppable);
        }
    });


    $('.show-select-table-and-function').focus(function(){
        $('.table-and-function').removeClass( "table-and-function-hide" );
    });
    $('.show-table-and-function').click(function(){
        $('.table-and-function').toggleClass( "table-and-function-hide", 1000 );
    });


/*
    html.draggable({
        handle: '.field-config-heading'
    });
*/

    $(document).on('.panel-database-table .panel-controls .panel-close','click',function(e){
        sprFlat=$('body').data('sprFlat');
        console.log('hello panel');
    });

    var ajaxRederTable;
    function renderTable(uiDraggable,droppable)
    {
        table=uiDraggable.attr('data-table');
        if(typeof ajaxRederTable !== 'undefined'){
            ajaxRederTable.abort();
        }
        ajaxRederTable=$.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'table.aJaxInsertTable',
                    table:table

                };
                return dataPost;
            })(),
            beforeSend: function () {
                // $('.loading').popup();
            },
            success: function (response) {
                response=$(response);
                droppable.append(response);
               /* $('.panel-database-table').draggable({
                    containment:"parent",
                    handle: '.panel-heading-database-table'
                });*/
                response.find('.list-field .item-field').each(function(){
                });
                utilityDataSource.updateTableInSelectTableAndFunction();
            }
        });
    }
});
