//huong dan su dung
/*
 $('.view_categories_manager').view_categories_manager();

 view_categories_manager=$('.view_categories_manager').data('view_categories_manager');
 console.log(view_categories_manager);
 */

// jQuery Plugin for SprFlat admin view_categories_manager
// Control options and basic function of view_categories_manager
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_categories_manager = function(element, options) {

        // plugin's default options
        var defaults = {
            items:'',
            total_row:30
            //main color scheme for view_categories_manager
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.fill_data = function (page) {
            var items=JSON.parse(JSON.stringify(plugin.settings.items));
            var total_row=plugin.settings.total_row;
            items=$.array_chunk(items,total_row);
            items=items[page-1];

            if(typeof items=="undefined" || items.length==0)
            {
                var $tr=$('<tr class="nothing"><td colspan="9">there no item</td></tr>');
                $('table.adminlist tbody tr').css({
                    display:'none'
                });
                $tr.appendTo($('table.adminlist tbody'));
                return false;
            }else{
                $('table.adminlist tbody tr.nothing').remove();
            }
            $('table.adminlist tbody tr').css({
                display:'none'
            });
            $.each(items,function(index,item){
                var tr=$('table.adminlist tbody tr:eq('+index+')');
                $.each(item,function(key,value){
                    tr.find('*[area-key="'+key+'"]').html(value);
                });
                tr.css({
                    display:'table-row'
                });
            });

        };
        plugin.set_pagination = function (items) {
            var total_row=plugin.settings.total_row;
            var totalPages=Math.round(items.length/total_row);
            totalPages=totalPages>0?totalPages:1;
            $element.find('#pagination').twbsPagination({
                totalPages: totalPages,
                visiblePages: 7,
                onPageClick: function (event, page) {
                    plugin.fill_data(page);
                }
            });
        };
        plugin.filter_data = function () {
            var virtuemart_category_id=$element.find('.filter-category').val();
            if(virtuemart_category_id!=''&& $.isNumeric(virtuemart_category_id)&&virtuemart_category_id>0) {
                var items = JSON.parse(JSON.stringify(plugin.settings.store));
                var return_list_category = [];
                plugin.create_tree_category_list(return_list_category, virtuemart_category_id, items, 0);
                plugin.settings.items = return_list_category;
            }else{
                plugin.settings.items=JSON.parse(JSON.stringify(plugin.settings.store));
            }
            var items=plugin.settings.items;
            var twbsPagination = $element.find('#pagination').data('twbsPagination');
            twbsPagination.destroy();
            plugin.set_pagination(items);
            plugin.fill_data(1);
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var items=plugin.settings.items;
            items=base64.decode(items);
            items= $.parseJSON(items);
            plugin.settings.items= items;
            plugin.settings.store= items;
            plugin.set_pagination(items);

            $element.find('.filter-category').change(function(){
                plugin.filter_data();
            });
        }

        plugin.create_tree_category_list=function(return_list_category,category_parent_id,items,level){

            var  list_category1 = [];
            if(items.length==0)
                return false;
            $.each(items,function(index,item){
                if(typeof item!=="undefined" && item.category_parent_id==category_parent_id){
                    list_category1.push(item);
                    delete items[index];
                }
            });
            list_category1.sort(function(category1,category2){
                if(category1.ordering==category2.ordering) return false;
                return category1.ordering<category2.ordering?false:true;
            });
            var level1 = level + 1;
            $.each(list_category1,function(index,item){
                item.tree_category=(level1>1?$.str_repeat('--',level1):'')+item.category_name;
                return_list_category.push(item);
                plugin.create_tree_category_list(return_list_category,item.virtuemart_category_id,items,level1);
            });
        };
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_categories_manager = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_categories_manager')) {
                var plugin = new $.view_categories_manager(this, options);

                $(this).data('view_categories_manager', plugin);

            }

        });

    }

})(jQuery);
