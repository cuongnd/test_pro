jQuery(document).ready(function ($) {
    $(document).bind('keypress', function(event) {

        if( event.which === 81 && event.shiftKey ) {
            Joomla.design_website.auto_build_less_again();
        }
        if( event.which === 81 && event.shiftKey ) {
            Joomla.design_website.auto_build_less_again();
        }
    });
    $(document).on('dblclick','*[enable-double-click-edit="true"]',function(){
        $(this).attr('contenteditable',true);
        $(this).focus();
    });
    $('.reload-website').bind('click',function(){
        $.ajax({
            type: "GET",
            url: currentLink,
            data: (function () {

                dataPost = {
                    tmpl: 'contentwebsite',
                    screenSize: screenSize,
                    menuItemActiveId: menuItemActiveId,
                    rebuid:0
                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                var queryString = '?reload=' + new Date().getTime();
                src='/templates/sprflat/js/design.js';
                $('.screen-layout').html(response);
                $('script[type="text/javascript"][src="'+this_host+src+'"]').remove();
                $('script[type="text/javascript"][data-source="'+this_host+src+'"]').remove();
                $('head').append('<script src="'+this_host+src.replace(/\?.*|$/, queryString)+'" data-source="'+this_host+src+'" type="text/javascript" >');

            }
        });

    });
    $('.rebuild_root_block').bind('click',function(){

        Joomla.design_website.rebuild_root_block();



    });
    $(document).on('keydown','*[enable-double-click-edit="true"]',function(e){
        this_self=$(this);
        block_id=$(this).attr('data-block-id');
        field=$(this).attr('data-block-field');
        text=this_self.html();
        switch(e.keyCode) {
            case 13:
                //code block
                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.ajaxSavePropertyBlockByEnter',
                            block_id:block_id,
                            field:field,
                            text:text
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        // $('.loading').popup();
                    },
                    success: function (response) {
                        this_self.removeAttr('contenteditable');


                    }
                });

                break;
            case 12:
               // code block
                break;
            default:
            //default code block
        }
    });

    $(window).bind('keydown', function(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    $('.apply-block-property').click();
                    break;
                case 'f':
                    event.preventDefault();

                    break;
                case 'g':
                    event.preventDefault();

                    break;
            }
        }
    });


    Joomla.design_website = {
        auto_build_less_again: function () {
            $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.ajaxBuildLess'
                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {

                    Joomla.design_website.reloadStylesheets('/layouts/website/css/'+source_less);

                }
            });

        },
        /**
         * Forces a reload of all stylesheets by appending a unique query string
         * to each stylesheet URL.
         */
        reloadStylesheets: function (href) {
            var queryString = '?reload=' + new Date().getTime();
            //href=href.replace(/\?.*|$/, queryString);
            //console.log(href);
            $('link[rel="stylesheet"][href="'+this_host+href+'"]').remove();
            $('link[rel="stylesheet"][data-source="'+this_host+href+'"]').remove();
            $('head').append('<link href="'+this_host+href.replace(/\?.*|$/, queryString)+'" data-source="'+this_host+href+'" type="text/css" rel="stylesheet">');
        },
        menu_manager:function(){
            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        view: 'menus',
                        tmpl:'ajax_json',
                        layout:'ajaxloader'

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
                    if(!$('.menus-config').length) {
                        html = $('<div class="panel menu panel-primary menus-config  panelMove toggle panelRefresh panelClose"  >' +
                            '<div class="panel-heading menu-handle">' +
                            '<h4 class="panel-title">Menu manager</h4>' +

                            '</div>' +
                            '<div class="panel-body menu"></div>' +
                            '<div class="panel-footer menu-handle-footer">' +
                            '<button class="btn btn-danger save-block-property pull-right" onclick="menu_ajax_loader.save_and_close(self)" ><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                            '<button class="btn btn-danger apply-block-property pull-right" onclick="menu_ajax_loader.save(self)" ><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                            '<button class="btn btn-danger cancel-block-property pull-right" onclick="menu_ajax_loader.cancel(self)"><i class="fa-save"></i>Cancel</button>' +
                            '</div>'+
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.menu-handle,.menu-handle-footer'
                        });
                    }
                    Joomla.sethtmlfortag1(response);



                }
            });

        },
        rebuild_root_block:function(){
            if (confirm('Are you sure you want rebuid root block?')) {
                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.ajax_rebuild_block',
                            screenSize: screenSize,
                            menu_item_active_id: menuItemActiveId
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {
                        $('.div-loading').css({
                            display: "block"


                        });
                    },
                    success: function (response) {
                        window.location.href = this_host+'?Itemid='+menuItemActiveId;
                        //reload website here
                    }
                });

            } else {
                return;
            }
        } ,
        load_php_content:function(self,menu_item_id) {
            web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        view: 'item',
                        layout: 'phpcontent',
                        tmpl: 'ajax_json',
                        menu_item_id: menu_item_id

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
                    if (!$('.itemField').length) {
                        html = $('<div class="panel itemField content_menu_item panel-primary field-config  panelMove toggle panelRefresh panelClose" data-menu-id="' + menu_item_id + '" data-block-property="module">' +
                            '<div class="panel-heading field-config-heading">' +
                            '<h4 class="panel-title">file php</h4>' +

                            '</div>' +
                            '<div class="panel-body property menu"  data-menu-id="' + menu_item_id + '"></div>' +
                            '<div class="panel-footer">' +
                            '<button onClick="Joomla.design_website.save_content_php_menu_item(this,'+menu_item_id+',true)"  class="btn btn-danger  pull-right" data-menu-id="' + menu_item_id + '" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                            '<button onClick="Joomla.design_website.save_content_php_menu_item(this,'+menu_item_id+')" class="btn btn-danger pull-right" data-menu-id="' + menu_item_id + '" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                            '<button  class="btn btn-danger cancel-block-property pull-right" data-menu-id="' + menu_item_id + '" type="button"><i class="fa-save"></i>Cancel</button>' +
                            '</div>' +
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.field-config-heading,.panel-footer'
                        }).resizable({
                            aspectRatio: false,
                            handles: 'e'
                        });
                    }
                    Joomla.sethtmlfortag1(response);


                }
            });
        },
        load_code_binding_source:function(self,binding_source_id) {
            web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_phpmyadmin',
                        view: 'datasource',
                        layout: 'phpcontent',
                        tmpl: 'ajax_json',
                        binding_source_id: binding_source_id

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
                    if (!$('.itemField').length) {
                        html = $('<div class="panel itemField  panel-primary content_datasource_item field-config  panelMove toggle panelRefresh panelClose" data-menu-id="' + binding_source_id + '" data-block-property="module">' +
                            '<div class="panel-heading field-config-heading">' +
                            '<h4 class="panel-title">file php</h4>' +

                            '</div>' +
                            '<div class="panel-body property datasource"  data-datasource-id="' + binding_source_id + '"></div>' +
                            '<div class="panel-footer">' +
                            '<button onClick="Joomla.design_website.save_content_php_datasource(this,'+binding_source_id+',true)"  class="btn btn-danger  pull-right" data-menu-id="' + binding_source_id + '" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                            '<button onClick="Joomla.design_website.save_content_php_datasource(this,'+binding_source_id+')" class="btn btn-danger pull-right" data-menu-id="' + binding_source_id + '" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                            '<button  class="btn btn-danger cancel-block-property pull-right" data-datasource-id="' + binding_source_id + '" type="button"><i class="fa-save"></i>Cancel</button>' +
                            '</div>' +
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.field-config-heading,.panel-footer'
                        }).resizable({
                            aspectRatio: false,
                            handles: 'e'
                        });
                    }
                    Joomla.sethtmlfortag1(response);


                }
            });
        },
        save_content_php_menu_item:function(self,menu_item_id,close_edit){
            var php_content=$('#php_content').val();
            php_content= base64.encode(php_content);
            web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'item.ajax_save_content_php_code',
                        menu_item_id: menu_item_id,
                        php_content:php_content

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
                    if(response.e==1)
                    {
                        alert(response.m);
                    }else
                    {

                        if(typeof close_edit !="undefined" && close_edit==true)
                        {
                            $('.itemField.content_menu_item').remove();
                        }
                    }

                }
            });

        },
        save_content_php_datasource:function(self,binding_source_id,close_edit){
            var php_content=$('#php_content').val();
            php_content= base64.encode(php_content);
            web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'datasource.ajax_save_content_php_code',
                        binding_source_id: binding_source_id,
                        php_content:php_content

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
                    if(response.e==1)
                    {
                        alert(response.m);
                    }else
                    {

                        if(typeof close_edit !="undefined" && close_edit==true)
                        {
                            $('.itemField.content_datasource_item').remove();
                        }
                    }

                }
            });

        }

    };

    var sprFlat = $('body').sprFlat();


    function showevent(tag) {
        $.each($(tag).data("events"), function (i, event) {

        });
    };
    setShowControl();
    function setShowControl() {
        $('.grid-stack-item-content,.row-content,.control-element').hover(
            function () {
                $('.hover-block-item').removeClass('hover-block-item');
                $(this).addClass('hover-block-item')
            },
            function () {
                $(this).removeClass('hover-block-item')
            }
        );
    }

    function createScrollbarPosition() {

        $('.grid-stack-item .grid-stack-item-content').each(function () {
            $(this).mCustomScrollbar({
                theme: "minimal-dark",
                horizontalScroll: false,
                mouseWheelPixels: 1000,
                SCROLLINERTIA: "easeOutCirc"

            });
        });
    }

    var listBlock = {};

    function treenodegridstack(self, key) {
        var i = 0;
        listBlock[key] = {};
        self.children('.row-content').each(function () {
            listBlock[key].row = {};
            listBlock[key].row[i] = {};
            $(this).children('.grid-stack').each(function () {
                var j = 0;
                listBlock[key].row[i].column = {};
                $(this).children('.grid-stack-item.show-grid-stack-item').each(function () {
                    x = $(this).attr('data-gs-x');
                    width:$(this).attr('data-gs-width');
                    listBlock[key].row[i].column[j] = {
                        x: x,
                        width: width
                    };
                    treenodegridstack($(this).find('.position-content:first'), key + i.toString() + '@' + j.toString());
                    j++;
                });

            });
            i++;


        });


    }

    var ajaxUpdateColumn;
    updateColumn = function (column) {
        listBlockWhenResize
        columnId = column.attr('data-block-id');
        columnX = column.attr('data-gs-x');
        columnY = column.attr('data-gs-y');
        columnWidth = column.attr('data-gs-width');
        columnHeight = column.attr('data-gs-height');
        if (typeof ajaxUpdateColumn !== 'undefined') {
            ajaxUpdateColumn.abort();
        }

        //console.log(listPositionSetting);
        ajaxUpdateColumn = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxUpdateColumnInScreen',
                    columnId: columnId,
                    columnX: columnX,
                    columnY: columnY,
                    columnWidth: columnWidth,
                    columnHeight: columnHeight,
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
    };
    var ajaxUpdateColumns;
    updateColumns = function (column) {
        if (typeof ajaxUpdateColumns !== 'undefined') {
            ajaxUpdateColumns.abort();
        }

        //console.log(listPositionSetting);
        ajaxUpdateColumns = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxUpdateColumnsInScreen',
                    listColumn: listBlockWhenResize,
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
    };
    var listBlock = {};

    function updateChangeSizeGridParent(self) {
        grid_stack_item = self.closest('.grid-stack-item');

        if (grid_stack_item.length > 0 && grid_stack_item.hasClass('show-grid-stack-item')) {
            parent_grid_stack = grid_stack_item.closest('.grid-stack').data('gridstack');
            max_height = 0;
            for (var i = 0; i < parent_grid_stack.grid.nodes.length; i++) {
                height = parent_grid_stack.grid.nodes[i].height;
                if (max_height < height)
                    max_height = height;
            }
            height = parseInt(grid_stack_item.attr('data-gs-height')) + 2;
            parent_grid_stack.resize(grid_stack_item, null, height);

            listBlock[grid_stack_item.attr('data-block-id')] = {
                x: grid_stack_item.attr('data-gs-x'),
                y: grid_stack_item.attr('data-gs-y'),
                height: grid_stack_item.attr('data-gs-height'),
                width: grid_stack_item.attr('data-gs-width'),
                type: 'column'
            };

            if (height > max_height) {
                self = grid_stack_item.closest('.row-content');
                updateChangeSizeGridParent(self);
            }

        }

    }

    var listBlockWhenResize = {};
    changeSizeGridParent = function changeSizeGridParent(grid, el, setnull) {
        if (setnull == 1) {
            listBlockWhenResize = {};
        }

        $.each(grid.nodes, function (index, node) {

            if (typeof node.el.attr('data-block-id') !== 'undefined') {
                listBlockWhenResize[node.el.attr('data-block-id')] = {
                    ordering: index,
                    x: node.x,
                    y: node.x,
                    height: node.height,
                    width: node.width,
                    type: 'column'
                };
            }
        });
        parentRow = el.closest('.row-content[data-block-id="' + el.attr('data-block-parent-id') + '"]');
        rowHeigh = 0;
        parenColumnOfParentRow = parentRow.closest('.grid-stack-item[data-block-id="' + parentRow.attr('data-block-parent-id') + '"]');
        parenColumnOfParentRow.find('.row-content[data-block-parent-id="' + parentRow.attr('data-block-parent-id') + '"]').each(function () {
            rowHeigh += $(this).outerHeight(false);
        });
        if (parenColumnOfParentRow.length > 0 && parenColumnOfParentRow.hasClass('show-grid-stack-item')) {
            gridStackOfParenColumnOfParentRow = parenColumnOfParentRow.closest('.grid-stack[data-block-id="' + parenColumnOfParentRow.attr('data-block-parent-id') + '"]').data('gridstack');
            cell_height = gridStackOfParenColumnOfParentRow.opts.cell_height;
            height = rowHeigh / cell_height + 2;

            gridStackOfParenColumnOfParentRow.resize(parenColumnOfParentRow, null, height);
            changeSizeGridParent(gridStackOfParenColumnOfParentRow.grid, parenColumnOfParentRow, 0);
        }


    };

    $(document).on('click', '.remove-module', function (e) {
        if (confirm('Are you sure you want remove module ?')) {
            e.stopPropagation();
            removeModule($(this));
        } else {
            return;
        }


    });
    optionsGridIndex.updateColumns = updateColumns;
    optionsGridIndex.changeSizeGridParent = changeSizeGridParent;

    //createGridStack();
    function createGridStack() {
        screenSize = $('select[name="smart_phone"] option:selected').val();
        screensize = screenSize.toLowerCase();
        array_data_screensizeSmartPhone = screensize.split("x");
        screenAvail = array_data_screensizeSmartPhone[0];
        $('.grid-stack').each(function () {
            data_grird_stack_item = 'grid-stack-item_' + $(this).attr('data-grird-stack-item');
            $(this).find('.show-grid-stack-item').removeClass(data_grird_stack_item).hide();
            $(this).find('.show-grid-stack-item').each(function () {

                data_screensize = $(this).attr('data-screensize');
                data_screensize = data_screensize.toLowerCase();
                array_data_screensize = data_screensize.split("x");
                nowScreenSizeX = array_data_screensize[0];
                if (nowScreenSizeX == screenAvail) {
                    $(this).addClass(data_grird_stack_item).show();
                    /* data_position=$(this).attr('data-position');
                     if(data_position=='component-position')
                     {
                     hasPositionComponent=1;
                     if(!$(this).find('.position-content.component-content').hasClass('has-content')) {
                     $(this).find('.position-content.component-content').remove();
                     $(".grid-stack-item-content .component-content.has-content").appendTo($(this).find('.grid-stack-item-content'));
                     }
                     }*/
                }
            });

        });
        $('.row-content.show-grid-stack-item').hide();
        $('.row-content.show-grid-stack-item').each(function () {
            data_screensize = $(this).attr('data-screensize');
            data_screensize = data_screensize.toLowerCase();
            array_data_screensize = data_screensize.split("x");
            nowScreenSizeX = array_data_screensize[0];
            if (nowScreenSizeX == screenAvail) {
                $(this).show();
            }
        });
        /*
         if (thisGridstackSite == undefined) {
         console.log('hello thisGridstackSite');
         thisGridstackSite=$('.grid-stack').each(function(){
         grid_stack_item_id=$(this).attr('data-grird-stack-item');
         optionsGridIndex.item_class='grid-stack-item_'+grid_stack_item_id;
         $(this).gridstack(optionsGridIndex);
         });

         }
         if(hasPositionComponent==0)
         {

         var el=$('.module-item-template .grid-stack-item').clone();
         el.attr('data-position','component-position');
         el.attr('data-screensize',screenSize);
         el.addClass('show-grid-stack-item');
         el.find('.position-content').remove();
         $(".grid-stack-item-content .component-content.has-content").appendTo(el.find('.grid-stack-item-content'));
         grid = $('.grid-stack').data('gridstack');
         $('.tool-edit-style').editstyletool();
         }*/
    }

    var ajaxInsertColumn;

    function add_column(self) {
        if (typeof ajaxInsertColumn !== 'undefined' && ajaxInsertColumn.readyState == 1) {
            //alert we are processing
            return;
        }
        ;
        imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
        item_row = self.closest('.row-content');
        blockId = item_row.attr('data-block-id');
        parentBlockId = item_row.attr('data-block-parent-id');
        var new_column = $('<div class="grid-stack-item  show-grid-stack-item" ></div>');
        new_column.prepend(imageLoading);
        new_column.attr('data-position', 'module-position');
        //el.attr('data-screensize',screenSize);
        new_column.addClass('show-grid-stack-item');
        new_column.find('.position-content').empty();
        //thisSubGrid = item_row.find('.grid-stack:first').data('gridstack');
        thisSubGrid = item_row.find('.grid-stack[data-block-id="' + blockId + '"]').data('gridstack');

        if (typeof thisSubGrid == 'undefined') {
            optionsGridIndex.item_class = 'grid-stack-item_' + blockId;
            grid_stack = $('<div class="grid-stack" data-block-parent-id="' + parentBlockId + '"  data-block-id="' + blockId + '" data-grird-stack-item="' + blockId + '" data-screensize="' + currentScreenSizeEditing + '"></div>');
            optionsGridIndex.handle = '.move-column[data-block-parent-id="' + blockId + '"]';
            grid_stack.gridstack(optionsGridIndex);
            grid_stack.appendTo(item_row);
            thisSubGrid = grid_stack.data('gridstack');
        }

        childrenColumnX = 0;
        childrenColumnWidth = 3;
        new_column.addClass('grid-stack-item_' + blockId);
        new_column.attr("data-block-parent-id", blockId);
        new_column.prepend(
            '<div class="grid-stack-item-content edit-style  allow-edit-style " data-block-id="" data-block-parent-id="' + blockId + '">' +
            '<div class="item-row" data-block-parent-id="' + blockId + '" data-block-id="">col(<span class="offset-width">o:' + childrenColumnX + '-w:' + childrenColumnWidth + '</span>)</div>' +
            '<span class="drag label label-default move-column" data-block-id="" data-block-parent-id="' + blockId + '"><i class="glyphicon glyphicon-move "></i> drag</span>' +
            '<a href="javascript:void(0)" class="remove label label-danger remove-column" data-block-id="" data-block-parent-id="' + blockId + '"><i class="glyphicon-remove glyphicon"></i></a>' +
            '<a href="javascript:void(0)" class="add label label-danger add-row" data-block-id="" data-block-parent-id="' + blockId + '"><i class="glyphicon glyphicon-plus"></i></a>' +
            '<a href="javascript:void(0)" class="menu label label-danger menu-list config-block" data-block-id="" data-block-parent-id="' + blockId + '"><i class="im-menu2"></i></a>' +

            '<div class="position-content block-item" data-block-id="" data-block-parent-id="' + blockId + '"></div>' +
            '</div>'
        );
        thisSubGrid.add_widget(new_column, childrenColumnX, 0, childrenColumnWidth, 2, true);
        childrenColumnX = new_column.attr('data-gs-x');
        childrenColumnWidth = new_column.attr('data-gs-width');
        childrenColumnY = new_column.attr('data-gs-y');
        childrenColumnHeight = new_column.attr('data-gs-height');
        createDroppable(new_column.find('.grid-stack-item-content'));
        parentRowId = item_row.attr('data-block-id');
        ajaxInsertColumn = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxInsertColumn',
                    type: 'column',
                    parentRowId: parentRowId,
                    childrenColumnX: childrenColumnX,
                    childrenColumnY: childrenColumnY,
                    childrenColumnWidth: childrenColumnWidth,
                    childrenColumnHeight: childrenColumnHeight,
                    screenSize: screenSize,
                    menuItemActiveId: menuItemActiveId

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                new_column.attr('data-block-id', response.trim());
                new_column.find('*[data-block-parent-id="' + blockId + '"]').attr('data-block-id', response.trim());
                new_column.find('.image-loading').remove();
                Joomla.create_context_menu();

            }
        });
    }

    //CKEDITOR.disableAutoInline = true;
    //CKEDITOR.inline( 'editor1' );

    //$(".module-custom-html").popline({position: "fixed"});


    var ajaxSaveModuleContent;
    $(document).on('click', '.save-content-module', function () {
        module = $(this).closest('.module-content');
        module_id = module.attr('data-module-id');
        content = module.find('.module-custom-html ').html();
        content = base64.encode(content);
        if (ajaxSaveModuleContent !== null && typeof ajaxSaveModuleContent === 'object') {

            ajaxSaveModuleContent.abort();
        }
        ajaxSaveModuleContent = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_modules',
                    task: 'module.aJaxSaveContent',
                    module_id: module_id,
                    content: content

                };
                return dataPost;
            })(),
            beforeSend: function () {


                // $('.loading').popup();
            },
            success: function (response) {


            }
        });

    });
    /*var ajaxSaveModuleContent;
     $('.module-custom-html').on('contentChange', function(e) {
     var content = e.originalEvent.detail.content;
     currentTarget= e.currentTarget;
     module=$(currentTarget).closest('.module-content');
     module_id=module.attr('data-module-id');
     if(ajaxSaveModuleContent !== null && typeof ajaxSaveModuleContent === 'object'){

     ajaxSaveModuleContent.abort();
     }
     //console.log(listPositionSetting);
     ajaxSaveModuleContent=$.ajax({
     type: "GET",
     url: this_host+'/index.php',
     data: (function () {
     dataPost = {
     option: 'com_modules',
     task: 'module.aJaxSaveContent',
     module_id: module_id,
     content:content

     };
     return dataPost;
     })(),
     beforeSend: function () {


     // $('.loading').popup();
     },
     success: function (response) {



     }
     });


     });*/

    $(document).on('click', '.add-column-in-row', function () {
        if (confirm('Are you sure you want add column ?')) {
            add_column($(this));
            setShowControl();
        } else {
            return;
        }



    });
    var ajaxUpdateRows;
    $(".main-container").sortable({
        //axis: "y",
        handle: ".move-row",
        items: "> .row-content",
        stop: function (event, ui) {
            screenSize = $('select[name="smart_phone"] option:selected').val();
            //screensize = screenSize.toLowerCase();
            listRow = {};
            $('.main-container .row-content.show-grid-stack-item:visible').each(function (index) {

                listRow[$(this).attr('data-block-id')] = {
                    ordering: index
                }

            });

            if (typeof ajaxUpdateRows !== 'undefined') {
                ajaxUpdateRows.abort();
            }

            //console.log(listPositionSetting);
            ajaxUpdateRows = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxUpdateRowsInScreen',
                        listRow: listRow,
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
    });
    function createSortableRow(postion_content) {
        blockId = postion_content.attr('data-block-id');

        postion_content.sortable({
            //axis: "y",
            handle: '.move-sub-row[data-block-parent-id="' + blockId + '"]',
            items: '.row-content[data-block-parent-id="' + blockId + '"]',
            stop: function (event, ui) {
                screenSize = $('select[name="smart_phone"] option:selected').val();
                //screensize = screenSize.toLowerCase();
                listRow = {};
                $('.position-content[data-block-id="' + ui.item.attr('data-block-parent-id') + '"] .row-content.show-grid-stack-item:visible').each(function (index) {

                    listRow[$(this).attr('data-block-id')] = {
                        ordering: index,
                        screenSize: screenSize
                    }

                });

                if (typeof ajaxUpdateRows !== 'undefined') {
                    ajaxUpdateRows.abort();
                }

                //console.log(listPositionSetting);
                ajaxUpdateRows = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.aJaxUpdateRowsInScreen',
                            listRow: listRow,
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

        });
    }

    function createSortableModule(postion_content) {
        blockId = postion_content.attr('data-block-id');

        postion_content.sortable({
            //axis: "y",
            handle: '.module-move-sub-row[data-block-id="' + blockId + '"]',
            items: '.module-content[data-block-id="' + blockId + '"]',
            stop: function (event, ui) {
                blockColumnId = ui.item.attr("data-block-id");
                screenSize = $('select[name="smart_phone"] option:selected').val();
                //screensize = screenSize.toLowerCase();
                listModule = {};
                $('.position-content[data-block-id="' + blockColumnId + '"] .module-content[data-block-id="' + blockColumnId + '"]').each(function (index) {

                    listModule[$(this).attr('data-module-id')] = {
                        ordering: index,
                        screenSize: screenSize
                    }

                });

                if (typeof ajaxUpdateModule !== 'undefined') {
                    ajaxUpdateModule.abort();
                }

                ajaxUpdateModule = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_modules',
                            task: 'modules.aJaxUpdateModules',
                            listModule: listModule,
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

        });
    }

    createSortableAddOn($('.list-add-one'));
    function createSortableAddOn(addOn) {
        addOn.sortable({
            axis: "x",
            //handle: '.add-on-move-sub-row',
            items: '.add-on-item-content',
            stop: function (event, ui) {
            }

        });
    }

    $(document).on('click', '.list-add-one .add-on-item-content .remove-add-on', function (e) {
        e.stopPropagation();
        removeAddOn($(this));
    });
    function removeAddOn(self) {
        addOnId = self.attr('data-add-on-id');
        if (typeof ajaxRemoveAddOn !== 'undefined') {
            ajaxRemoveAddOn.abort();
        }

        ajaxRemoveAddOn = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'datasource.aJaxRemoveAddOn',
                    addOnId: addOnId,
                    menuItemActiveId: menuItemActiveId

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                if (response) {
                    $('.add-on-item-content[data-add-on-id="' + addOnId + '"]').remove();
                    $('.item-data-source-ui[data-add-on-id="' + addOnId + '"]').remove();
                }


            }
        });
    }

    function createSortableElement(postion_content) {
        blockId = postion_content.attr('data-block-id');
        axis = postion_content.attr('data-axis');
        postion_content.sortable({
            axis: axis,
            handle: '.element-move-handle[data-block-parent-id="' + blockId + '"]',
            items: '.control-element[data-block-parent-id="' + blockId + '"]',
            stop: function (event, ui) {
                blockColumnId = ui.item.attr("data-block-parent-id");
                screenSize = $('select[name="smart_phone"] option:selected').val();
                //screensize = screenSize.toLowerCase();
                listElement = {};
                $('.position-content[data-block-id="' + blockColumnId + '"] .control-element[data-block-parent-id="' + blockColumnId + '"]').each(function (index) {

                    listElement[$(this).attr('data-block-id')] = {
                        ordering: index,
                        screenSize: screenSize
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

        });
    };
    createShowElementTypeWhenHoverControlItem();
    function createShowElementTypeWhenHoverControlItem() {
        $('.module-config,.config-block').each(function () {
            $(this).popover({
                html: true,
                placement: 'top',
                title: function () {
                    block_id = $(this).attr('data-block-id');
                    block_parent_id = $(this).attr('data-block-parent-id');
                    return $('.block-item[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]').attr('element-type') + '(' + block_id + ')';
                },
                trigger: 'hover ',
                container: 'body',
                content: function () {
                    return 'you can right click to copy,cut element, left click go to properties this element';
                }
            });
        });
    }

    $(".position-content,.control-element .list-row").each(function () {
        if ($(this).find('> .row-content').length > 0) {
            createSortableRow($(this))
        } else if ($(this).find('> .module-content').length > 0) {
            createSortableModule($(this))
        } else if ($(this).find('> .control-element').length > 0) {
            createSortableElement($(this));
            setStyle($(this));
        }
    });
    function setStyle(self) {
        self.find('> .control-element').each(function () {
            a_float = $(this);
        });
        $('.control-element[data-block-id="' + blockId + '"]').css({
            float: a_float,
            'min-width': 250
        });
    }

    $(document).on('change', 'select[name="jform[params][float]"]', function () {
        a_float = $(this).val();
        properties = $(this).closest('.properties');
        if (properties.hasClass('block')) {
            blockId = properties.find('input[name="jform[id]"]').val();

            $('.control-element[data-block-id="' + blockId + '"]').css({
                float: a_float,
                'min-width': 250
            });
        } else if (properties.hasClass('module')) {
            moduleId = properties.find('input[name="jform[id]"]').val();

            $('.module-content[data-module-id="' + moduleId + '"]').css({
                float: a_float,
                'min-width': 250
            });
        }
    });
    $(document).on('change', 'select[name="jform[params][axis]"]', function () {
        axis = $(this).val();
        blockProperties = $(this).closest('.properties.block');
        blockId = blockProperties.find('input[name="jform[id]"]').val();

        $('.position-content[data-block-id="' + blockId + '"]').sortable("option", "axis", axis);

    });

    var ajaxInsertRow;

    function add_row(self) {
        if (typeof ajaxInsertRow !== 'undefined' && ajaxInsertRow.readyState == 1) {

            //alert we are processing
            return;
        }
        ;
        parentColumn = self.closest('.grid-stack-item');

        if (!parentColumn.hasClass('grid-stack-item')) {
            parentColumn = self.closest('.main-container');
        }
        element_type=self.attr('element-type')
        if(typeof element_type!=="undefined" && element_type!='')
        {
            parentColumn= self.closest('.control-element');
        }
        blockType = parentColumn.attr('data-position');
        parentBlockId = parentColumn.attr('data-block-id');
        if (blockType == 'position-component') {
            alert('you can not add row in block main content');
            return;
        }
        if (parentColumn.find('.position-content[data-block-id="' + parentColumn.attr("data-block-id") + '"] .module-content[data-block-id="' + parentColumn.attr("data-block-id") + '"]').length) {
            alert('you can not add row this block because it is block module');
            return;
        }

        if (parentColumn.find('.position-content[data-block-id="' + parentColumn.attr("data-block-id") + '"] > .control-element').length) {
            alert('you can not add row this block because it is block element');
            return;
        }
        listBlock = {};
        updateChangeSizeGridParent(self);
        screenSize = $('select[name="smart_phone"] option:selected').val();
        screensize = screenSize.toLowerCase();

        classMove = '';
        if (parentColumn.hasClass('grid-stack-item')) {
            classMove = ' move-sub-row ';
        } else {
            classMove = ' move-row ';
        }

        newRow = $('<div class="row-content block-item show-grid-stack-item" data-screensize="' + screensize + '" data-block-parent-id="' + parentBlockId + '" data-bootstrap-type="row">' +
        '<div class="item-row bootstrap-row" data-block-parent-id="' + parentBlockId + '">row</div>' +
        '<span class="drag label bottom-control-row  label-default ' + classMove + ' " data-block-parent-id="' + parentBlockId + '"><i class="glyphicon glyphicon-move"></i></span>' +
        '<a href="javascript:void(0)" class="add label label-danger bottom-control-row add-column-in-row" data-block-parent-id="' + parentBlockId + '"><i class="glyphicon glyphicon-plus"></i></a>' +
        '<a href="javascript:void(0)" class="remove bottom-control-row label label-danger remove-row" data-block-parent-id="' + parentBlockId + '"><i class="glyphicon-remove glyphicon"></i></a>' +
        ' <a href="javascript:void(0)" class="menu label bottom-control-row label-danger menu-list config-block" data-block-parent-id="' + parentBlockId + '"><i class="im-menu2"></i></a>' +
        '</div>');
        imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
        newRow.prepend(imageLoading);
        grid_stack_item_content = self.closest('.grid-stack-item-content');
        if (grid_stack_item_content.length > 0) {
            grid_stack_item_content.find('.position-content:first').append(newRow);
        }
        else {
            $('.main-container').append(newRow);

            //grid_stack.appendTo();
        }
        if (!parentColumn.find('.position-content[data-block-id="' + parentColumn.attr("data-block-id") + '"]').hasClass('ui-sortable')) {
            createSortableRow(parentColumn.find('.position-content[data-block-id="' + parentColumn.attr("data-block-id") + '"]'));

        } else {
            parentColumn.find('.position-content[data-block-id="' + parentColumn.attr("data-block-id") + '"]').sortable('refresh');
        }
        parentColumnId = parentColumn.attr('data-block-id');
        if (typeof parentColumnId == 'undefined') {
            $(".main-container").sortable('refresh');
            parentColumnId = 0;
        }
        ajaxInsertRow = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxInsertRow',
                    parentColumnId: parentColumnId,
                    type: 'row',
                    screenSize: screenSize,
                    listBlock: listBlock,
                    menuItemActiveId: menuItemActiveId

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                newRow.attr('data-block-id', response.trim());
                newRow.find('[data-block-parent-id="' + parentBlockId + '"]').attr('data-block-id', response.trim());
                newRow.find('.image-loading').remove();
                Joomla.create_context_menu();


            }
        });


    }


    function remove_row(self) {
        row = self.closest('.row-content');
        imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
        row.prepend(imageLoading);
        rowId = row.attr('data-block-id');
        postionContent = row.closest('.position-content[data-block-id="' + row.attr("data-block-parent-id") + '"]');
        if (!postionContent.find('.row-content[data-block-parent-id="' + row.attr("data-block-parent-id") + '"]').length) {
            postionContent.sortable('destroy');
        }
        ajaxRemoveColumn = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxRemoveRow',
                    rowId: rowId

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                row.remove();

            }
        });
    }

    $(document).on('click', '.add-row', function () {
        if (confirm('Are you sure you want add row ?')) {
            add_row($(this));
            setShowControl();
        } else {
            return;
        }


    });
    $(document).on('click', '.remove-row', function () {

        if (confirm('Are you sure you want delete row ?')) {
            remove_row($(this));
        } else {
            return;
        }



    });

    $('.grid-stack').on('change', function (e, items) {
        var element = e.target;
        renderPropertyGridStackElement(element);
    });


    // ondragstart(event, ui)


    $('.grid-stack').on('dragstart', function (event, ui) {
        var grid = this;
        var element = event.target;
        renderPropertyGridStackElement(element);

    });
    function renderPropertyGridStackElement(self) {
        //console.log(self);
    }

    // ondragstop(event, ui)


    $('.grid-stack').on('dragstop', function (event, ui) {
        var grid = this;
        var element = event.target;
        renderPropertyGridStackElement(element);

    });


    // onresizestart(event, ui)


    $('.grid-stack').on('resizestart', function (event, ui) {
        var grid = this;
        var element = event.target;
        var element = event.target;
        renderPropertyGridStackElement(element);
    });


    // onresizestop(event, ui)

    $('.grid-stack').on('resizestop', function (event, ui) {
        /*var grid = this;
         var element = event.target;
         if(typeof ajaxSaveColumn !== 'undefined'){
         ajaxSaveColumn.abort();
         }
         console.log(grid);
         savePosition();*/
        var element = event.target;
        renderPropertyGridStackElement(element);
    });

    $(document).on('hover', '.grid-stack-item:not(.postion-header-setting)', function () {

        if (hiden_position_setting) {
            return;
        }

        position = $(this).position();

        data_gs_x = $(this).attr('data-gs-x');
        data_gs_width = $(this).attr('data-gs-width');

        position_data_gs_x = $(this).attr('data-gs-x');

        module_setting_height = $('.module-setting').outerHeight();

        position_data_gs_width = $(this).attr('data-gs-width');


        $('.module-setting').attr('data-gs-x', data_gs_x);
        $('.module-setting').attr('data-gs-width', data_gs_width);
        $('.module-setting').attr('data-position', $(this).attr('data-position'));

        position_top = position.top - module_setting_height;
        if (position_top < 0) {
            position_top = 0;
        }
        $('.module-setting').css({
            top: position_top,
            left: position.left,
            display: "block",
            width: $(this).width(),
            position: "absolute"
        });


    });


    $(".item-element").draggable({
        appendTo: 'body',
        helper: "clone"
        /* revert:true,
         proxy:'clone'*/
    });
    createDroppable($(".grid-stack-item .grid-stack-item-content"));
    createDroppable($(".enable-create-drop-element"));

    function createDroppable(self) {
        self.droppable({
            accept: ".item-element",
            greedy: true,
            drop: function (ev, ui) {


                grid_stack_item = $(this).closest('.grid-stack-item');
                position = grid_stack_item.attr('data-block-id');
                uiDraggable = $(ui.draggable);
                droppable = $(this).find('.position-content');
                if ($(this).hasClass('enable-create-drop-element')) {
                    droppable = $(this);
                }
                if (droppable.find('.row-content[data-block-parent-id="' + droppable.attr("data-block-id") + '"]').length) {
                    alert('you can not add module or component or element because it has some row');
                    return;
                }
                /*if(droppable.find(' > .control-element').length)
                 {
                 alert('you can not add module or component or module because it has some element');
                 return;
                 }*/

                if (uiDraggable.hasClass('item-element-ui')) {
                    //check drag divrow to divrow
                    block_id = droppable.attr('data-block-id');
                    elementType = uiDraggable.attr('data-element-type');
                    if (elementType == 'divrow' && droppable.hasClass('div-row') && droppable.find('>.div-column[data-block-parent-id="' + block_id + '"]').length) {
                        alert('you can not add row and column type  in one row !');
                        return;
                    }
                    uiDraggable.addClass('element-draggable');

                    renderElement(uiDraggable, droppable);
                } else if (uiDraggable.hasClass('module_item')) {
                    uiDraggable.addClass('module-draggable');

                    //uiDraggable.appendTo(droppable);
                    renderModule(uiDraggable, position, droppable);
                } else if (uiDraggable.hasClass('view_item')) {
                    if ($('.panel-component').length && $(this).find('.panel-component').length == 0) {
                        alert('you can not add more component');
                        return;
                    }
                    uiDraggable.addClass('view-draggable');

                    block_id = position;
                    renderComponent(uiDraggable, block_id, droppable);
                } else if (uiDraggable.hasClass('item-data-source-ui')) {
                    block_id = position;

                    renderAddOn(uiDraggable, block_id, droppable);
                }
                setShowControl();

            }
        });

    }

    var ajaxRederModuel;

    function renderModule(uiDraggable, position, droppable) {
        module_id = uiDraggable.attr('data-module-id');
        blockId = droppable.attr('data-block-id');
        if (typeof ajaxRederModuel !== 'undefined') {
            ajaxRederModuel.abort();
        }
        ajaxRederModuel = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                screenSize = $('select[name="smart_phone"] option:selected').val();
                dataPost = {
                    option: 'com_modules',
                    task: 'module.aJaxInsertModule',
                    module_id: module_id,
                    screenSize: screenSize,
                    position: position

                };
                return dataPost;
            })(),
            beforeSend: function () {
                // $('.loading').popup();
            },
            success: function (response) {
                respons = $.parseJSON(response);
                module = $(respons.modulecontent);

                droppable.append(module);
                //uiDraggable.remove();

                resizeGrid(droppable.closest('.grid-stack-item'));
                if ($('.position-content[data-block-id="' + blockId + '"]').hasClass('ui-sortable')) {

                    $('.position-content[data-block-id="' + blockId + '"]').sortable('refresh');
                }
                else {
                    createSortableModule($('.position-content[data-block-id="' + blockId + '"]'));
                }


            }
        });

    }

    var ajaxRederDataSource;

    function renderAddOn(uiDraggable, position, droppable) {
        addOnType = uiDraggable.attr('data-add-on-type');
        if (typeof ajaxRederDataSource !== 'undefined') {
            ajaxRederDataSource.abort();
        }
        ajaxRederDataSource = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                screenSize = $('select[name="smart_phone"] option:selected').val();
                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'datasource.aJaxInsertDataSource',
                    addOnType: addOnType,
                    screenSize: screenSize,
                    position: position

                };
                return dataPost;
            })(),
            beforeSend: function () {
                // $('.loading').popup();
            },
            success: function (response) {
                respons = $.parseJSON(response);
                addOn = $(respons.addOnContent);
                $('.list-add-one').append(addOn);
                if ($('.list-add-one').hasClass('ui-sortable')) {

                    $('.list-add-one').sortable('refresh');
                }
                else {
                    createSortableAddOn($('.list-add-one'));
                }


            }
        });

    }

    function renderComponent(uiDraggable, block_id, droppable) {
        item_view = uiDraggable.attr('data-view');

        item_component = uiDraggable.attr('data-component');
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                screenSize = $('select[name="smart_phone"] option:selected').val();
                dataPost = {
                    option: 'com_menus',
                    task: 'item.aJaxInsertComponent',
                    item_view: item_view,
                    item_component: item_component,
                    screenSize: screenSize,
                    menuItemActiveId: menuItemActiveId,
                    tmpl: "contentcomponent",
                    block_id: block_id

                };
                return dataPost;
            })(),
            beforeSend: function () {
                // $('.loading').popup();
            },
            success: function (response) {
                respons = $.parseJSON(response);
                droppable.html(respons.componentContent);
                resizeGrid(droppable.closest('.grid-stack-item'));


            }
        });

    }

    function renderElement(uiDraggable, droppable) {
        pathElement = uiDraggable.attr('data-element-path');
        if (typeof ajaxInsertElement !== 'undefined' && ajaxInsertElement.readyState == 1) {

            //alert we are processing
            return;
        }
        ;

        parentBlockId = droppable.attr('data-block-id');
        blockType = droppable.attr('data-position');
        if (blockType == 'position-component') {
            alert('you can not add row in block main content');
            return;
        }
        if (droppable.find('.position-content[data-block-id="' + droppable.attr("data-block-id") + '"] .module-content[data-block-id="' + droppable.attr("data-block-id") + '"]').length) {
            alert('you can not add row this block because it is block module');
            return;
        }
        parentColumnId = droppable.attr('data-block-id');
        if (typeof parentColumnId == 'undefined') {
            $(".main-container").sortable('refresh');
            parentColumnId = 0;
        }
        ajaxInsertElement = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxInsertElement',
                    parentColumnId: parentColumnId,
                    screenSize: screenSize,
                    addSubRow: 0,
                    ajaxgetcontent: 1,
                    menuItemActiveId: menuItemActiveId,
                    pathElement: pathElement

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                response = $.parseJSON(response);
                html = $('<div>' + response.html + '</div');
                html = $(html);
                listjs = html.find('script');
                for (var i = 0; i < listjs.length; i++) {
                    script = html.find('script:eq(' + i + ')');
                    src = script.attr('src');
                    if ($(document).find('script[src="' + src + '"]').length) {
                        html.find('script:eq(' + i + ')').remove();
                    }
                }
                listLink = html.find('link[rel="stylesheet"]');
                for (var i = 0; i < listLink.length; i++) {
                    link = html.find('link[rel="stylesheet"]:eq(' + i + ')');
                    href = script.attr('href');
                    if ($(document).find('link[rel="stylesheet"][href="' + href + '"]').length) {
                        html.find('link[rel="stylesheet"]:eq(' + i + ')').remove();
                    }
                }

                element_type_droppable=droppable.attr('element-type');
                if(element_type_droppable=="divrow")
                {
                    element_div_column=$(html.html());
                    div_row_grid_stack=droppable.data('gridstackDivRow');
                    if(typeof div_row_grid_stack=="undefined")
                    {
                        element_ui_div_row.create_grid_stack(droppable);
                        div_row_grid_stack=droppable.data('gridstackDivRow');
                    }
                    div_row_grid_stack.add_widget(element_div_column, 0, 0, 3, 2, true);
                }else
                {
                    droppable.append(html.html());
                }

                if ($('.position-content[data-block-id="' + parentBlockId + '"]').hasClass('ui-sortable')) {

                    $('.position-content[data-block-id="' + parentBlockId + '"]').sortable('refresh');
                }
                else {
                    createSortableElement($('.position-content[data-block-id="' + parentBlockId + '"]'));
                }
                createDroppable($('.enable-create-drop-element'));
                element_ui_div_row.init_div_row();


            }
        });


    }

    function reloadPage() {

    }

    $(document).on('click', '.content-element', function () {
        pathXmlElement = $(this).attr('data-xml-path');
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxGetXmlElement',
                    id: 1630

                };
                return dataPost;
            })(),
            beforeSend: function () {
                // $('.loading').popup();
            },
            success: function (response) {

            }
        });

    });

    function resizeGrid(gridItem) {
        /*     height = gridItem.find('.grid-stack-item-content .position-content').height();

         height = height / 80 + 2;
         var grid = $('.grid-stack').data('gridstack');
         grid.resize(gridItem, null, height);*/
    }

    $('#hide_module_item_setting').on('switchChange.bootstrapSwitch', function (event, state) {

        hideSettingPanel(state);

    });

    function hideSettingPanel(hideSetting) {
        if (hideSetting) {
            $('.panel-setting-module-item > .panel-heading').hide();
            $('.panel-setting-module-item .panel-body').css({
                border: "none"
            });
        }
        else {
            $('.panel-setting-module-item > .panel-heading').show();
            $('.panel-setting-module-item .panel-body').css({
                border: "inherit"
            });
        }
    }

    hideSetting = $('input[name="hide_setting"]').is(':checked');
    hideSettingPanel(hideSetting);
    function add_block() {
        var grid = $('.grid-stack').data('gridstack');
        listItemStack = listPositions;

        $('.grid-stack-item:not(.postion-header-setting)').each(function () {
            position = $(this).attr('data-position');
            $.each(listItemStack, function (key, value) {
                if (value == position) {
                    listItemStack.splice(key, 1);
                }
            });
        });
        if (listItemStack.length == 0) {
            $("#dialog_add_widget").dialog(
                {
                    modal: true
                }
            );
            //return;
        }
        var el = $('.module-item-template .grid-stack-item').clone();
        position = listItemStack[0];
        el.attr('data-position', position);
        screenSize = $('select[name="smart_phone"] option:selected').val();
        el.attr('data-screensize', screenSize);

        grid.add_widget(el, 0, 0, 3, 2, true);
        $('.tool-edit-style').editstyletool();
        disableEditWidget = !$('.disable_widget').is(':checked');
        disable_widget(disableEditWidget);
        //createScrollbarPosition();
        hideSetting = $('input[name="hide_setting"]').is(':checked');
        hideSettingPanel(hideSetting);

        hideSettingModuleItem = $('input[name="hide_module_item_setting"]').is(':checked');
        hideSettingPanelModule(hideSettingModuleItem);
        if (typeof ajaxSaveColumn !== 'undefined') {
            ajaxSaveColumn.abort();
        }
        saveColumn();
    }

    $(document).on('click', '.remove-element', function () {
        if (confirm('Are you sure you want remove element ?')) {
            remove_element($(this));
        } else {
            return;
        }


    });
    var ajaxRemoveElement;

    function remove_element(self) {
        controlElement = self.closest('.control-element');
        imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
        controlElement.prepend(imageLoading);
        block_id = self.attr('data-block-id');
        if (typeof ajaxRemoveElement !== 'undefined') {
            ajaxRemoveElement.abort();
        }
        ajaxRemoveElement = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxRemoveElement',
                    block_id: block_id

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                controlElement.remove();

            }
        });

    }


    $(document).on('click', '.remove-column', function () {
        if (confirm('Are you sure you want remove column ?')) {
            remove_column($(this));
        } else {
            return;
        }


    });
    function remove_column(self) {
        grid_stack_item = self.closest('.grid-stack-item');
        imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
        grid_stack_item.prepend(imageLoading);
        grid_stack = grid_stack_item.closest('.grid-stack').data('gridstack');
        columnId = grid_stack_item.attr('data-block-id');
        ajaxRemoveColumn = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxRemoveColumn',
                    columnId: columnId

                };
                return dataPost;
            })(),
            beforeSend: function () {

                // $('.loading').popup();
            },
            success: function (response) {
                grid_stack.remove_widget(grid_stack_item);

            }
        });

    }

    $(document).on('click', '.add_widget', function () {

        add_block()

    });

    $(document).on('click', '#save-position', function (index) {
        if (typeof ajaxSaveColumn !== 'undefined') {

            ajaxSaveColumn.abort();
        }
        saveColumn();


    });

    $(document).on('change', '.disable_widget', function () {

        val = !$(this).is(':checked');

        disable_widget(val);
    });
    $(document).on('change', 'input[name="editing"]', function () {

        val = !$(this).is(':checked');

        hideSettingPanel(val);
    });
    $('#disable_widget').on('switchChange.bootstrapSwitch', function (event, state) {

        disable_widget(!state);

    });
    var hiden_position_setting = true;
    $('#hide_setting').on('switchChange.bootstrapSwitch', function (event, state) {

        hiden_position_setting = state;
        if (hiden_position_setting) {
            $('.module-setting').css({
                display: "none"
            });
        }
    });


    $(".scroll-div-screen-size").scroll(function () {
        $(".panel-screen-size .panel-body")
            .scrollLeft($(".scroll-div-screen-size").scrollLeft());
    });
    $(".panel-screen-size .panel-body").scroll(function () {
        $(".scroll-div-screen-size")
            .scrollLeft($(".panel-screen-size .panel-body").scrollLeft());
    });

    $(document).on('scroll', '.scroll-div-screen-size', function (event) {

    });
    var ajaxDeleteModule;

    function removeModule(module) {
        module_id = module.attr('data-module-id');
        if (typeof ajaxDeleteModule !== 'undefined') {
            ajaxDeleteModule.abort();
        }

        ajaxDeleteModule = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_modules',
                    task: 'module.AjaxRemoveModule',
                    module_id: module_id

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
                $('.module-content[data-module-id="' + module_id + '"]').remove();


            }
        });


    }

    var isLoadedModuleStyle = 0;

    $(document).on('click', '.apanel-setting-module-item .panel-setting', function () {
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_website',
                    task: 'modulestyle.AjaxGetModuleStyle',
                    isLoadedModuleStyle: isLoadedModuleStyle

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
                sethtmlfortag(response);
                $("#dialog_show_view").dialog({
                    width: 300,
                    modal: true,
                    buttons: {

                        close: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).hide();
                    }
                });
                isLoadedModuleStyle = 1;
            }
        });


    });
    $('#disable_border_module').on('switchChange.bootstrapSwitch', function (event, state) {

        if (state) {
            $('.grid-stack-item .grid-stack-item-content, .grid-stack-item .placeholder-content').css({
                "border": "1px solid " + $('#colorpickerFieldSelect').val(),
                "margin": "-1px"
            });


        } else {
            $('.grid-stack-item .grid-stack-item-content, .grid-stack-item .placeholder-content').css({
                "border": "none",
                "margin": 0
            });

        }
    });

    $('#disable_widget').on('switchChange.bootstrapSwitch', function (event, state) {

        disable_widget(!state);

    });

    var heightScreenSize = 0;
    $('#full_height').on('switchChange.bootstrapSwitch', function (event, state) {
        screenSize = $('select[name="smart_phone"] option:selected').val();
        screenSize = screenSize.toString();
        screenSize = screenSize.toLowerCase();
        screenSize = screenSize.split('x');
        heightScreenSize = screenSize[1];
        if (state) {
            $('.screen-layout').css({
                "height": "auto",
                "overflow-y": "hidden"
            });
        } else {
            $('.screen-layout').css({
                "height": heightScreenSize,
                "overflow-y": "scroll"
            });
        }
    });
    //disableEditWidget=$('.disable_widget').is(':checked');

    //disable_widget(disableEditWidget);
    function disable_widget(val) {

        var grid = $('.grid-stack').data('gridstack');

        if (typeof(grid) !== 'undefined') {
            grid.resizable('.grid-stack-item', val);
            grid.movable('.grid-stack-item', val);
        }

    }

    $(document).on('dblclick', '.custom.module-custom-html', function () {

        js_ckfinder = '/ckfinder/ckfinder.js';
        if (!$('script[src="' + this_host + js_ckfinder + '"]').length) {
            $('head').append('<script src="' + this_host + js_ckfinder + '" type="text/javascript"></script>');
        }

        js_ckeditor = '/media/editors/ckeditor/ckeditor.js';
        if (!$('script[src="' + this_host + js_ckeditor + '"]').length) {
            $('head').append('<script src="' + this_host + js_ckeditor + '" type="text/javascript"></script>');
        }

        js_adapters_jquery = '/media/editors/ckeditor/adapters/jquery.js';
        if (!$('script[src="' + this_host + js_adapters_jquery + '"]').length) {
            $('head').append('<script src="' + this_host + js_adapters_jquery + '" type="text/javascript"></script>');
        }
        $(this).attr('contenteditable', true);
        $(this).ckeditor();

    });

    var listScreenSize = {
        '480X320': {
            "width": 300,
            "height": 60
        },
        '800X480': {
            "width": 130,
            "height": 75
        },
        '854X480': {
            "width": 165,
            "height": 80
        },
        '960X640': {
            "width": 320,
            "height": 195
        },
        '1136X640': {
            "width": 390,
            "height": 195
        },
        '1280X768': {
            "width": 440,
            "height": 230
        },
        '1920X1080': {
            "width": 165,
            "height": 800
        }
    };
    var screenClass = "";
    $(document).on('change', '.smart-phone', function () {
        selected = $(this).val();
        changeLayout(selected);

    });
    var thisGridstackSite = undefined;
    showRowAvaible();
    function showRowAvaible() {

        screenSize = $('select[name="smart_phone"] option:selected').val();
        currentScreenSizeEditing = screenSize;
        screensize = screenSize.toLowerCase();
        array_data_screensizeSmartPhone = screensize.split("x");
        screenAvail = array_data_screensizeSmartPhone[0];
        $('.main-container>.row-content').each(function () {
            data_screensize = $(this).attr('data-screensize');
            data_screensize = data_screensize.toLowerCase();
            array_data_screensize = data_screensize.split("x");
            nowScreenSizeX = array_data_screensize[0];
            //console.log('nowScreenSizeX:'+nowScreenSizeX+'-screenAvail:'+screenAvail);
            if (nowScreenSizeX != screenAvail) {
                $(this).hide();
            } else {
                $(this).show();
            }

        });
        console.log(currentScreenSizeEditing);
        if (thisGridstackSite == undefined) {
            makeGridStask(currentScreenSizeEditing)

        }

    }

    function makeGridStask(currentScreenSizeEditing) {
        thisGridstackSite = $('.main-container:eq(0) .row-content[data-screensize="' + currentScreenSizeEditing + '"]').find('.grid-stack').each(function () {
            blockId = $(this).attr('data-block-id');
            cell_height = $(this).attr('cell-height');
            cell_height = cell_height ? cell_height : 80;
            vertical_margin = $(this).attr('vertical-margin');
            vertical_margin = vertical_margin ? vertical_margin : 0;
            amount_of_columns = $(this).attr('amount-of-columns');
            amount_of_columns = amount_of_columns ? amount_of_columns : 12;
            currentOption = {};
            currentOption.cell_height = cell_height;
            currentOption.destroy_resizable = 1;
            currentOption.width = amount_of_columns;
            currentOption.vertical_margin = vertical_margin;
            currentOption.item_class = 'grid-stack-item_' + blockId;
            currentOption.handle = '.move-column[data-block-parent-id="' + blockId + '"]';
            currentOption = $.extend({}, currentOption, optionsGridIndex);
            $(this).gridstack(currentOption);
        });
    }

    function changeLayout(selected) {

        changeBackground(selected);
        showRowAvaible();
        setWidthScrollScreenSize();

        $.ajax({
            type: "GET",
            url: this_host,
            data: (function () {


                dataPost = {
                    option: 'com_utility',
                    task: 'utility.aJaxChangeScreenSize',
                    screenSize: screenSize

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


            }
        });
    }


    changeBackground(currentScreenSizeEditing);
    function changeBackground(selected) {
        screenSize = selected;
        screenSize = screenSize.toString();
        selected = selected.toLowerCase();
        selected = selected.split('x');


        $('.screen-layout').css({
            "width": selected[0].toString() + 'px',
            "height": selected[1].toString() + 'px',
            "overflow-y": "scroll",
            "overflow-x": "hidden"
        });
        /*$(".screen-layout").mCustomScrollbar({
         theme:"light",
         set_width:false,
         mouseWheelPixels: 100,
         scrollinertia:"easeOutCirc"

         });*/

        width = parseInt(selected[0]) + parseInt(listScreenSize[screenSize].width);
        height = parseInt(selected[1]) + parseInt(listScreenSize[screenSize].height);
        $('.iframelive').removeClass(screenClass);
        screenClass = "screen-" + screenSize.toString();
        $('.iframelive').addClass(screenClass);

        $('.iframelive').css({
            "background": "url(" + url_root + "/images/stories/" + screenSize + ".png) no-repeat center 0",
            "margin": "0 auto",
            "width": width.toString() + 'px'
            //,"height":height.toString()+'px'


        });
    }

    $(document).on('click', '.calculator', function () {

        max_data_gs_x = 0;
        total_data_gs_width = 0;

        max_data_gs_y = 0;
        total_data_gs_height = 0;

        $('.grid-stack-item.ui-draggable').each(function (index) {
            data_gs_x = $(this).attr('data-gs-x');
            data_gs_width = $(this).attr('data-gs-width');
            data_gs_x = parseInt(data_gs_x);

            data_gs_width = parseInt(data_gs_width);
            total_data_gs_width += data_gs_width;
            if (max_data_gs_x < data_gs_x) {
                max_data_gs_x = data_gs_x;
            }

            data_gs_y = $(this).attr('data-gs-y');
            data_gs_height = $(this).attr('data-gs-height');
            data_gs_y = parseInt(data_gs_y);
            data_gs_height = parseInt(data_gs_height);
            total_data_gs_height += data_gs_height;
            if (max_data_gs_y < data_gs_y) {
                max_data_gs_y = data_gs_y;
            }
        });


    });
    $(document).on('click', '.turn_off_preview', function () {
        enable_edit_website = 0;
        $('<form>', {
            "id": "form_enable_edit_website",
            "html": '<input type="text" id="enable_edit_website" name="enable_edit_website" value="' + enable_edit_website + '" />' +
            '<input type="text" id="editing_state" name="editing_state" value="0" />',
            "action": document.URL,
            "method": 'post'

        }).appendTo(document.body).submit();
    });
    $(document).on('click', '.change_margin_widget', function () {
        html = '<p>' +
        '<label for="amount">Margin:</label>' +
        '<input type="text" class="margin_widget" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">' +
        '</p>' +
        '<div><div id="slider-range-min"></div></div>';
        $('.dialog_show_view_body').html(html);
        $("#dialog_show_view").dialog({
            width: 800,
            buttons: {
                Yes: function () {
                    // $(obj).removeAttr('onclick');
                    // $(obj).parents('.Parent').remove();

                    $(this).dialog("close");
                },
                No: function () {
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).hide();
            }
        });


        $("#slider-range-min").ionRangeSlider({
            min: 0,
            max: 10,
            from: 0,
            onChange: function (data) {
                fromNumber = data.fromNumber;
                $('.grid-stack .grid-stack-item .grid-stack-item-content').css({
                    "padding": fromNumber.toString() + 'px'
                });
            }
        });


    });

    $(document).on('click', '.change_background', function () {
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_website',
                    task: 'background.ChangeBackground'

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
                sethtmlfortag(response);
                $("#dialog_show_view").dialog({
                    width: 800,
                    buttons: {
                        Yes: function () {
                            // $(obj).removeAttr('onclick');
                            // $(obj).parents('.Parent').remove();
                            SaveBackGround();
                            $(this).dialog("close");
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).hide();
                    }
                });

            }
        });

    });
    function sethtmlfortag(respone_array) {
        if (respone_array !== null && typeof respone_array !== 'object')
            respone_array = $.parseJSON(respone_array);
        $.each(respone_array, function (index, respone) {
            if (typeof(respone.type) !== 'undefined') {
                $(respone.key.toString()).val(respone.contents);
            } else {
                $(respone.key.toString()).html(respone.contents);
            }
        });
    }

    $(window).bind('resize', function (e) {
        window.resizeEvt;
        $(window).resize(function () {
            clearTimeout(window.resizeEvt);
            if ($(e.target).hasClass('grid-stack-item')) {
                return;
            }
            if (!$('input[name="editing"]').is(':checked')) {
                return;
            }
            if (enableEditWebsite == 1)
                return;
            // Javascript URL redirection

            window.resizeEvt = setTimeout(function () {
                //code to do after window is resized

                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {
                        var w = window,
                            d = document,
                            e = d.documentElement,
                            g = d.getElementsByTagName('body')[0],
                            x = w.innerWidth || e.clientWidth || g.clientWidth,
                            y = w.innerHeight || e.clientHeight || g.clientHeight;

                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.aJaxChangeScreenSize',
                            screenSize: x.toString() + "X" + y.toString()

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


                    }
                });

            }, 250);
        });
    });

    $(document).on('click', '.btn-module-setting', function () {

        $("#dialog").dialog(
            {
                modal: true,
                resizable: true,
                open: function (event, ui) {
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {
                            dataPost = {
                                option: 'com_modules',
                                task: 'module.aJaxGetOptionModule',
                                id: 10

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

                            sethtmlfortag(response);
                        }
                    });

                }

            }
        );

    });
    function GridStackModuleItem() {
        var optionsModule = {
            cell_height: 40,
            vertical_margin: 10,
            item_class: 'module-grid-stack-item',
            placeholder_class: 'module-grid-stack-placeholder',
            handle: 'a.btn-module-move'

        };
        //$('.panel.panel-setting-module .panel-body').gridstack(optionsModule);
        /*$('.panel-setting-module > .panel-body').sortable({
         placeholder: "panel-heading",
         axis: "y"
         });*/


    }

    //$('.dd').nestable({ /* config options */ });
    $(document).on('change', 'input[name="hide_module_item_setting"]', function () {
        hideSettingModuleItem = $(this).is(':checked');
        hideSettingPanelModule(hideSettingModuleItem);
    });
    hideSettingModuleItem = $('input[name="hide_module_item_setting"]').is(':checked');
    hideSettingPanelModule(hideSettingModuleItem);
    function hideSettingPanelModule(hideSetting) {
        if (hideSetting) {
            $('.panel-setting-module-item > .panel-heading').hide();
        }
        else {
            $('.panel-setting-module-item > .panel-heading').show();
        }
    }

    $(document).on('click', '.btn-module-remove', function () {
        var grid = $('.grid-stack').data('gridstack');
        module_setting_item = $(this).closest('.module-setting');
        data_position = module_setting_item.attr('data-position');
        grid_stack_item = $('.grid-stack-item[data-position="' + data_position + '"]');
        module_setting_item.hide();

        grid.remove_widget(grid_stack_item);
        if (typeof ajaxSaveColumn !== 'undefined') {
            ajaxSaveColumn.abort();
        }
        saveColumn();


    });
    $(document).on('click', '.position-remove', function () {
        data_position = $('.postion-header-setting').attr('data-position');
        var grid = $('.grid-stack').data('gridstack');
        grid_stack_item = $('.grid-stack').find('.grid-stack-item[data-position="' + data_position + '"]:not(.postion-header-setting)');

        grid.remove_widget(grid_stack_item);


    });
    /* $('.screen-layout .grid-stack-item').each(function(){
     el=$(this);
     height=$(this).find('.grid-stack-item-content .position-content').height();
     height=height/80+2;
     var grid = $('.grid-stack').data('gridstack');
     grid.resize(el, null,height);

     });*/

    $('#joyRideTipContent').joyride({
        autoStart: true,
        postStepCallback: function (index, tip) {

            if (index == 2) {

                $(this).joyride('set_li', false, 1);
            }
        },
        modal: true,
        expose: true
    });
    setWidthScrollScreenSize();
    function setWidthScrollScreenSize() {
        screen_layout_width = $('.iframelive').outerWidth();
        //screen_layout_width=1280;

        $('.scroll-div-screen-size .scroll-div1').width(screen_layout_width);
    }

});