<?php
$lessInput = JPATH_ROOT . '/templates/sprflat/html/mod_menu/assets/less/tourmanagermenu.less';
$cssOutput = JPATH_ROOT . '/templates/sprflat/html/mod_menu/assets/css/tourmanagermenu.css';
require_once JPATH_ROOT.'/components/com_bookpro/helpers/bookpro.php';
BookProHelperFrontEnd::compileLess($lessInput, $cssOutput);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/templates/sprflat/html/mod_menu/assets/css/tourmanagermenu.css');
$doc->addScript(JUri::root().'/templates/sprflat/html/mod_menu/assets/js/tourmanagermenu.js');
$app=JFactory::getApplication();
$menu=$app->getMenu('site');
$active_menu=$menu->getActive();
?>

<div role="tabpanel" class="menu_tour">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="tour_menu_<?php echo $module->id ?>">
        <?php $i=1; ?>
        <?php foreach ($list as $menu) { ?>
            <?php if($menu->level==1){ ?>
                    <li role="presentation" class=""><i class="<?php echo $menu1->icon ?>"></i><a menu_item_id="<?php echo $menu->id ?>" href="#tab_item_<?php echo $menu->id ?>" aria-controls="tab_item_<?php echo $menu->id ?>" role="tab" data-toggle="tab"><?php echo $menu->title ?></a></li>
                <?php } ?>
            <?php $i++ ?>
        <?php } ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <?php $i=1; ?>
        <?php foreach ($list as $menu) { ?>
            <?php if($menu->level==1){ ?>
                <?php

                ?>
                <div role="tabpanel" class="tab-pane  " id="tab_item_<?php echo $menu->id ?>">
                    <ul class="pull-left list-menu-item">
                        <?php foreach ($list as $menu1) { ?>
                            <?php if($menu1->parent_id==$menu->id){ ?>
                                <li class="<?php echo $active_menu->id==$menu1->id?' active ':'' ?>"><i class="<?php echo $menu1->icon ?>"></i><a href="<?php echo $menu1->flink ?>"><?php echo $menu1->title ?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php $i++ ?>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        active_tab_pane_index=$('li.active').closest('.tab-pane').index();
        $('#tour_menu_<?php echo $module->id ?> a:eq('+active_tab_pane_index+')').tab('show');
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



