<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";a:2:{s:4:"body";s:0:"";s:4:"head";a:2:{s:11:"styleSheets";a:1:{s:92:"http://etravelservice.com:81//templates/sprflat/html/mod_menu/assets/css/tourmanagermenu.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}}s:7:"scripts";a:1:{s:90:"http://etravelservice.com:81//templates/sprflat/html/mod_menu/assets/js/tourmanagermenu.js";a:4:{s:11:"callingFile";s:93:"Calling file: H:\project\test_pro\templates\sprflat\html\mod_menu\tourmanagermenu.php line  8";s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}}}}s:6:"result";s:1265:"
<div role="tabpanel" class="menu_tour">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="tour_menu_1121">
                                                                                    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
                                                                                    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        active_tab_pane_index=$('li.active').closest('.tab-pane').index();
        $('#tour_menu_1121 a:eq('+active_tab_pane_index+')').tab('show');
        $('.list-menu-item').each(function(){
            self= $(this);
            var numitems =  self.find("li").length;
            total_column=numitems / 2;
            total_column=Math.round(total_column);
            self.css({
                '-webkit-column-count':total_column,
                '-moz-column-count':total_column,
                'column-count':total_column
            });
        });
        $('a[menu_item_id="226"]').click(function(){
            menu_item_active_id=$(this).attr('menu_item_id');
            window.location.href = this_host+'?Itemid='+menu_item_active_id;
        });

    });
</script>



";}