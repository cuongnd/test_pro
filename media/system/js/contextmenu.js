(function ($, window) {

    $.fn.contextMenu = function (settings) {

        return this.each(function () {

            // Open context menu
            $(this).on("contextmenu", function (e) {
                //open menu
                $(settings.menuSelector)
                    .data("invokedOn", $(e.target))
                    .show()
                    .css({
                        position: "absolute",
                        left: getLeftLocation(e),
                        top: getTopLocation(e)
                    })
                    .off('click')
                    .on('click', function (e) {
                        $(this).hide();

                        var $invokedOn = $(this).data("invokedOn");
                        var $selectedMenu = $(e.target);

                        settings.menuSelected.call(this, $invokedOn, $selectedMenu);
                    });

                return false;
            });

            //make sure menu closes on any click
            $(document).click(function () {
                $(settings.menuSelector).hide();
            });
        });

        function getLeftLocation(e) {
            var mouseWidth = e.pageX;
            var pageWidth = $(window).width();
            var menuWidth = $(settings.menuSelector).width();

            // opening menu would pass the side of the page
            if (mouseWidth + menuWidth > pageWidth &&
                menuWidth < mouseWidth) {
                return mouseWidth - menuWidth;
            }
            return mouseWidth;
        }

        function getTopLocation(e) {
            var mouseHeight = e.pageY;
            var pageHeight = $(window).height();
            var menuHeight = $(settings.menuSelector).height();

            // opening menu would pass the bottom of the page
            if (mouseHeight + menuHeight > pageHeight &&
                menuHeight < mouseHeight) {
                return mouseHeight - menuHeight;
            }
            return mouseHeight;
        }

    };
})(jQuery, window);

jQuery(document).ready(function($){
    $("#itemList tbody tr").contextMenu({
        menuSelector: "#contextMenu",
        menuSelected: function (invokedOn, selectedMenu) {
            command=selectedMenu.attr('data-command');
            if(command=='edit_all_row')
            {
                edit_all_row(invokedOn,selectedMenu);
            }else if(command=='save_all')
            {
                save_all(invokedOn,selectedMenu);
            }

        }
    });
    function save_all(invokedOn,selectedMenu)
    {
        option= selectedMenu.attr('data-command-component');
        task= selectedMenu.attr('data-controller-task');
        cid=$('#itemList tbody').find(':input[name="cid[]"]').serializeArray();
        if(cid.length==0)
        {
            alert('please select item edit by checked in checkbox');
            return false;
        }
        if(cid.length>0)
        {
            cid='&'+$.param(cid);
        }

        title=$('#itemList tbody').find(':input.input-title').serializeArray();
        if(title.length>0)
        {
            title='&'+$.param(title);
        }

        alias=$('#itemList tbody').find(':input.input-alias').serializeArray();
        if(alias.length>0)
        {
            alias='&'+$.param(alias);
        }
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                data = {
                    option: option
                    ,task: task
                };
                return $.param(data)+cid+title+alias ;
            })(),
            beforeSend: function() {
                $('.div_loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.div_loading').css({
                    display: "none"
                });
                //sethtmlfortag(result);
            }
        });
    }

    function edit_all_row(invokedOn,selectedMenu)
    {
        selectedMenu.hide();
        $('#contextMenu .save-all').show();
        $('#itemList tbody tr').each(function(){
            item_id=$(this).attr('item-id');
            quick_title=$(this).find('td .quick-edit-title');
            html_title='<input class="input-title" name="title['+item_id+']" type="text" value="'+quick_title.text().trim()+'"/> ';
            quick_title.after(html_title);
            quick_alias=$(this).find('td .quick-edit-alias');
            html_alias='<input class="input-title" name="alias['+item_id+']" type="text" value="'+quick_alias.text().trim()+'"/> ';
            quick_alias.after(html_alias);
        });

    }
});
