//huong dan su dung
/*
 $('.field_groupparent').field_groupparent();

 field_groupparent=$('.field_groupparent').data('field_groupparent');
 console.log(field_groupparent);
 */

// jQuery Plugin for SprFlat admin field_groupparent
// Control options and basic function of field_groupparent
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_groupparent = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_groupparent
            //be sure to be same as colors on main.css or custom-variables.less
            field:{
                name:''
            },
            list_group_user:[]

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var list_group_user=plugin.settings.list_group_user;
            $element.find('.list-website').select2({

            });
            $element.find('.list-website').change(function(){
                var website_id=$(this).val();
                $element_group_user=$element.find('select[name="'+plugin.settings.field.name+'"]');
                $element_group_user.select2('destroy');
                $element_group_user.empty();
                if(website_id!=''){
                    var list_group_user=plugin.settings.list_group_user.slice();
                    var list_group_user_by_website=[];
                    for(var i=0;i<list_group_user.length;i++)
                    {
                        var group_user=list_group_user[i];
                        if(group_user.website_id==website_id)
                        {
                            list_group_user_by_website.push(group_user);
                        }
                    }


                    $element_group_user.select2({
                        data:list_group_user_by_website,
                        disabled:false
                    });


                }else{
                    $element_group_user.select2({
                        data:[],
                        disabled:true
                    });
                }
            });
            $element.find('select[name="'+plugin.settings.field.name+'"]').select2({
                data:list_group_user
            });
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_groupparent = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_groupparent')) {
                var plugin = new $.field_groupparent(this, options);

                 $(this).data('field_groupparent', plugin);

            }

        });

    }

})(jQuery);
