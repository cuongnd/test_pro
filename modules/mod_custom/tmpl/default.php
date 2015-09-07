<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$fileInclude=$params->get('includefile');
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/modules/mod_custom/includefiles/css/style.css');
?>
<?php if($fileInclude){ ?>

<?php
    include JPATH_BASE.'/modules/mod_custom/includefiles/'.$fileInclude ?>
<?php }else{
    $module->content=str_replace('http://www.admin.etravelservice.com','http://admin.etravelservice.com:81',$module->content);
    ?>

<div class="custom<?php echo $moduleclass_sfx ?> module-custom-html "  <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage');?>)"<?php endif;?> >
    <?php echo $module->content?$module->content:'please input content here !';?>
</div>
<?php
    require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
    $enableEditWebsite=UtilityHelper::getEnableEditWebsite();
    if($enableEditWebsite)
    {
        ?>
        <div><button class="btn btn-danger save-content-module pull-right" type="button"><i class="fa-save"></i>Save</button></div>
        <?php
    }
    ?>
<?php } ?>
