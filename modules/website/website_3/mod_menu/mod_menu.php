<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$list		= ModMenuHelper::getList($params);
$base		= ModMenuHelper::getBase($params);
$active		= ModMenuHelper::getActive($params);
$active_id 	= $active->id;
$path		= $base->tree;



$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/sticky-master/jquery.sticky.js');

$showAll	= $params->get('showAllChildren');
$class_sfx	= htmlspecialchars($params->get('class_sfx'));
$enable_main_menu_style_item=JUtility::toStrictBoolean($params->get('menu_config.enable_main_menu_style_item'));

$main_menu_style_item=$params->get('menu_config.main_menu_style_item');
if(trim($main_menu_style_item)!=='') {
	$list_style_main_menu_style_item=UtilityHelper::get_build_css($main_menu_style_item);
}

$main_menu_style_item_open=$params->get('menu_config.main_menu_style_item_open');
$enable_main_menu_style_item_open=JUtility::toStrictBoolean($params->get('menu_config.enable_main_menu_style_item_open'));

$enable_sticky=$params->get('sticky',0);
$scriptId="module_menu_".$module->id;
ob_start();
?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			<?php if($enable_sticky){ ?>
			$("#module_<?php echo $module->id ?>").sticky({topSpacing:0});
			<?php } ?>
		});
	</script>
<?php
$script=ob_get_clean();
$script=JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);






if(trim($main_menu_style_item_open)!=='') {
	$list_style_main_menu_style_item_open=UtilityHelper::get_build_css($main_menu_style_item_open);
}
if (count($list))
{
	?>
	<div id="module_<?php echo $module->id ?>" data-block-parent-id="<?php echo  $module->position ?>" data-block-id="<?php echo  $module->id ?>">
	<?php
	require JModuleHelper::getLayoutPath('mod_menu', $params->get('layout', 'default'));
	?>
	</div>
	<?php
}
ob_start();
?>
<style type="text/css">
	<?php if($enable_main_menu_style_item&&$list_style_main_menu_style_item){ ?>
		div[data-block-parent-id="<?php echo  $module->position ?>"][ data-block-id="<?php echo  $module->id ?>"] ul.menu >li > a
		{
			<?php
			$none_hover=$list_style_main_menu_style_item['none_hover'];
			foreach($none_hover as $key=>$value)
			{
				echo "$key:$value;";
			}
			  ?>
		}
	div[data-block-parent-id="<?php echo  $module->position ?>"][ data-block-id="<?php echo  $module->id ?>"] ul.menu >li > a:hover
	{
	<?php
        $none_hover=$list_style_main_menu_style_item['hover'];
        foreach($none_hover as $key=>$value)
        {
            echo "$key:$value;";
        }
          ?>
	}
	<?php } ?>

	<?php if($enable_main_menu_style_item_open&&$list_style_main_menu_style_item_open){ ?>
		div[data-block-parent-id="<?php echo  $module->position ?>"][ data-block-id="<?php echo  $module->id ?>"] ul.menu >li.open > a
		{
			<?php
			$none_hover=$list_style_main_menu_style_item_open['none_hover'];
			foreach($none_hover as $key=>$value)
			{
				echo "$key:$value;";
			}
			  ?>
		}
	div[data-block-parent-id="<?php echo  $module->position ?>"][ data-block-id="<?php echo  $module->id ?>"] ul.menu >li.open > a:hover
	{
	<?php
        $none_hover=$list_style_main_menu_style_item_open['hover'];
        foreach($none_hover as $key=>$value)
        {
            echo "$key:$value;";
        }
          ?>
	}
	<?php } ?>
</style>
<?php
$css=ob_get_clean();
$css=JUtility::remove_string_css($css);
$doc->addStyleDeclaration($css);

?>