//huong dan su dung
/*
 $('.vtv_design').vtv_design();

 vtv_design=$('.vtv_design').data('vtv_design');
 console.log(vtv_design);
 */

// jQuery Plugin for SprFlat admin vtv_design
// Control options and basic function of vtv_design
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.vtv_design = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for vtv_design
            //be sure to be same as colors on main.css or custom-variables.less
            listBlock:{},
            menu_item_active_id:0
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init_alert_warning_website_config = function () {
            $element.find('.alert_warning_website_config').bind('click',function(){
                $.alert_warning_website_config('','',0);
            });

        };
        plugin.init_event = function () {
            $(document).bind('keypress', function(event) {
                //shift+q
                if( event.which === 81 && event.shiftKey ) {
                    plugin.auto_build_less_again();
                }
            });
            $('.reload-website').bind('click',function(){
                $.ajax({
                    type: "GET",
                    url: currentLink,
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            tmpl: 'contentwebsite',
                            screenSize: screen_size_id,
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

            $(document).on('dblclick','*[enable-double-click-edit="true"]',function(){
                $(this).attr('contenteditable',true);
                $(this).focus();
            });
            $(document).on('click', '.remove-module', function (e) {
                if (confirm('Are you sure you want remove module ?')) {
                    e.stopPropagation();
                    removeModule($(this));
                } else {
                    return;
                }


            });
            $(document).on('click', '.add-column-in-row', function () {
                if (confirm('Are you sure you want add column ?')) {
                    add_column($(this));
                    setShowControl();
                } else {
                    return;
                }



            });

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
                            enable_load_component:1,
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
            $element.find(".main-container").sortable({
                //axis: "y",
                handle: ".move-row",
                items: "> .row-content",
                stop: function (event, ui) {
                    screen_size_id = $('select[name="screen_size_id"] option:selected').val();
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
                                enable_load_component:1,
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
            $(document).on('click', '.list-add-one .add-on-item-content .remove-add-on', function (e) {
                e.stopPropagation();
                plugin.removeAddOn($(this));
            });


        };
        plugin.removeAddOn=function removeAddOn(self) {
            var addOnId = self.attr('data-add-on-id');
            if (typeof ajaxRemoveAddOn !== 'undefined') {
                ajaxRemoveAddOn.abort();
            }

            var ajaxRemoveAddOn = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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
        };
        plugin.changeLayout=function changeLayout(selected) {

            plugin.changeBackground(selected);
            plugin.showRowAvaible();
            plugin.setWidthScrollScreenSize();
            var screenSize=$('select[name="screen_size_id"]').val();
            $.ajax({
                type: "GET",
                url: this_host,
                data: (function () {


                    dataPost = {
                        enable_load_component:1,
                        option: 'com_utility',
                        task: 'utility.aJaxChangeScreenSize',
                        screenSize: screen_size_id

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
        };
        plugin.sethtmlfortag=function sethtmlfortag(respone_array) {
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
        plugin.GridStackModuleItem=function GridStackModuleItem() {
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

        plugin.changeBackground=function changeBackground(selected) {
            var screenSize = selected;
            var screenSize = screen_size_id.toString();
            selected = selected.toLowerCase();
            selected = selected.split('x');
            var screen_x= selected[0];
            if(!$('#full_height').bootstrapSwitch('state'))
            {
                $('.screen-layout').css({
                    "width": selected[0].toString() + 'px',
                    "height": selected[1].toString() + 'px',
                    "overflow-y": "scroll",
                    "overflow-x": "hidden"
                });

            }

            $('.iframelive').removeClass(screenClass);
            screenClass = "screen-" + screen_size_id.toString();
            $('.iframelive').addClass(screenClass);

            $('.iframelive').css({
                "background": "url(" + url_root + "/images/stories/" + screen_size_id + ".png) no-repeat center 0",
                "margin": "0 auto",
                //"width": screen_x + 'px'
                //,"height":height.toString()+'px'


            });
        }

        plugin.makeGridStask=function makeGridStask(currentScreenSizeEditing) {
            var thisGridstackSite = $('.main-container:eq(0) .row-content[data-screensize="' + currentScreenSizeEditing + '"]').find('.grid-stack').each(function () {
                var blockId = $(this).attr('data-block-id');
                var cell_height = $(this).attr('cell-height');
                cell_height = cell_height ? cell_height : 80;
                var  vertical_margin = $(this).attr('vertical-margin');
                vertical_margin = vertical_margin ? vertical_margin : 0;
                var amount_of_columns = $(this).attr('amount-of-columns');
                amount_of_columns = amount_of_columns ? amount_of_columns : 12;
                var currentOption = {};
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

        plugin.showRowAvaible=function showRowAvaible() {

            var screen_size_id = $('select[name="screen_size_id"] option:selected').val();
            var currentScreenSizeEditing = screen_size_id;
            var screensize = screen_size_id.toLowerCase();
            var array_data_screensizeSmartPhone = screen_size_id.split("x");
            var screenAvail = array_data_screensizeSmartPhone[0];
            $('.main-container>.row-content').each(function () {
                var data_screensize = $(this).attr('data-screensize');
                var data_screensize = data_screensize.toLowerCase();
                var array_data_screensize = data_screensize.split("x");
                var nowScreenSizeX = array_data_screensize[0];
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

        plugin.disable_widget=function disable_widget(val) {

            var grid = $('.grid-stack').data('gridstack');

            if (typeof(grid) !== 'undefined') {
                grid.resizable('.grid-stack-item', val);
                grid.movable('.grid-stack-item', val);
            }

        }

        plugin.createSortableElement=function createSortableElement(postion_content) {
            var blockId = postion_content.attr('data-block-id');
            var axis = postion_content.attr('data-axis');
            postion_content.sortable({
                axis: axis,
                handle: '.element-move-handle[data-block-parent-id="' + blockId + '"]',
                items: '.control-element[data-block-parent-id="' + blockId + '"]',
                stop: function (event, ui) {
                    blockColumnId = ui.item.attr("data-block-parent-id");
                    screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                    //screensize = screenSize.toLowerCase();
                    listElement = {};
                    $('.position-content[data-block-id="' + blockColumnId + '"] .control-element[data-block-parent-id="' + blockColumnId + '"]').each(function (index) {

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
                                enable_load_component:1,
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
        }
        plugin.createShowElementTypeWhenHoverControlItem= function createShowElementTypeWhenHoverControlItem() {
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
        };
        plugin.add_row=function add_row(self) {
            if (typeof ajaxInsertRow !== 'undefined' && ajaxInsertRow.readyState == 1) {

                //alert we are processing
                return;
            }
            ;
            var parentColumn = self.closest('.grid-stack-item');

            if (!parentColumn.hasClass('grid-stack-item')) {
                parentColumn = self.closest('.main-container');
            }
            var element_type=self.attr('element-type')
            if(typeof element_type!=="undefined" && element_type!='')
            {
                parentColumn= self.closest('.control-element');
            }
            var blockType = parentColumn.attr('data-position');
            var parentBlockId = parentColumn.attr('data-block-id');
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
            plugin.updateChangeSizeGridParent(self);
            var screen_size_id = $('select[name="screen_size_id"] option:selected').val();
            var screensize = screen_size_id.toLowerCase();

            classMove = '';
            if (parentColumn.hasClass('grid-stack-item')) {
                classMove = ' move-sub-row ';
            } else {
                classMove = ' move-row ';
            }

            newRow = $('<div class="row-content block-item show-grid-stack-item" data-screensize="' + screen_size_id + '" data-block-parent-id="' + parentBlockId + '" data-bootstrap-type="row">' +
                '<div class="item-row bootstrap-row" data-block-parent-id="' + parentBlockId + '">row</div>' +
                '<span class="drag label bottom-control-row  label-default ' + classMove + ' " data-block-parent-id="' + parentBlockId + '"><i class="glyphicon glyphicon-move"></i></span>' +
                '<a href="javascript:void(0)" class="add label label-danger bottom-control-row add-column-in-row" data-block-parent-id="' + parentBlockId + '"><i class="glyphicon glyphicon-plus"></i></a>' +
                '<a href="javascript:void(0)" class="remove bottom-control-row label label-danger remove-row" data-block-parent-id="' + parentBlockId + '"><i class="glyphicon-remove glyphicon"></i></a>' +
                ' <a href="javascript:void(0)" class="menu label bottom-control-row label-danger menu-list config-block" data-block-parent-id="' + parentBlockId + '"><i class="im-menu2"></i></a>' +
                '</div>');
            var imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
            newRow.prepend(imageLoading);
            var grid_stack_item_content = self.closest('.grid-stack-item-content');
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
            var parentColumnId = parentColumn.attr('data-block-id');
            if (typeof parentColumnId == 'undefined') {
                $(".main-container").sortable('refresh');
                parentColumnId = 0;
            }
            var ajaxInsertRow = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_utility',
                        task: 'utility.aJaxInsertRow',
                        parentColumnId: parentColumnId,
                        type: 'row',
                        screenSize: screen_size_id,
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
        plugin.remove_row=function remove_row(self) {
            var row = self.closest('.row-content');
            var imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
            row.prepend(imageLoading);
            var rowId = row.attr('data-block-id');
            var postionContent = row.closest('.position-content[data-block-id="' + row.attr("data-block-parent-id") + '"]');
            if (!postionContent.find('.row-content[data-block-parent-id="' + row.attr("data-block-parent-id") + '"]').length) {
                postionContent.sortable('destroy');
            }
            var ajaxRemoveColumn = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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
        plugin.createDroppable=function createDroppable(self) {
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

                        plugin.renderElement(uiDraggable, droppable);
                    } else if (uiDraggable.hasClass('module_item')) {
                        uiDraggable.addClass('module-draggable');

                        //uiDraggable.appendTo(droppable);
                        console.log('sdfsdfsdfd');
                        plugin.renderModule(uiDraggable, position, droppable);
                    } else if (uiDraggable.hasClass('view_item')) {
                        if ($('.panel-component').length && $(this).find('.panel-component').length == 0) {
                            alert('you can not add more component');
                            return;
                        }
                        uiDraggable.addClass('view-draggable');

                        block_id = position;
                        plugin.renderComponent(uiDraggable, block_id, droppable);
                    } else if (uiDraggable.hasClass('item-data-source-ui')) {
                        block_id = position;

                        plugin.renderAddOn(uiDraggable, block_id, droppable);
                    }
                    plugin.setShowControl();

                }
            });

        }
        plugin.renderModule=function renderModule(uiDraggable, position, droppable) {
            var module_id = uiDraggable.attr('data-module-id');
            var blockId = droppable.attr('data-block-id');
            if (typeof ajaxRederModuel !== 'undefined') {
                ajaxRederModuel.abort();
            }
            var ajaxRederModuel = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {
                    screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                    dataPost = {
                        enable_load_component:1,
                        option: 'com_modules',
                        task: 'module.aJaxInsertModule',
                        module_id: module_id,
                        screenSize: screen_size_id,
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
        plugin.renderAddOn=function renderAddOn(uiDraggable, position, droppable) {
            var addOnType = uiDraggable.attr('data-add-on-type');
            if (typeof ajaxRederDataSource !== 'undefined') {
                ajaxRederDataSource.abort();
            }
            var ajaxRederDataSource = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {
                    screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                    dataPost = {
                        enable_load_component:1,
                        option: 'com_phpmyadmin',
                        task: 'datasource.aJaxInsertDataSource',
                        addOnType: addOnType,
                        screenSize: screen_size_id,
                        position: position

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    // $('.loading').popup();
                },
                success: function (response) {
                    alert('insert datasource successful');
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
        plugin.renderComponent=function renderComponent(uiDraggable, block_id, droppable) {
            var item_view = uiDraggable.attr('data-view');
            var item_layout = uiDraggable.attr('data-layout');
            if(typeof item_layout==undefined)
            {
                item_layout='default';
            }
            var item_component = uiDraggable.attr('data-component');
            $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {
                    var screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                    var dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'item.aJaxInsertComponent',
                        item_layout: item_layout,
                        item_view: item_view,
                        item_component: item_component,
                        screenSize: screen_size_id,
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
        plugin.renderElement=function renderElement(uiDraggable, droppable) {
            var pathElement = uiDraggable.attr('data-element-path');
            if (typeof ajaxInsertElement !== 'undefined' && ajaxInsertElement.readyState == 1) {

                //alert we are processing
                return;
            }
            ;

            var parentBlockId = droppable.attr('data-block-id');
            var blockType = droppable.attr('data-position');
            if (blockType == 'position-component') {
                alert('you can not add row in block main content');
                return;
            }
            if (droppable.find('.position-content[data-block-id="' + droppable.attr("data-block-id") + '"] .module-content[data-block-id="' + droppable.attr("data-block-id") + '"]').length) {
                alert('you can not add row this block because it is block module');
                return;
            }
            var parentColumnId = droppable.attr('data-block-id');
            if (typeof parentColumnId == 'undefined') {
                $(".main-container").sortable('refresh');
                parentColumnId = 0;
            }
            var ajaxInsertElement = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_utility',
                        task: 'utility.aJaxInsertElement',
                        parentColumnId: parentColumnId,
                        screenSize: screen_size_id,
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
                    var html = $('<div>' + response.html + '</div');
                    html = $(html);
                    var listjs = html.find('script');
                    for (var i = 0; i < listjs.length; i++) {
                        script = html.find('script:eq(' + i + ')');
                        src = script.attr('src');
                        if ($(document).find('script[src="' + src + '"]').length) {
                            html.find('script:eq(' + i + ')').remove();
                        }
                    }
                    var listLink = html.find('link[rel="stylesheet"]');
                    for (var i = 0; i < listLink.length; i++) {
                        link = html.find('link[rel="stylesheet"]:eq(' + i + ')');
                        href = script.attr('href');
                        if ($(document).find('link[rel="stylesheet"][href="' + href + '"]').length) {
                            html.find('link[rel="stylesheet"]:eq(' + i + ')').remove();
                        }
                    }

                    var element_type_droppable=droppable.attr('element-type');
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
                    if(element_type_droppable=="divrow")
                    {
                        element_ui_div_row.init_div_row();
                    }


                }
            });


        }
        plugin.hideSettingPanel=function hideSettingPanel(hideSetting) {
            if (hideSetting) {
                $element.find('.panel-setting-module-item > .panel-heading').hide();
                $element.find('.panel-setting-module-item .panel-body').css({
                    border: "none"
                });
            }
            else {
                $element.find('.panel-setting-module-item > .panel-heading').show();
                $element.find('.panel-setting-module-item .panel-body').css({
                    border: "inherit"
                });
            }
        }

        plugin.setStyle=function setStyle(self) {
            self.find('> .control-element').each(function () {
                a_float = $(this);
            });
            element.find('.control-element[data-block-id="' + blockId + '"]').css({
                float: a_float,
                'width': '100%'
            });
        };
        plugin.add_block=function add_block() {
            var grid = $('.grid-stack').data('gridstack');
            var listItemStack = listPositions;

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
            screen_size_id = $('select[name="screen_size_id"] option:selected').val();
            el.attr('data-screensize', screen_size_id);

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
        plugin.remove_element=function remove_element(self) {
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
                        enable_load_component:1,
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
        plugin.removeModule=function removeModule(module) {
            module_id = module.attr('data-module-id');
            if (typeof ajaxDeleteModule !== 'undefined') {
                ajaxDeleteModule.abort();
            }

            ajaxDeleteModule = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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

        plugin.remove_column=function remove_column(self) {
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
                        enable_load_component:1,
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

        plugin.createSortableRow=function createSortableRow (postion_content) {
            var blockId = postion_content.attr('data-block-id');

            postion_content.sortable({
                //axis: "y",
                handle: '.move-sub-row[data-block-parent-id="' + blockId + '"]',
                items: '.row-content[data-block-parent-id="' + blockId + '"]',
                stop: function (event, ui) {
                    screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                    //screensize = screenSize.toLowerCase();
                    listRow = {};
                    $('.position-content[data-block-id="' + ui.item.attr('data-block-parent-id') + '"] .row-content.show-grid-stack-item:visible').each(function (index) {

                        listRow[$(this).attr('data-block-id')] = {
                            ordering: index,
                            screenSize: screen_size_id
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
                                enable_load_component:1,
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
        plugin.createSortableModule= function createSortableModule(postion_content) {
            var blockId = postion_content.attr('data-block-id');

            postion_content.sortable({
                //axis: "y",
                handle: '.module-move-sub-row[data-block-id="' + blockId + '"]',
                items: '.module-content[data-block-id="' + blockId + '"]',
                stop: function (event, ui) {
                    blockColumnId = ui.item.attr("data-block-id");
                    screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                    //screensize = screenSize.toLowerCase();
                    listModule = {};
                    $('.position-content[data-block-id="' + blockColumnId + '"] .module-content[data-block-id="' + blockColumnId + '"]').each(function (index) {

                        listModule[$(this).attr('data-module-id')] = {
                            ordering: index,
                            screenSize: screen_size_id
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
                                enable_load_component:1,
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
        plugin.createSortableAddOn=function createSortableAddOn(addOn) {
            addOn.sortable({
                axis: "x",
                //handle: '.add-on-move-sub-row',
                items: '.add-on-item-content',
                stop: function (event, ui) {
                }

            });
        }

        plugin.init_enable_double_click_edit = function () {
            $(document).on('keydown','*[enable-double-click-edit="true"]',function(e){
                var this_self=$(this);
                var block_id=$(this).attr('data-block-id');
                var field=$(this).attr('data-block-field');
                var text=this_self.html();
                switch(e.keyCode) {
                    case 13:
                        //code block
                        $.ajax({
                            type: "GET",
                            url: this_host + '/index.php',
                            data: (function () {

                                var dataPost = {
                                    enable_load_component:1,
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


        };
        plugin.reloadStylesheets= function (href) {
            var queryString = '?reload=' + new Date().getTime();
            //href=href.replace(/\?.*|$/, queryString);
            //console.log(href);
            $('link[rel="stylesheet"][href="'+this_host+href+'"]').remove();
            $('link[rel="stylesheet"][data-source="'+this_host+href+'"]').remove();
            $('head').append('<link href="'+this_host+href.replace(/\?.*|$/, queryString)+'" data-source="'+this_host+href+'" type="text/css" rel="stylesheet">');
        };
        plugin.auto_build_less_again= function () {
            $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_utility',
                        task: 'utility.ajaxBuildLess'
                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {

                    plugin.reloadStylesheets('/layouts/website/css/'+source_less);

                }
            });

        };
        plugin.element_config=function(e_taget){
            var element_config=e_taget.attr('element-config');
            var item_element_ui=e_taget.closest('.item-element.item-element-ui');
            var element_path=item_element_ui.attr('data-element-path');
            var ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        view: 'block',
                        tmpl:'ajax_json',
                        layout:'config',
                        element_path:element_path,
                        element_config:element_config

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
                    var html='';
                    if(!$('.element-config').length) {
                        html = $('<div class="panel element panel-primary element-config  panelMove toggle panelRefresh panelClose"  >' +
                            '<div class="panel-heading element-handle">' +
                            '<h4 class="panel-title">element manager</h4>' +

                            '</div>' +
                            '<div class="panel-body element"></div>' +
                            '<div class="panel-footer element-handle-footer">' +
                            '<button class="btn btn-danger save-block-property pull-right" onclick="view_config.save_and_close(self)" ><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                            '<button class="btn btn-danger apply-block-property pull-right" onclick="view_config.save(self)" ><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                            '<button class="btn btn-danger cancel-block-property pull-right" onclick="view_config.cancel(self)"><i class="fa-save"></i>Cancel</button>' +
                            '</div>'+
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.element-handle,.element-handle-footer'
                        });
                    }
                    Joomla.sethtmlfortag1(response);



                }
            });

        };
        plugin.module_config=function(e_taget){
            var type=e_taget.data('type');
            var element_path=e_taget.data('element_path');
            if(type=='config_field_module'){
                var element_path='root_module';
            }
            var id=e_taget.data('id');

            var sprFlat=$('body').data('sprFlat');
            var show_popup_control=sprFlat.settings.show_popup_control;
            if(show_popup_control)
            {
                $.open_popup_window({
                    scrollbars:1,
                    windowName:'module config field',
                    windowURL:'index.php?enable_load_component=1&option=com_modules&view=module&layout=config&id='+id+'&element_path='+element_path+'&tmpl=field&hide_panel_component=1',
                    centerBrowser:1,
                    width:'400',
                    menubar:0,
                    scrollbars:1,
                    height:'600',

                });
            }else {
                var ajax_web_design=$.ajax({
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    url: this_host+'/index.php',
                    data: (function () {

                        var dataPost = {
                            enable_load_component:1,
                            option: 'com_modules',
                            view: 'module',
                            tmpl:'ajax_json',
                            layout:'config',
                            id:id,
                            element_path:element_path

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
                        var html='';
                        if(!$('.extension-module-config').length) {
                            html = $('<div class="panel module panel-primary extension-module-config  panelMove toggle panelRefresh panelClose"  >' +
                                '<div class="panel-heading module-handle">' +
                                '<h4 class="panel-title">module manager</h4>' +

                                '</div>' +
                                '<div class="panel-body module"></div>' +
                                '<div class="panel-footer module-handle-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" onclick="view_module_config.save_and_close(self)" ><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" onclick="view_module_config.save(self)" ><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" onclick="view_module_config.cancel(self)"><i class="fa-save"></i>Cancel</button>' +
                                '</div>'+
                                '</div>'
                            );
                            $('body').prepend(html);

                            html.draggable({
                                handle: '.module-handle,.module-handle-footer'
                            });
                        }
                        Joomla.sethtmlfortag1(response);



                    }
                });

            }

        };
        plugin.load_php_content=function(self,menu_item_id) {
            var web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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
        };
        plugin.load_code_binding_source=function(self,binding_source_id) {
            var web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    var dataPost = {
                        enable_load_component:1,
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
        }
        plugin.rebuild_root_block=function(){
            if (confirm('Are you sure you want rebuid root block?')) {
                var screenSize=$('select[name="screen_size_id"]').val();
                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        var dataPost = {
                            enable_load_component:1,
                            option: 'com_utility',
                            task: 'utility.ajax_rebuild_block',
                            screenSize: screen_size_id,
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
                        if(response==1)
                        {
                            alert('rebuild sucessfull');
                            window.location.href = this_host+'?Itemid='+menuItemActiveId;
                        }

                        //reload website here
                    }
                });

            } else {
                return;
            }
        }
        plugin.rebuild_sprFlat=function(){
            var sprFlat=$('body').data('sprFlat');
            sprFlat.sideBarNav();
            sprFlat.setCurrentNav();
            console.log(sprFlat);
        };
        plugin.save_content_php_menu_item=function(self,menu_item_id,close_edit){
            var php_content=$('#php_content').val();
            php_content= base64.encode(php_content);
            var web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    var dataPost = {
                        enable_load_component:1,
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

        };
        plugin.build_grid_database_manager=function(data_source){
            $element.find('#database_detail').kendoGrid({
                dataSource:{
                    data:  Joomla.design_website.seting.list_data_source,
                    pageSize: 20
                },
                height: 550,
                columns: [
                    { field: "id", title: "Id", width: "50px" },
                    {
                        field: "title", title: "Title", width: "100px",
                        template:'<div class="add-on-item-content pull-left ui-sortable-handle" data-add-on-id="#:id#">' +
                        '<a href="javascript:void(0)" data-add-on-id="#:id#">' +
                        '<i class="br-database"></i>#:title#' +
                        '</a></div>'
                    },
                    { field: "name",title: "Name", width: "100px" },
                    { field: "introtext",title: "Description", width: "100px" },
                    {
                        field: "title", title: "Action", width: "200px",
                        template:'<a class="popup-project-relationship" href="index.php?enable_load_component=1&option=com_phpmyadmin&view=projectrelation&tmpl=field&hide_panel_component=1"><i class="en-popup">project relationship</i></a>&nbsp;&nbsp;&nbsp;&nbsp;' +
                        '<a class="popup-current-relationship" href="/index.php?enable_load_component=1&option=com_phpmyadmin&view=datasourcerelation&tmpl=field&datasource_id=#:id#&hide_panel_component=1"><i class="en-publish"></i>curent relationship</a>'
                    }
                ]
            });
            $element.find('.popup-project-relationship').popupWindow({
                scrollbars:1,
                windowName:'popup_project_relationship',
                centerBrowser:1,
                width:'1800',
                height:'1000'
            });
            $element.find('.popup-current-relationship').popupWindow({
                scrollbars:1,
                windowName:'popup_current_relationship',
                centerBrowser:1,
                width:'1200',
                height:'800'
            });


        };
        plugin.ajax_load_menu_page=function(self,_this,e)
        {
            var add_html_completed=self.data('add_html_completed');
            if(typeof add_html_completed==="undefined")
            {
                var web_design = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        var dataPost = {
                            enable_load_component:1,
                            option: 'com_menus',
                            task: 'items.ajax_load_menu_page'

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
                        self.data('add_html_completed',true);
                        Joomla.sethtmlfortag(response,'append');
                        var sprFlat=$('body').data('sprFlat');
                        sprFlat.sideBarNav();
                        //set current class on nav
                        sprFlat.setCurrentNav();
                        //toggle sidebar
                        sprFlat.toggleSidebar();
                        sprFlat.side_nav_click(_this,e);


                    }
                });
            }else{
                sprFlat.side_nav_click(_this,e);
            }

        }
        plugin.ajax_load_element=function(self,_this,e)
        {
            add_html_completed=self.data('add_html_completed');
            if(typeof add_html_completed==="undefined")
            {
                web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_utility',
                            view: 'blocks',
                            layout:'default',
                            tpl:'loadelement',
                            tmpl:'ajax_json'

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
                        self.data('add_html_completed',true);
                        Joomla.sethtmlfortag1(response,'append');
                        sprFlat=$('body').data('sprFlat');
                        sprFlat.sideBarNav();
                        //set current class on nav
                        sprFlat.setCurrentNav();
                        //toggle sidebar
                        sprFlat.toggleSidebar();
                        sprFlat.side_nav_click(_this,e);


                    }
                });
            }else{
                sprFlat.side_nav_click(_this,e);
            }

        };
        plugin.ajax_load_component=function(self,_this,e)
        {
            add_html_completed=self.data('add_html_completed');
            if(typeof add_html_completed==="undefined")
            {
                web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_components',
                            view:'components',
                            layout:'default',
                            tpl:'loadcomponent',
                            tmpl:'ajax_json'

                            //task: 'components.ajax_load_component'

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
                        self.data('add_html_completed',true);
                        Joomla.sethtmlfortag1(response,'append');
                        var sprFlat=$('body').data('sprFlat');
                        sprFlat.sideBarNav();
                        //set current class on nav
                        sprFlat.setCurrentNav();
                        //toggle sidebar
                        sprFlat.toggleSidebar();
                        sprFlat.side_nav_click(_this,e);

                        /*
                         $(".load_component .item-element").draggable({
                         appendTo: 'body',
                         helper: "clone"
                         /!* revert:true,
                         proxy:'clone'*!/
                         });
                         */

                    }
                });
            }else{
                sprFlat.side_nav_click(_this,e);
            }

        };
        plugin.ajax_load_modules=function(self,_this,e)
        {
            add_html_completed=self.data('add_html_completed');
            if(typeof add_html_completed==="undefined")
            {
                web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component: 1,
                            option: 'com_modules',
                            view: 'modules',
                            layout:'default',
                            tpl:'loadmodules',
                            tmpl:'ajax_json'

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
                        self.data('add_html_completed',true);
                        Joomla.sethtmlfortag1(response,'append');
                        var sprFlat=$('body').data('sprFlat');
                        sprFlat.sideBarNav();
                        //set current class on nav
                        sprFlat.setCurrentNav();
                        //toggle sidebar
                        sprFlat.toggleSidebar();
                        sprFlat.side_nav_click(_this,e);


                    }
                });
            }else{
                sprFlat.side_nav_click(_this,e);
            }

        };
        plugin.ajax_load_plugins=function(self,_this,e)
        {
            add_html_completed=self.data('add_html_completed');
            if(typeof add_html_completed==="undefined")
            {
                web_design = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_plugins',
                            task: 'plugins.ajax_load_plugins'

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
                        self.data('add_html_completed',true);
                        Joomla.sethtmlfortag(response,'append');
                        var sprFlat=$('body').data('sprFlat');
                        sprFlat.sideBarNav();
                        //set current class on nav
                        sprFlat.setCurrentNav();
                        //toggle sidebar
                        sprFlat.toggleSidebar();
                        sprFlat.side_nav_click(_this,e);


                    }
                });
            }else{
                sprFlat.side_nav_click(_this,e);
            }

        }
        plugin.ajax_load_datasources=function(self,_this,e)
        {
            var add_html_completed=self.data('add_html_completed');
            if(typeof add_html_completed==="undefined")
            {
                var web_design = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_phpmyadmin',
                            task: 'datasources.ajax_load_datasources'

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
                        self.data('add_html_completed',true);
                        Joomla.sethtmlfortag(response,'append');
                        var sprFlat=$('body').data('sprFlat');
                        sprFlat.sideBarNav();
                        //set current class on nav
                        sprFlat.setCurrentNav();
                        //toggle sidebar
                        sprFlat.toggleSidebar();
                        sprFlat.side_nav_click(_this,e);

                    }
                });
            }else{
                sprFlat.side_nav_click(_this,e);
            }

        };
        plugin.save_content_php_datasource=function(self,binding_source_id,close_edit){
            var php_content=$('#php_content').val();
            php_content= base64.encode(php_content);
            var web_design = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {

                    var dataPost = {
                        enable_load_component:1,
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
                            $element.find('.itemField.content_datasource_item').remove();
                        }
                    }

                }
            });

        };
        plugin.showevent=function(tag) {
            $.each($(tag).data("events"), function (i, event) {

            });
        };
        plugin.setShowControl=function() {
            $element.find('.grid-stack-item-content,.row-content,.control-element').hover(
                function () {
                    $element.find('.hover-block-item').removeClass('hover-block-item');
                    $(this).addClass('hover-block-item')
                },
                function () {
                    $(this).removeClass('hover-block-item')
                }
            );
        };
         plugin.createScrollbarPosition=function() {

            $element.find('.grid-stack-item .grid-stack-item-content').each(function () {
                $(this).mCustomScrollbar({
                    theme: "minimal-dark",
                    horizontalScroll: false,
                    mouseWheelPixels: 1000,
                    SCROLLINERTIA: "easeOutCirc"

                });
            });
        };
         plugin.treenodegridstack=function(self, key) {
            var i = 0;
            plugin.settings.listBlock[key] = {};
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
                        plugin.treenodegridstack($(this).find('.position-content:first'), key + i.toString() + '@' + j.toString());
                        j++;
                    });

                });
                i++;


            });


        };

        plugin.updateColumn = function (column) {
            var columnId = column.attr('data-block-id');
            var columnX = column.attr('data-gs-x');
            var columnY = column.attr('data-gs-y');
            var columnWidth = column.attr('data-gs-width');
            var columnHeight = column.attr('data-gs-height');
            if (typeof ajaxUpdateColumn !== 'undefined') {
                ajaxUpdateColumn.abort();
            }

            //console.log(listPositionSetting);
            var ajaxUpdateColumn = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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
        plugin.updateColumns = function (column) {
            if (typeof ajaxUpdateColumns !== 'undefined') {
                ajaxUpdateColumns.abort();
            }

            //console.log(listPositionSetting);
            var ajaxUpdateColumns = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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
        plugin.updateChangeSizeGridParent=function (self) {
            var grid_stack_item = self.closest('.grid-stack-item');

            if (grid_stack_item.length > 0 && grid_stack_item.hasClass('show-grid-stack-item')) {
                var parent_grid_stack = grid_stack_item.closest('.grid-stack').data('gridstack');
                var max_height = 0;
                for (var i = 0; i < parent_grid_stack.grid.nodes.length; i++) {
                    height = parent_grid_stack.grid.nodes[i].height;
                    if (max_height < height)
                        max_height = height;
                }
                var height = parseInt(grid_stack_item.attr('data-gs-height')) + 2;
                parent_grid_stack.resize(grid_stack_item, null, height);

                plugin.listBlock[grid_stack_item.attr('data-block-id')] = {
                    x: grid_stack_item.attr('data-gs-x'),
                    y: grid_stack_item.attr('data-gs-y'),
                    height: grid_stack_item.attr('data-gs-height'),
                    width: grid_stack_item.attr('data-gs-width'),
                    type: 'column'
                };

                if (height > max_height) {
                    self = grid_stack_item.closest('.row-content');
                    plugin.updateChangeSizeGridParent(self);
                }

            }

        }
        plugin.changeSizeGridParent = function changeSizeGridParent(grid, el, setnull) {
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
            var parentRow = el.closest('.row-content[data-block-id="' + el.attr('data-block-parent-id') + '"]');
            var rowHeigh = 0;
            var parenColumnOfParentRow = parentRow.closest('.grid-stack-item[data-block-id="' + parentRow.attr('data-block-parent-id') + '"]');
            parenColumnOfParentRow.find('.row-content[data-block-parent-id="' + parentRow.attr('data-block-parent-id') + '"]').each(function () {
                rowHeigh += $(this).outerHeight(false);
            });
            if (parenColumnOfParentRow.length > 0 && parenColumnOfParentRow.hasClass('show-grid-stack-item')) {
                var gridStackOfParenColumnOfParentRow = parenColumnOfParentRow.closest('.grid-stack[data-block-id="' + parenColumnOfParentRow.attr('data-block-parent-id') + '"]').data('gridstack');
                var cell_height = gridStackOfParenColumnOfParentRow.opts.cell_height;
                var height = rowHeigh / cell_height + 2;

                gridStackOfParenColumnOfParentRow.resize(parenColumnOfParentRow, null, height);
                plugin.changeSizeGridParent(gridStackOfParenColumnOfParentRow.grid, parenColumnOfParentRow, 0);
            }


        };
        plugin.createGridStack=function () {
            var screen_size_id = $('select[name="screen_size_id"] option:selected').val();

            var screensize = screen_size_id.toLowerCase();
            var array_data_screensizeSmartPhone = screen_size_id.split("x");
            var screenAvail = array_data_screensizeSmartPhone[0];
            $element.find('.grid-stack').each(function () {
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
            $element.find('.row-content.show-grid-stack-item').hide();
            $element.find('.row-content.show-grid-stack-item').each(function () {
                var data_screensize = $(this).attr('data-screensize');
                var data_screensize = data_screensize.toLowerCase();
                var array_data_screensize = data_screensize.split("x");
                var nowScreenSizeX = array_data_screensize[0];
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
        };
        plugin.add_column=function add_column(self) {
            if (typeof ajaxInsertColumn !== 'undefined' && ajaxInsertColumn.readyState == 1) {
                //alert we are processing
                return;
            }
            ;
            var imageLoading = $('<img width="100%" class="image-loading"  alt="loading" src="' + this_host + '/templates/sprflat/assets/img/svg/loading-bubbles.svg">');
            var item_row = self.closest('.row-content');
            var blockId = item_row.attr('data-block-id');
            var parentBlockId = item_row.attr('data-block-parent-id');
            var new_column = $('<div class="grid-stack-item  show-grid-stack-item" ></div>');
            new_column.prepend(imageLoading);
            new_column.attr('data-position', 'module-position');
            //el.attr('data-screensize',screenSize);
            new_column.addClass('show-grid-stack-item');
            new_column.find('.position-content').empty();
            //thisSubGrid = item_row.find('.grid-stack:first').data('gridstack');
            var thisSubGrid = item_row.find('.grid-stack[data-block-id="' + blockId + '"]').data('gridstack');

            if (typeof thisSubGrid == 'undefined') {
                plugin.settings.optionsGridIndex.item_class = 'grid-stack-item_' + blockId;
                var grid_stack = $('<div class="grid-stack" data-block-parent-id="' + parentBlockId + '"  data-block-id="' + blockId + '" data-grird-stack-item="' + blockId + '" data-screensize="' + currentScreenSizeEditing + '"></div>');
                plugin.settings.optionsGridIndex.handle = '.move-column[data-block-parent-id="' + blockId + '"]';
                grid_stack.gridstack(optionsGridIndex);
                grid_stack.appendTo(item_row);
                thisSubGrid = grid_stack.data('gridstack');
            }

            var childrenColumnX = 0;
            var childrenColumnWidth = 3;
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
            var childrenColumnY = new_column.attr('data-gs-y');
            var childrenColumnHeight = new_column.attr('data-gs-height');
            plugin.createDroppable(new_column.find('.grid-stack-item-content'));
            var parentRowId = item_row.attr('data-block-id');
            var ajaxInsertColumn = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    var dataPost = {
                        enable_load_component:1,
                        option: 'com_utility',
                        task: 'utility.aJaxInsertColumn',
                        type: 'column',
                        parentRowId: parentRowId,
                        childrenColumnX: childrenColumnX,
                        childrenColumnY: childrenColumnY,
                        childrenColumnWidth: childrenColumnWidth,
                        childrenColumnHeight: childrenColumnHeight,
                        screenSize: screen_size_id,
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

        plugin.init_layout = function () {
            $(".screen_size_publish").bootstrapSwitch();
            $(".screen_size_publish").on('switchChange.bootstrapSwitch', function (event, state) {
                var screen_size_id = $('select[name="screen_size_id"] option:selected').val();
                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {

                        var dataPost = {
                            enable_load_component:1,
                            option: 'com_utility',
                            task: 'utility.publish_screen_size',
                            menu_item_active_id:plugin.settings.menu_item_active_id,
                            screen_size_id: screen_size_id,
                            state:state === true ? 1 : 0

                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        // $('.loading').popup();
                    },
                    success: function (response) {
                       alert('state change ok');

                    }
                });


            });
            return;
            $element.find(".position-content,.control-element .list-row").each(function () {
                if ($(this).find('> .row-content').length > 0) {
                    createSortableRow($(this))
                } else if ($(this).find('> .module-content').length > 0) {
                    createSortableModule($(this))
                } else if ($(this).find('> .control-element').length > 0) {
                    createSortableElement($(this));
                    setStyle($(this));
                }
            });
            $element.find( 'select[name="jform[params][float]"]').change*=(function(){
                var a_float = $(this).val();
                var properties = $(this).closest('.properties');
                if (properties.hasClass('block')) {
                    var blockId = properties.find('input[name="jform[id]"]').val();

                    $('.control-element[data-block-id="' + blockId + '"]').css({
                        float: a_float,
                        'min-width': 250
                    });
                } else if (properties.hasClass('module')) {
                    var moduleId = properties.find('input[name="jform[id]"]').val();

                    $('.module-content[data-module-id="' + moduleId + '"]').css({
                        float: a_float,
                        'min-width': 250
                    });
                }
            });
            $element.find( 'select[name="jform[params][axis]"]').change(function(){
                var axis = $(this).val();
                var blockProperties = $(this).closest('.properties.block');
                var blockId = blockProperties.find('input[name="jform[id]"]').val();

                $('.position-content[data-block-id="' + blockId + '"]').sortable("option", "axis", axis);

            });
            $element.find('.add-row').change(function() {
                if (confirm('Are you sure you want add row ?')) {
                    plugin.add_row($(this));
                    plugin.setShowControl();
                } else {
                    return;
                }
            });
            $element.find('.remove-row').change(function() {
                if (confirm('Are you sure you want delete row ?')) {
                    plugin.remove_row($(this));
                } else {
                    return;
                }
            });
            $element.find('.remove-row').change(function(e, items) {
                var element = e.target;
                plugin.renderPropertyGridStackElement(element);
            });
            $element.find('.grid-stack').on('dragstart', function (event, ui) {
                var grid = this;
                var element = event.target;
                plugin.renderPropertyGridStackElement(element);

            });
            $element.find('.grid-stack').on('dragstop', function (event, ui) {
                var grid = this;
                var element = event.target;
                plugin.renderPropertyGridStackElement(element);

            });
            $element.find('.grid-stack').on('resizestart', function (event, ui) {
                var grid = this;
                var element = event.target;
                var element = event.target;
                plugin.renderPropertyGridStackElement(element);
            });
            $element.find('.grid-stack').on('resizestop', function (event, ui) {
                /*var grid = this;
                 var element = event.target;
                 if(typeof ajaxSaveColumn !== 'undefined'){
                 ajaxSaveColumn.abort();
                 }
                 console.log(grid);
                 savePosition();*/
                var element = event.target;
                plugin.renderPropertyGridStackElement(element);
            });
            $element.find('.grid-stack-item:not(.postion-header-setting)').hover(function() {
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
            $element.find( '.content-element').click(function() {
                var pathXmlElement = $(this).attr('data-xml-path');
                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {
                        dataPost = {
                            enable_load_component:1,
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





        };
        plugin.renderPropertyGridStackElement=function renderPropertyGridStackElement(self) {
            //console.log(self);
        }

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

            plugin.init_layout();

        }

        plugin.example_function = function() {

        }
        plugin.init();

    };


    // add the plugin to the jQuery.fn object
    $.fn.vtv_design = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('vtv_design')) {
                var plugin = new $.vtv_design(this, options);

                $(this).data('vtv_design', plugin);

            }

        });

    }

})(jQuery);

