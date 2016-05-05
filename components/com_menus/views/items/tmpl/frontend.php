<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$root_menu=reset($this->items);
$children_menu_item = array();
foreach ($this->items as $v) {
    $pt = $v->parent_id;
    $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
    $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
    array_push($list, $v);
    $children_menu_item[$pt] = $list;
}
unset($children_menu_item['list_root']);
$render_menu=function($function_call_back,$menu_item_id=0,$children_menu_item, $level=0, $max_level=999,$column=3) {
    $list_list_menu_item=$children_menu_item[$menu_item_id];
    $list_list_menu_item=array_chunk($list_list_menu_item,$column);
    $level1=$level+1;
    $column1=$column-1;
    foreach($list_list_menu_item AS $list_menu_item) {
        ?>
        <div class="row">
            <?php foreach($list_menu_item as $menu_item){ ?>
                <div class="col-md-<?php echo round(12/$column) ?>">

                    <?php if(count($children_menu_item[$menu_item->id])){ ?>
                    <h3 class="title"><a href="<?php echo JUri::root() ?>index.php?Itemid=<?php echo $menu_item->id ?>"><?php echo $menu_item->title ?></a></h3>
                    <?php }else{ ?>
                        <a href="<?php echo JUri::root() ?>index.php?Itemid=<?php echo $menu_item->id ?>"><?php echo $menu_item->title ?></a>
                    <?php } ?>
                    <?php $function_call_back($function_call_back,$menu_item->id,$children_menu_item,$level1,$max_level,$column1); ?>
                </div>
            <?php } ?>
        </div>
        <?php
    }
};

$render_menu($render_menu,$root_menu->id,$children_menu_item);
    ?>

