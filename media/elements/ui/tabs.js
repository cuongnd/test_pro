// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_tabs = function (element, options) {




        // plugin's default options
        var defaults = {
            enableEditWebsite:false,
            block_id:0,
            tabs_option:{

            }
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var element_id=$element.attr('id');
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            enableEditWebsite=plugin.settings.enableEditWebsite;
            $div_wapper=$('<div class="tabs_wapper"></div>');
            $element.children().each(function(){
                $(this).appendTo($div_wapper);
            });
            $div_wapper.appendTo($element);



            $ui=$('<ul></ul>');

            $div_wapper.children().each(function(){
                var tab_title=$(this).attr('data-tab-title');
                $li=$('<li><a>'+tab_title+'</a></li>');
                $li.appendTo($ui);
            });
            $ui.prependTo($element);




            tabs_option=plugin.settings.tabs_option;
            plugin.tabbedNav = $element.zozoTabs({
                    position: "top-left",
                    theme: "silver",
                    size: "large",
                    defaultTab: "tab2"
                }),
                getItem = function () {
                    //uncomment if you want to delete current/active tab
                    //return $(".z-tabs > ul > li.z-active").index()+1;
                    return $("#tabIndex").val();
                },
                select = function (e) {
                    tabbedNav.data("zozoTabs").select(getItem());
                },
                add = function (e) {
                    tabbedNav.data("zozoTabs").add($("#addText").val(), "New Tab Content ...<br>", "test");
                },
                remove = function (e) {
                    tabbedNav.data("zozoTabs").remove(getItem());
                },
                disable = function (e) {
                    /*disable tab via geven index*/
                    tabbedNav.data("zozoTabs").disable(getItem());
                },
                enable = function (e) {
                    /*enable tab via geven index*/
                    tabbedNav.data("zozoTabs").enable(getItem());
                },
                next = function (e) {
                    tabbedNav.data("zozoTabs").next();
                },
                prev = function (e) {
                    tabbedNav.data("zozoTabs").prev();
                },
                first = function (e) {
                    tabbedNav.data("zozoTabs").first();
                },
                last = function (e) {
                    tabbedNav.data("zozoTabs").last();
                },
                play = function (e) {
                    tabbedNav.data("zozoTabs").play();
                },
                stop = function (e) {
                    tabbedNav.data("zozoTabs").stop();
                };





        }
        plugin.add_tab=function(){
            block_id=plugin.settings.block_id;
            //tab.html("<div style='background:red'>sadasdasdasdasdasdasdasd</div>")
            ajaxInsertElement=$.ajax({
                type: "GET",
                url: this_host+'/index.php',

                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertElement',
                        parentColumnId:block_id,
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

                    $element.find('.nav-tabs').append(li);
                    $element.find('.tab-content').append(html);
                    html.find('.row-content[data-block-parent-id="'+block_id+'"]').css({
                        display:"block"
                    });
                    html.find('.grid-stack[data-block-parent-id="'+block_id+'"]').gridstackDivRow(optionsGridIndex);

                }
            });
        }

        plugin.remove_tab=function(self){
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

        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_tabs = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_tabs')) {
                var plugin = new $.ui_tabs(this, options);
                $(this).data('ui_tabs', plugin);

            }

        });

    }

})(jQuery);




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
        //self button delete
    };
    //$('.tab_ui .remove-tab-content').click(function(){
    $(document).delegate(".tab_ui .remove-tab-content","click",function(e){
        elementuitab.remove_tab($(this));
    });


});