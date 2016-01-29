(function ($, window) {
    $.fn.contextMenuBootrap = function (settings) {

        selfContextMenu = $(settings.menuSelector);

        settings.contextMenuUtil.copy_object_id = $.cookie('copy_object_id');

        settings.contextMenuUtil.move_object_id = $.cookie('move_object_id');

        settings.contextMenuUtil.copy_object_parent_id = $.cookie('copy_object_parent_id');
        settings.contextMenuUtil.copy_element_type = $.cookie('copy_element_type');

        settings.contextMenuUtil.move_object_parent_id = $.cookie('move_object_parent_id');
        settings.contextMenuUtil.move_element_type = $.cookie('move_element_type');

        $(this).on("contextmenu", function (e) {

            self = $(this);
            block = self.closest('.block-item');
            block_id = self.attr('data-block-id');
            if (block_id == settings.contextMenuUtil.copy_object_id || block_id == settings.contextMenuUtil.move_object_id) {
                selfContextMenu.find('li:last').addClass('disabled');
            } else {
                selfContextMenu.find('li').removeClass('disabled');
            }


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
                    e.stopPropagation();

                    var $invokedOn = $(this).data("invokedOn");
                    var $selectedMenu = $(e.target);
                    li = $selectedMenu.closest('li');
                    if (li.hasClass('disabled')) {
                        return;
                    }
                    $(settings.menuSelector).hide();
                    settings.menuSelected.call(this, block, $selectedMenu);
                });
            return false;
        });

        $(document).click(function () {
            $(settings.menuSelector).hide();
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

jQuery(document).ready(function ($) {
    var contextMenuUtil = {
        copy_object_id: 0,
        copy_object_parent_id: 0,
        move_object_id: 0,
        move_object_parent_id: 0,
        copy_element_type: '',
        move_element_type: '',
        set_value_cookie: function () {
            contextMenuUtil.copy_object_id = $.cookie('copy_object_id');
            contextMenuUtil.copy_object_parent_id = $.cookie('copy_object_parent_id');
            contextMenuUtil.copy_element_type = $.cookie('copy_element_type');
            contextMenuUtil.move_object_id = $.cookie('move_object_id');
        },
        copyElement: function (invokedOn) {
            contextMenuUtil.move_object_id = 0;
            contextMenuUtil.copy_object_id = invokedOn.attr('data-block-id');
            contextMenuUtil.copy_object_parent_id = invokedOn.attr('data-block-parent-id');
            if (!contextMenuUtil.copy_object_id) {
                alert('copy_object_id null');
            }
            contextMenuUtil.copy_element_type = invokedOn.attr('element-type');

            if (!contextMenuUtil.copy_element_type) {
                alert('copy_element_type:' + contextMenuUtil.copy_element_type);
            }
            $.cookie('copy_object_id', contextMenuUtil.copy_object_id);
            $.cookie('copy_object_parent_id', contextMenuUtil.copy_object_parent_id);
            $.cookie('copy_element_type', contextMenuUtil.copy_element_type);
            $.cookie('move_object_id', contextMenuUtil.move_object_id);
            alert('copy has save memory, please select other block and click past button');
        },
        moveElement: function (invokedOn) {
            contextMenuUtil.copy_object_id = 0;
            contextMenuUtil.move_object_id = invokedOn.attr('data-block-id');
            contextMenuUtil.move_object_parent_id = invokedOn.attr('data-block-parent-id');
            if (!contextMenuUtil.move_object_id) {
                alert('move_object_id:' + contextMenuUtil.move_object_id);
            }
            contextMenuUtil.move_element_type = invokedOn.attr('element-type');
            if (!contextMenuUtil.move_element_type) {
                alert('move_element_type:' + contextMenuUtil.move_element_type);
            }
            $.cookie('move_object_id', contextMenuUtil.move_object_id);
            $.cookie('move_object_parent_id', contextMenuUtil.move_object_parent_id);
            $.cookie('move_object_id', contextMenuUtil.move_object_id);
            $.cookie('copy_object_id', contextMenuUtil.copy_object_id);

            alert('cut has save memory, please select other block and click past button');
        },
        copyBlock: function (self) {
            var past_object_id = self.attr('data-block-id');
            var past_element_type = self.attr('element-type');

            if (confirm('Are you sure you want copy block ' + contextMenuUtil.move_element_type + ' into this ' + past_element_type + '  ?')) {
                if (typeof ajax_copy_block !== 'undefined') {
                    ajax_copy_block.abort();
                }

                ajax_copy_block = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {
                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.ajax_copy_block',
                            copy_object_id: contextMenuUtil.copy_object_id,
                            copy_element_type: contextMenuUtil.copy_element_type,
                            past_object_id: past_object_id,
                            past_element_type: past_element_type,
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
                        Joomla.design_website.rebuild_root_block();

                    }
                });
            } else {
                return;
            }
        },
        copyModule: function (from_id, to_id) {
            if (typeof ajaxCopyModule !== 'undefined') {
                ajaxCopyModule.abort();
            }

            ajaxCopyModule = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_modules',
                        task: 'module.copyModule',
                        view: 'module',
                        tmpl: 'ajax_json'
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
                    Joomla.sethtmlfortag1(response);

                }
            });
        },
        moveBlock: function (self) {
            var past_object_id = self.attr('data-block-id');
            var past_element_type = self.attr('element-type');

            if (confirm('Are you sure you want move block ' + contextMenuUtil.move_element_type + ' into this ' + past_element_type + '  ?')) {
                if (typeof ajaxMoveBlock !== 'undefined') {
                    ajaxMoveBlock.abort();
                }

                ajaxMoveBlock = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {
                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.ajaxMoveBlock',
                            move_object_id: contextMenuUtil.move_object_id,
                            move_element_type: contextMenuUtil.move_element_type,
                            past_object_id: past_object_id,
                            past_element_type: past_element_type,
                            view: 'blocks',
                            tmpl: 'ajax_json'
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
                        Joomla.sethtmlfortag1(response);
                        past_block = $('.block-item[data-block-id="' + past_object_id + '"]');
                        move_block = $('.block-item[data-block-id="' + contextMenuUtil.move_object_id + '"]');
                        past_block.append(move_block);
                        move_block.find('*[data-block-parent-id="' + contextMenuUtil.move_object_parent_id + '"]').attr('data-block-parent-id', past_object_id);
                        move_block.show('slow');

                    }
                });
            } else {
                return;
            }


        },
        moveModule: function (from_id, to_id) {
            if (typeof ajaxMoveModule !== 'undefined') {
                ajaxMoveModule.abort();
            }

            ajaxMoveModule = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_modules',
                        task: 'module.moveModule',
                        view: 'module',
                        tmpl: 'ajax_json'
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
                    Joomla.sethtmlfortag1(response);

                }
            });
        },
        moveComponent: function (from_id, to_id) {
            if (typeof ajaxMoveComponent !== 'undefined') {
                ajaxMoveComponent.abort();
            }

            ajaxMoveComponent = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_components',
                        task: 'component.moveComponent',
                        view: 'component',
                        tmpl: 'ajax_json'
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
                    Joomla.sethtmlfortag1(response);

                }
            });
        },
        pastElement: function (self) {
            contextMenuUtil.copy_object_id = $.cookie('copy_object_id');

            contextMenuUtil.move_object_id = $.cookie('move_object_id');

            contextMenuUtil.copy_object_parent_id = $.cookie('copy_object_parent_id');
            contextMenuUtil.copy_element_type = $.cookie('copy_element_type');

            contextMenuUtil.move_object_parent_id = $.cookie('move_object_parent_id');
            contextMenuUtil.move_element_type = $.cookie('move_element_type');

            var past_object_id = self.attr('data-object-id');
            var past_element_type = self.attr('element-type');
            if (past_object_id == contextMenuUtil.copy_object_id || contextMenuUtil.copy_element_type == past_element_type) {
                console.log(contextMenuUtil);
                alert('you can not set ' + contextMenuUtil.copy_element_type + ' into ' + past_element_type);
                return;
            }
            if (past_object_id == contextMenuUtil.move_object_id || contextMenuUtil.move_element_type == past_element_type) {
                alert('you can not set ' + contextMenuUtil.move_element_type + ' into ' + past_element_type);
                return;
            }
            if (contextMenuUtil.copy_object_id != 0) {
                switch (contextMenuUtil.copy_element_type) {

                    case 'module':
                        contextMenuUtil.copyModule(copy_object_id, past_object_id);
                        break;
                    case 'block':
                    default:
                        contextMenuUtil.copyBlock(self);
                        break;
                }
            } else if (contextMenuUtil.move_object_id != 0) {
                switch (contextMenuUtil.move_element_type) {

                    case 'module':
                        contextMenuUtil.moveModule(move_object_id, past_object_id);
                        break;
                    case 'component':
                        contextMenuUtil.moveComponent(move_object_id, past_object_id);
                        break;
                    case 'block':
                    default:
                        contextMenuUtil.moveBlock(self);
                        break;
                }
            }
        },
        duplicateElement: function (self) {


            if (confirm('Are you sure you want duplicate block   ?')) {
                block_id = self.attr('data-block-id');
                element_type = self.attr('element-type');
                ajaxDuplicateBlock = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {
                        dataPost = {
                            option: 'com_utility',
                            task: 'utility.ajaxDuplicateBlock',
                            view: 'blocks',
                            block_id: block_id,
                            element_type: element_type,
                            tmpl: 'ajax_json'
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
                        Joomla.design_website.rebuild_root_block();
                        //Joomla.sethtmlfortag1(response);

                    }
                });
            } else {
                return;
            }


        }
    };

    Joomla.create_context_menu = function () {
        $(".module-config,.config-block").contextMenuBootrap({
            menuSelector: "#contextMenu",
            menuSelected: function (invokedOn, selectedMenu) {
                command = selectedMenu.attr('data-command');
                switch (command) {
                    case 'copy-element':
                        contextMenuUtil.copyElement(invokedOn);
                        break;
                    case 'cut-element':
                        contextMenuUtil.moveElement(invokedOn);
                        break;
                    case 'past-element':
                        contextMenuUtil.pastElement(invokedOn);
                        break;
                    case 'duplicate-element':
                        contextMenuUtil.duplicateElement(invokedOn);
                        break;
                }

            },
            contextMenuUtil: contextMenuUtil
        });
    };
    Joomla.create_context_menu_website = function () {
        $(".iframelive").contextmenu({
            onlyTargetClass: 'iframelive',
            beforeOpen: function (event, ui) {
                event.stopPropagation();
            },
            menu: [

                {
                    title: "copy all block from other website",
                    cmd: "copy_block_from_website"
                }
            ],
            select: function (event, ui) {
                switch(ui.cmd) {
                    case 'copy_block_from_website':
                        //code block
                        var current_website= $.cookie('website');
                        var website = prompt("Please enter website",current_website);
                        if (website != null && website.trim()!='') {

                            if (confirm('Are you sure you want copy block from this website ?')) {
                                //action copy from block
                                Joomla.copy_block_from_orther_website(website);
                                $.cookie('website',website);
                            } else {
                                break;
                            }
                        } else{
                            alert('website is null');
                        }
                        break;
                    default:
                    //default code block
                }
            }
        });
    };
    Joomla.copy_block_from_orther_website=function (website){
        web_design = $.ajax({
            type: "POST",
            dataType: "json",
            url: this_host + '/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_utility',
                    task: 'blocks.ajax_copy_block_from_orther_website',
                    website: website,
                    menu_active_id: menuItemActiveId

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

                    alert(response.m);
                    Joomla.design_website.rebuild_root_block();

                }


            }
        });
    }
    //copy database
    Joomla.create_context_menu_database = function () {
        $('#footer_tab a[aria-controls="database"]').contextmenu({
            beforeOpen: function (event, ui) {
                event.stopPropagation();
            },
            menu: [

                {
                    title: "copy all database from other website",
                    cmd: "copy_database_from_website"
                }
            ],
            select: function (event, ui) {
                switch(ui.cmd) {
                    case 'copy_database_from_website':
                        //code block
                        var current_website= $.cookie('database_website');
                        var website = prompt("Please enter website",current_website);
                        if (website != null && website.trim()!='') {

                            if (confirm('Are you sure you want copy database from this website ?')) {
                                //action copy from block
                                Joomla.copy_database_from_orther_website(website);
                                $.cookie('database_website',website);
                            } else {
                                break;
                            }
                        } else{
                            alert('website is null');
                        }
                        break;
                    default:
                    //default code block
                }
            }
        });
    };
    Joomla.copy_database_from_orther_website=function (website){
        web_design = $.ajax({
            type: "POST",
            dataType: "json",
            url: this_host + '/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'datasources.ajax_copy_database_from_orther_website',
                    website: website,
                    menu_active_id: menuItemActiveId

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

                    alert(response.m);


                }


            }
        });
    }
    //end copy all database
    Joomla.create_context_menu();
    Joomla.create_context_menu_website();
    Joomla.create_context_menu_database();

});
