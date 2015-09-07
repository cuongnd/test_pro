<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 4/20/2015
 * Time: 2:56 PM
 */
defined('_JEXEC') or die;
JHTML::_('behavior.formvalidation');
?>
<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
<script src="<?php echo JUri::root().'modules/mod_nav_tabs/assets/mod_nav_tabs.js'?>"></script>
<link rel="stylesheet" href="<?php echo JUri::root().'modules/mod_nav_tabs/assets/mod_nav_tabs.css' ?>">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<?php
$tabs=explode(';',$tabs);
$count = 0;
?>
<style>
    .class-tabs .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active{
        background: <?php echo $active; ?> !important;
    }
</style>
<div id="tabs" class="class-tabs">
    <!-- Nav tabs -->
    <ul style="background: <?php echo $background_tabs; ?>">
        <?php foreach($tabs as $tab): ?>
            <li>
                <a href="#tabs-<?php echo $count ?>" style="color: <?php echo $color_tabs; ?>"><?php echo $tab ?></a>
            </li>
            <?php $count++; ?>
        <?php endforeach; ?>
    </ul>

    <!-- Tab panes -->
    <div style="background: <?php echo $background_content?>">
        <?php $count = 0; ?>
        <?php foreach($tabs as $tab): ?>
            <div id="tabs-<?php echo $count ?>"><?php echo $tab ?></div>
            <?php $count++; ?>
        <?php endforeach; ?>
    </div>


</div>