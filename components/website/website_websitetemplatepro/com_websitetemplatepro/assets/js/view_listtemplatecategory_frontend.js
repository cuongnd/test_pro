//huong dan su dung
/*
 $('.view_listtemplatecategory_frontend').view_listtemplatecategory_frontend();

 view_listtemplatecategory_frontend=$('.view_listtemplatecategory_frontend').data('view_listtemplatecategory_frontend');
 console.log(view_listtemplatecategory_frontend);
 */

// jQuery Plugin for SprFlat admin view_listtemplatecategory_frontend
// Control options and basic function of view_listtemplatecategory_frontend
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_listtemplatecategory_frontend = function(element, options) {

        // plugin's default options
        var defaults = {
            totalPages:300,
            category_id:0
            //main color scheme for view_listtemplatecategory_frontend
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.set_menu = function () {
            $element.find('.u-vmenu').vmenuModule({
                Speed: 200,
                autostart: false,
                autohide: true
            });
        };
        plugin.load_website_template_by_category=function(category_id,count_error_ajax,page_selected) {
            if(category_id=='')
            {
                return false;
            }
            var option_click = {
                option: 'com_websitetemplatepro',
                task: 'listtemplatecategory.ajax_get_website_template_by_category',

            };
            option_click = $.param(option_click);
            var data_submit = {};
            data_submit.category_id =category_id;
            data_submit.page_selected =page_selected;
            var ajax_web_design = $.ajax({
                contentType: 'application/json',
                type: "POST",
                url: this_host + '/index.php?' + option_click,
                data: JSON.stringify(data_submit),
                beforeSend: function () {
                    $('.div-loading').css({
                        display: "block"


                    });
                },
                success: function (respone_array) {
                    $('.div-loading').css({
                        display: "none"


                    });
                    Joomla.sethtmlfortag(respone_array);
                    plugin.reset_pagination(1);

                },
                error: function (request, status, err) {
                    if (status == "timeout") {
                        // timeout -> reload the page and try again
                        console.log("timeout");
                        plugin.load_website_template_by_category(category_id,count_error_ajax,page_selected);
                    } else {
                        if (count_error_ajax > 10) {
                            console.log('too many error ajax');
                        } else {
                            // another error occured
                            count_error_ajax++;
                            plugin.load_website_template_by_category(category_id,count_error_ajax,page_selected);
                        }
                    }
                }

            });
        };

        plugin.load_website_template_by_category_by_click = function () {
            $element.find('.u-vmenu a').click(function(){
                var category_id =$(this).data('category_id');
                plugin.load_website_template_by_category(category_id,0,1);
            });
        };
        plugin.reset_pagination = function (page) {
            var total_page=$element.find('.area-list-template #pagination').data('total_page');
            plugin.settings.totalPages=total_page;
            plugin.init_pagination();
        };
        plugin.select_page_template = function (page_selected) {
            var category_id=$element.find('.area-list-template #pagination').data('category_id');
            plugin.settings.category_id=category_id;
            console.log(category_id);
            plugin.load_website_template_by_category(category_id,0,page_selected);
        };
        plugin.init_pagination = function () {
            var total_page=$element.find('.area-list-template #pagination').data('total_page');
            var page_selected=$element.find('.area-list-template #pagination').data('page_selected');
            plugin.settings.totalPages=total_page;
            $element.find('.area-list-template #pagination').twbsPagination({
                totalPages: total_page,
                visiblePages: 7,
                startPage: page_selected,
                initiateStartPageClick:false,
                onPageClick: function (event, page_selected) {
                    plugin.select_page_template(page_selected);
                }
            });
        };
        plugin.effect_product = function () {
            var $area_list_template=$element.find('.area-list-template');
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            plugin.set_menu();
            plugin.load_website_template_by_category_by_click();
            plugin.init_pagination();
            plugin.effect_product();


        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_listtemplatecategory_frontend = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_listtemplatecategory_frontend')) {
                var plugin = new $.view_listtemplatecategory_frontend(this, options);

                $(this).data('view_listtemplatecategory_frontend', plugin);

            }

        });

    }

})(jQuery);
