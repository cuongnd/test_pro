(function ($) {

    // here we go!
    $.ui_label = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:false,
            max_character:20,
            ajax_clone:false,
            block_id:0,
            float:'',
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            float=plugin.settings.float;
            ajax_clone=plugin.settings.ajax_clone;
            if(float!='')
            {
                $element.css({
                    float:float
                });
            }
            $element.click(function(e){
                block_id=$(this).attr('data-block-id');
                if (e.ctrlKey)
                {
                    plugin.add_control_element($(this));
                }else {
                    //$( '.config-block[data-block-id="'+block_id+'"]' ).trigger( "click" );
                }
            });

            $element.dblclick(function(e){
                if(plugin.settings.enable_edit_website) {
                    $element.attr('contenteditable', true);
                }
            });
            $element.keypress(function(e){
                var text=$(this).text();
                if(text.length>plugin.settings.max_character)
                {
                    e.preventDefault();
                }
               if(e.which==13) {
                   e.preventDefault();
                   if (typeof ajax_web_design !== 'undefined') {
                       ajax_web_design.abort();
                   }
                   ajax_web_design = $.ajax({
                       type: "POST",
                       dataType: "json",
                       cache: false,
                       url: this_host + '/index.php',
                       data: (function () {

                           dataPost = {
                               option: 'com_utility',
                               task: 'block.ajax_update_field_block',
                               value: text,
                               field:'params.element_config.text',
                               block_id: plugin.settings.block_id
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
                           }else{
                               alert(response.m);
                               $element.attr('contenteditable', false);
                           }

                       }
                   });

               }

            });


        }

        plugin.init_ui_label=function(){
            $('.control-element.control-element-label').each(function(){
                self=$(this);
                block_id=self.attr('data-block-id');
                block_parent_id=self.attr('data-block-parent-id');
                icon=$('label[data-block-id="'+block_id+'"][data-block-parent-id="'+block_parent_id+'"]');
                icon.insertAfter(self);
                self.hide();
            });


        }
        plugin.update_text=function(self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            data_text=properties.find('input[name="jform[params][data][text]"]');
            if(data_text.val().trim()=='')
                $('label[data-block-id="'+block_id+'"]').html(self.val());
        }
        plugin.add_control_element=function(self){
            block_id = self.attr('data-block-id');
            block_parent_id = self.attr('data-block-parent-id');
            control_element = $('.control-element.control-element-label[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]');
            enable_add_control=self.attr('enable-add-control');

            enable_add_control=(typeof enable_add_control=='undefined')?1:enable_add_control;

            if(enable_add_control=="1") {
                control_element.append(self);
                control_element.show();
                self.attr('enable-add-control', '0');
            }else{
                self.insertAfter(control_element);
                control_element.hide();
                self.attr('enable-add-control', '1');
            }

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_label = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_label')) {
                var plugin = new $.ui_label(this, options);
                $(this).data('ui_label', plugin);

            }

        });

    }

})(jQuery);





