//huong dan su dung
/*
 $('.field_usergroup').field_usergroup();

 field_usergroup=$('.field_usergroup').data('field_usergroup');
 console.log(field_usergroup);
 */

// jQuery Plugin for SprFlat admin field_usergroup
// Control options and basic function of field_usergroup
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_usergroup = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_usergroup
            //be sure to be same as colors on main.css or custom-variables.less
            field:{
                name:''
            },
            list_group_user:[],
            group_selected:[]
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
                var $element_group_user=$element.find('select[name="'+plugin.settings.field.name+'"]');
                $element_group_user.select2('destroy');
                var group_selected=plugin.settings.group_selected;
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

                    console.log(list_group_user_by_website);
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
                $element_group_user.select2("val", group_selected);
            });
            var $element_group_user=$element.find('select[name="'+plugin.settings.field.name+'"]');
            $element_group_user.select2({
                data:[]
            });
            $element_group_user.change(function(){
                var group_user=$(this).val();
                if(group_user != null && group_user.length>0)
                {
                    plugin.settings.group_selected =group_user;
                }
            });
            $element.find('.list-website').trigger('change');
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_usergroup = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_usergroup')) {
                var plugin = new $.field_usergroup(this, options);

                 $(this).data('field_usergroup', plugin);

            }

        });

    }

})(jQuery);
