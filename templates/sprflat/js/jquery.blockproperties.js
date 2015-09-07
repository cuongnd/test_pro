(function( $ ) {

    blockPropertiesUtil={
        getFieldTypeOfModule:function(self){
            field=self.attr('data-field');
            module_id=$('input[name="jform[id]"]').val();
            ajaxLoadFieldTypeOfModule=$.ajax({
                type: "GET",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_modules',
                        view: 'module',
                        //layout: 'module.ajaxLoadFieldTypeOfModule',
                        layout: 'field',
                        tmpl: 'ajax_json',
                        id:module_id,
                        field:field

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
                    if(!$('.itemField').length) {
                        html = $('<div class="panel itemField panel-primary field-config  panelMove toggle panelRefresh panelClose" data-module-id="'+module_id+'" data-block-property="module">' +
                            '<div class="panel-heading field-config-heading">' +
                                '<h4 class="panel-title">' + field + '</h4>' +

                            '</div>' +
                            '<div class="panel-body property"  data-module-id="'+module_id+'"></div>' +
                            '<div class="panel-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" data-module-id="'+module_id+'" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" data-module-id="'+module_id+'" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" data-module-id="'+module_id+'" type="button"><i class="fa-save"></i>Cancel</button>' +
                            '</div>'+
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.field-config-heading'
                        }).resizable({
                            aspectRatio: false,
                            handles: 'e'
                        });
                    }
                    $('.itemField .panel-title').html(field);
                    Joomla.sethtmlfortag1(response);
                    sprFlat=$('body').data('sprFlat');
                   // $('.itemField[data-module-id="'+module_id+'"] .panel-body[data-module-id="'+module_id+'"]').find('.source').ckeditor();



                }
            });

        },
        getFieldTypeOfBlock:function(self){
            field=self.attr('data-field');
            block_id=$('input[name="jform[id]"]').val();
            ajaxLoadFieldTypeOfBlock=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        //task: 'utility.ajaxLoadFieldTypeOfBlock',
                        view: 'field',
                        block_id:block_id,
                        field:field,
                        tmpl:'ajax_json',
                        layout:'properties'

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
                    if(!$('.itemField').length) {
                        html = $('<div class="panel itemField panel-primary field-config  panelMove toggle panelRefresh panelClose" data-block-id="'+block_id+'" data-block-property="block">' +
                            '<div class="panel-heading field-config-heading">' +
                                '<h4 class="panel-title">' + field + '</h4>' +

                            '</div>' +
                            '<div class="panel-body property"  data-block-id="'+block_id+'"></div>' +
                            '<div class="panel-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" data-block-id="'+block_id+'" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" data-block-id="'+block_id+'" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" data-block-id="'+block_id+'" type="button"><i class="fa-save"></i>Cancel</button>' +
                            '</div>'+
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.field-config-heading,.panel-footer'
                        });
                    }
                    $('.itemField .panel-title').html(field);
                    Joomla.sethtmlfortag1(response);



                }
            });

        },
        getFieldTypeOfDataSource:function(self){
            field=self.attr('data-field');
            add_on_id=$('input[name="jform[id]"]').val();
            ajaxLoadFieldTypeOfBlock=$.ajax({
                type: "GET",
                cache:false,
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_phpmyadmin',
                        view: 'datasource',
                        tmpl: 'ajax_json',
                        add_on_id:add_on_id,
                        field:field
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
                    if(!$('.itemField').length) {
                        html = $('<div class="panel itemField panel-primary field-config  panelMove toggle panelRefresh panelClose" data-block-id="'+add_on_id+'" data-block-property="datasource">' +
                            '<div class="panel-heading field-config-heading">' +
                                '<h4 class="panel-title">' + field + '</h4>' +

                            '</div>' +
                            '<div class="panel-body property"  data-block-id="'+add_on_id+'"></div>' +
                            '<div class="panel-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" data-block-id="'+add_on_id+'" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" data-block-id="'+add_on_id+'" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" data-block-id="'+add_on_id+'" type="button"><i class="fa-save"></i>Cancel</button>' +
                            '</div>'+
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.field-config-heading'
                        });
                    }
                    $('.itemField .panel-title').html(field);

                    Joomla.sethtmlfortag1(response);
                    sprFlat=$('body').data('sprFlat');
                    //sprFlat.panels();



                }
            });

        },
        getFieldTypeOfComponent:function(self){
            field=self.attr('data-field');
            menu_id=$('input[name="jform[id]"]').val();
            ajaxLoadFieldTypeOfBlock=$.ajax({
                type: "POST",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_menus',
                        view: 'item',
                        layout: 'field',
                        tmpl: 'ajax_json',
                        id: menu_id,
                        field: field
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
                    if(!$('.itemField').length) {
                        html = $('<div class="panel itemField panel-primary field-config  panelMove toggle panelRefresh panelClose" data-block-id="'+menu_id+'" data-block-property="component">' +
                            '<div class="panel-heading field-config-heading">' +
                                '<h4 class="panel-title">' + field + '</h4>' +

                            '</div>' +
                            '<div class="panel-body property"  data-block-id="'+menu_id+'"></div>' +
                            '<div class="panel-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" data-block-id="'+menu_id+'" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" data-block-id="'+menu_id+'" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" data-block-id="'+menu_id+'" type="button"><i class="fa-save"></i>Cancel</button>' +
                            '</div>'+
                            '</div>'
                        );
                        $('body').prepend(html);

                        html.draggable({
                            handle: '.field-config-heading'
                        });
                    }
                    $('.itemField .panel-title').html(field);
                    Joomla.sethtmlfortag1(response);
                    sprFlat=$('body').data('sprFlat');
                    sprFlat.panels();
                    var source= $('.itemField[data-module-id="'+menu_id+'"] .panel-body[data-block-id="'+menu_id+'"]').find('.source');
                    if(source.length) {
                        source.kendoEditor({});
                        var content = source.data('kendoEditor');
                    }



                }
            });

        },
        loadPropertiesModule:function(self){
            module_id=self.attr('data-module-id');
            if(typeof ajaxLoadPropertiesModule !== 'undefined'){
                ajaxLoadPropertiesModule.abort();
            }

            ajaxLoadPropertiesModule=$.ajax({
                type: "GET",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_modules',
                        view: 'module',
                        layout: 'properties',
                        tmpl: 'ajax_json',
                        module_id: module_id

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    $('.div-loading').css({
                        display: "block"


                    });
                    // $('.loading').popup();ajaxLoadPropertiesBlock
                },
                success: function (response) {
                    $('.div-loading').css({
                        display: "none"


                    });
                    Joomla.sethtmlfortag1(response);
                    $('.block-properties').attr('data-properties-type','module');
                }
            });

        },
        loadPropertiesComponent:function(self){
            module_id=self.attr('data-module-id');
            if(typeof ajaxLoadPropertiesComponent !== 'undefined'){
                ajaxLoadPropertiesComponent.abort();
            }

            ajaxLoadPropertiesComponent=$.ajax({
                type: "GET",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        view: 'item',
                        layout: 'properties',
                        tmpl: 'ajax_json',
                        menuItemActiveId:menuItemActiveId
                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    $('.div-loading').css({
                        display: "block"


                    });
                    // $('.loading').popup();ajaxLoadPropertiesBlock
                },
                success: function (response) {
                    $('.div-loading').css({
                        display: "none"


                    });
                    Joomla.sethtmlfortag1(response);
                    $('.block-properties').attr('data-properties-type','component');
                }
            });

        },
        loadPropertiesBlock:function(self){
            block_id=self.attr('data-block-id');
            if(typeof ajaxLoadPropertiesBlock !== 'undefined'){
                ajaxLoadPropertiesBlock.abort();
            }
            parser_url= $.url(currentLink).param();

            ajaxLoadPropertiesBlock=$.ajax({
                type: "GET",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        view: 'block',
                        layout: 'properties',
                        tmpl: 'ajax_json',
                        Itemid: menuItemActiveId,
                        ajaxgetcontent: 1,
                        block_id:block_id

                    };
                    dataPost=$.extend(parser_url,dataPost);
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
                    $('.block-properties').attr('data-properties-type','block');
                }
            });

        },
        loadPropertiesAddOn:function(self){
            add_on_id=self.attr('data-add-on-id');
            if(typeof ajaxLoadPropertiesAddOn !== 'undefined'){
                ajaxLoadPropertiesAddOn.abort();
            }

            ajaxLoadPropertiesAddOn=$.ajax({
                type: "GET",
                cache:false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'datasource.ajaxLoadPropertiesAddOn',
                        add_on_id:add_on_id

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
                    Joomla.sethtmlfortag(response);
                    $('.block-properties').attr('data-properties-type','datasource');
                }
            });

        },
        loadPropertiesWebsite:function(self){
            if(typeof ajaxLoadPropertiesWebsite !== 'undefined'){
                ajaxLoadPropertiesWebsite.abort();
            }

            ajaxLoadPropertiesWebsite=$.ajax({
                type: "GET",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_website',
                        view: 'website',
                        layout: 'properties',
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
                    $('.block-properties').attr('data-properties-type','website');

                }
            });

        },
        savePropertiesModule:function(properties)
        {
            console.log('hello');
            if(typeof ajaxSavePropertyModule !== 'undefined'){
                ajaxSavePropertyModule.abort();
            }
            post=properties.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyModule=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_modules&task=module.ajaxSavePropertiesModule',
                data: post,
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
                    //Joomla.sethtmlfortag(response);
                }
            });
        },
        savePropertiesBlock:function(properties)
        {
            console.log('hello savePropertiesBlock');
            if(typeof ajaxSavePropertyModule !== 'undefined'){
                ajaxSavePropertyModule.abort();
            }
            post=properties.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyModule=$.ajax({
                type: "POST",
                dataType: "json",
                url: this_host+'/index.php?option=com_utility&task=utility.ajaxSavePropertiesBlock&Itemid='+menuItemActiveId+'&tmpl=ajax_json',
                data: post,
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
        savePropertiesComponent:function(properties)
        {
            if(typeof ajaxSavePropertiesComponent !== 'undefined'){
                ajaxSavePropertiesComponent.abort();
            }
            post=properties.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertiesComponent=$.ajax({
                type: "POST",
                dataType: "json",
                url: this_host+'/index.php?option=com_menus&task=item.ajaxSavePropertiesComponent&Itemid='+menuItemActiveId+'&tmpl=ajax_json',
                data: post,
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
                    window.location.href = this_host+'?Itemid='+menuItemActiveId;
                }
            });
        },
        savePropertiesDataSource:function(properties)
        {
            if(typeof ajaxSavePropertyDataSource !== 'undefined'){
                ajaxSavePropertyDataSource.abort();
            }
            post=properties.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyDataSource=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_phpmyadmin&task=datasource.ajaxSavePropertiesDataSource&screensize='+currentScreenSizeEditing,
                data: post,
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
                    //Joomla.sethtmlfortag(response);
                }
            });
        },
        savePropertyModule:function(property,module_id,close)
        {
            console.log('hello savePropertyModule');
            if(typeof ajaxSavePropertyModule !== 'undefined'){
                ajaxSavePropertyModule.abort();
            }
            post=property.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyModule=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_modules&task=module.ajaxSavePropertyModule&module_id='+module_id,
                data: post,
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
                    panelItemField=property.closest('.itemField');
                    if(close)
                        panelItemField.remove();
                }
            });
        },
        savePropertyBlock:function(property,block_id,close)
        {
            console.log('hello savePropertyBlock');
            if(typeof ajaxSavePropertyBlock !== 'undefined'){
                ajaxSavePropertyBlock.abort();
            }
            post=property.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyBlock=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_utility&task=utility.ajaxSavePropertyBlock&block_id='+block_id,
                data: post,
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
                    alert('save success');
                    panelItemField=property.closest('.itemField');
                    panelItemField.find(':input[name*="jform"]').each(function(){
                        self=$(this);
                        name=self.attr('name');
                        $('.block-properties').find(':input[name="'+name+'"]').val(self.val());
                    });
                    if(close)
                        panelItemField.remove();
                }
            });
        },
        savePropertyComponent:function(property,menu_id,close)
        {
            console.log('hello menu_id');
            if(typeof ajaxSavePropertyBlock !== 'undefined'){
                ajaxSavePropertyBlock.abort();
            }
            post=property.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyBlock=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_menus&task=item.ajaxSavePropertyComponent&menu_id='+menu_id,
                data: post,
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
                    panelItemField=property.closest('.itemField');
                    if(close)
                        panelItemField.remove();
                }
            });
        },
        savePropertyDataSource:function(property,add_on_id,close)
        {
            if(typeof ajaxSavePropertyBlock !== 'undefined'){
                ajaxSavePropertyBlock.abort();
            }
            property.find('select,textarea, input:not([readonly])').each(function(){
                functionCallBeforeSave=$(this).attr('function-call-before-save');
                if(functionCallBeforeSave)
                {
                    window[functionCallBeforeSave]($(this));
                }
            });
            post=property.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyBlock=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_phpmyadmin&task=datasource.ajaxSavePropertydatasource&add_on_id='+add_on_id+'&screensize='+currentScreenSizeEditing,
                data: post,
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
                    response= $.parseJSON(response);
                    html_dataset=response.html_dataset;
                    data_source_id=response.data_source_id;
                    data_source_title=response.title;
                    curent_html_dataset=$('.data-set').find('.item-element[data-add-on-id="'+data_source_id+'"]');
                    if(curent_html_dataset.length)
                    {
                        curent_html_dataset.html(html_dataset);
                    }else
                    {
                        new_data_set=$('<ul class="nav sub" id="dataset_'+data_source_id+'"></ul>');
                        new_data_set.append(html_dataset);
                        $('.data-set').append(new_data_set);
                    }

                    alert('save success');
                    panelItemField=property.closest('.itemField');
                    panelItemField.find(':input[name*="jform"]').each(function(){
                        self=$(this);
                        name=self.attr('name');
                        $('.block-properties').find(':input[name="'+name+'"]').val(self.val());
                    });
                    if(close)
                        panelItemField.remove();
                }
            });
        },
        savePropertiesWebsite:function(properties){
            if(typeof ajaxSavePropertiesWebsite !== 'undefined'){
                ajaxSavePropertiesWebsite.abort();
            }
            post=properties.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertiesWebsite=$.ajax({
                type: "POST",
                url: this_host+'/index.php?option=com_website&task=website.ajaxSavePropertiesWebsite',
                data: post,
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
                    //Joomla.sethtmlfortag(response);
                }
            });
        }


    };
    var blockPropertiesEngine=function(){

    };
    blockPropertiesEngine.prototype.function1=function(){

    };

    var blockProperties = function (el, opts) {
        $('body').prepend(el);
        $(el).draggable({
            handle: '.panel-heading,.panel-footer'
        })
        .resizable({
            aspectRatio: false,
            handles: 'e,s',
            resize: function( event, ui ) {
                console.log(ui.size.height);
                $('.block-properties .properties').css({
                    height:ui.size.height-110
                });
            }
        });
        ajaxLoadPropertiesBlock=undefined;
        $(document).on('click','.module-content .module-config',function(){
            if (confirm('Are you sure you want get properties module ?')) {
                $('.module-content').removeClass('module-selected');
                $(this).addClass('module-selected');

                data_block_id=$(this).attr('data-block-id');
                data_block_prent_id=$(this).attr('data-block-parent-id');

                $('.selected-block-item-properties').removeClass('selected-block-item-properties');
                block_item=$(this).closest('.position-content.block-item[data-block-id="'+data_block_id+'"]');
                block_item.addClass('selected-block-item-properties');
                blockPropertiesUtil.loadPropertiesModule($(this));
            } else {
                return;
            }



        });
        $(document).on('click','.panel-component .panel-heading,a.page-properties',function(){
            blockPropertiesUtil.loadPropertiesComponent($(this));
        });
        $(document).on('click','.iframelive',function(e){
            if($(e.target).hasClass('iframelive'))
            {
                if (confirm('Are you sure you want get properties website ?')) {
                    blockPropertiesUtil.loadPropertiesWebsite();
                } else {
                    return;
                }

            }


        });
        $(document).on('click','.config-block',function(e){
            if (confirm('Are you sure you want get properties this block ?')) {
                data_block_id=$(this).attr('data-block-id');
                data_block_prent_id=$(this).attr('data-block-parent-id');

                $('.selected-block-item-properties').removeClass('selected-block-item-properties');
                block_item=$(this).closest('.grid-stack-item.show-grid-stack-item[data-block-id="'+data_block_id+'"]');
                block_item=block_item.length?block_item:$(this).closest('.block-item[data-block-id="'+data_block_id+'"]');
                block_item.addClass('selected-block-item-properties');
                blockPropertiesUtil.loadPropertiesBlock($(this));
            } else {
                return;
            }


        });
        $(document).on('click','.add-on-item-content',function(e){
            if (confirm('Are you sure you want get properties this add on ?')) {
                blockPropertiesUtil.loadPropertiesAddOn($(this));
            } else {
                return;
            }

        });


        $(document).on('click','.save-block-property,.apply-block-property',function(){
            close=$(this).hasClass('save-block-property');
            property=$(this).closest('.itemField').find('.panel-body.property');
            panelItemField=$(this).closest('.itemField');

            if(panelItemField.attr('data-block-property')=='website')
            {
                blockPropertiesUtil.savePropertyWebsite(property,close);
            }else if(panelItemField.attr('data-block-property')=='module')
            {
                module_id=panelItemField.attr('data-module-id');
                blockPropertiesUtil.savePropertyModule(property,module_id,close);
            }else if(panelItemField.attr('data-block-property')=='block')
            {
                block_id=panelItemField.attr('data-block-id');
                blockPropertiesUtil.savePropertyBlock(property,block_id,close);
            }else if(panelItemField.attr('data-block-property')=='component')
            {
                menu_id=panelItemField.attr('data-block-id');
                blockPropertiesUtil.savePropertyComponent(property,menu_id,close);
            }else if(panelItemField.attr('data-block-property')=='datasource')
            {
                add_on_id=panelItemField.attr('data-block-id');
                blockPropertiesUtil.savePropertyDataSource(property,add_on_id,close);
            }


        });
        $(document).on('click','.save-block-properties',function(){
            properties=$(this).closest('.block-properties');
            panelProperties=$(this).closest('.block-properties');
            if(panelProperties.attr('data-properties-type')=='website')
            {
                blockPropertiesUtil.savePropertiesWebsite(properties);
            }else if(panelProperties.attr('data-properties-type')=='module')
            {
                blockPropertiesUtil.savePropertiesModule(properties);
            } else if(panelProperties.attr('data-properties-type')=='block')
            {
                blockPropertiesUtil.savePropertiesBlock(properties);
            }else if(panelProperties.attr('data-properties-type')=='datasource')
            {
                blockPropertiesUtil.savePropertiesDataSource(properties);
            }else if(panelProperties.attr('data-properties-type')=='component')
            {
                blockPropertiesUtil.savePropertiesComponent(properties);
            }

        });
        $(document).on('click','.cancel-block-property',function(){
            itemField=$(this).closest('.itemField ');
            itemField.remove();
        });
        jQuery.extend(
            jQuery.expr[':'], {
                Contains : "jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0"
            });
        $(document).on('keyup','input[name="filter_label"]',function(){
            text=$(this).val();
            $('.properties.block .panel-body,.properties.module .panel-body,.properties.component .panel-body').find('.form-horizontal').each(function(){
                title=$(this).find('div.control-label').text();
                title=title.toLowerCase();
                if(title.indexOf(text) != -1){
                    $(this).show();
                }
                 else
                {
                    $(this).hide();
                }
            });
        });

        $(document).on('click','.save-fields-content-block',function(){
            module_id=$(this).attr('data-module-id');
            post=$('.itemField[data-module-id="'+module_id+'"] .panel-body[data-module-id="'+module_id+'"]').serialize();
            if(typeof ajaxSavePropertyBlock !== 'undefined'){
                ajaxSavePropertyBlock.abort();
            }

            ajaxSavePropertyBlock=$.ajax({
                type: "POST",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_modules',
                        task: 'module.ajaxSavePropertyBlock',
                        module_id:module_id

                    };
                    returnData= $.extend(dataPost,post);
                    return returnData;
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
                    Joomla.sethtmlfortag(response);
                }
            });
        });
        $(document).on('click','.cancel-fields-content-module',function(){
            module_id=$(this).attr('data-module-id');
            $('.itemField[data-module-id="'+module_id+'"]').remove();
        });
        $(document).on('change','.block-properties input',function(){
            console.log('hello input change');
        });
        $(document).on('click','.getFieldType',function(){

            panelProperties=$(this).closest('.block-properties');
            if(panelProperties.attr('data-properties-type')=='website')
            {
                blockPropertiesUtil.getFieldTypeOfWebsite($(this));
            }else if(panelProperties.attr('data-properties-type')=='module')
            {
                blockPropertiesUtil.getFieldTypeOfModule($(this));
            } else if(panelProperties.attr('data-properties-type')=='block')
            {
                blockPropertiesUtil.getFieldTypeOfBlock($(this));
            }else if(panelProperties.attr('data-properties-type')=='component')
            {
                blockPropertiesUtil.getFieldTypeOfComponent($(this));
            }else if(panelProperties.attr('data-properties-type')=='datasource')
            {
                blockPropertiesUtil.getFieldTypeOfDataSource($(this));
            }



        });

    };

    blockProperties.prototype.function1=function(){

    };

    jQuery.fn.blockproperties = function (opts) {
        return this.each(function () {
            if (!jQuery(this).data('blockproperties')) {
                jQuery(this).data('blockproperties', new blockProperties(this, opts));
            }
        });
    };
}( jQuery ));
jQuery(document).ready(function($){
    //end show edit tool
    $('.block-properties').blockproperties();

});
