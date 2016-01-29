<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancesearch/tmpl/default.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); 

$config =& JblanceHelper::getConfig();

$document = & JFactory::getDocument(); 
$document->addStyleSheet("components/com_jblance/css/$config->theme"); 
$document->addStyleSheet("modules/mod_jblancesearch/css/style.css"); 

$set_Itemid	= intval($params->get('set_itemid', 0));
$Itemid = ($set_Itemid > 0) ? '&Itemid='.$set_Itemid : '';

$sh_category = $params->get('category', 1);
$sh_status 	= $params->get('status', 1);
$sh_budget 	= $params->get('budget', 1);
?>

<form action="index.php" method="get" name="userForm" >
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="20%" align="left"><?php echo JText::_('MOD_JBLANCE_ENTER_KEYWORD'); ?>:<br>
			<input type="text" class="inputbox jbl_dropdown" name="keyword" size="27" maxlength="255" /></td>
		</tr>
		<?php if($sh_status == 1){ ?>
		<tr>
			<td valign="top" align="left">
				<?php echo JText::_('MOD_JBLANCE_STATUS'); ?>:<br>
				<?php $list_categ = ModJblanceSearchHelper::getSelectProjectStatus();	   					   		
				echo $list_categ; ?>
			</td>
		</tr>
		<?php } ?>
		<?php if($sh_category == 1){ ?>
		<tr>
			<td valign="top" align="left">
				<?php echo JText::_('MOD_JBLANCE_CATEGORY'); ?>:<br>
				<?php $list_categ = ModJblanceSearchHelper::getListJobCateg();	   					   		
				echo $list_categ; ?>
			</td>
		</tr>
		<?php } ?>
		<?php if($sh_budget == 1){ ?>
		<tr>
			<td valign="top" align="left">
				<?php echo JText::_('MOD_JBLANCE_MIN_BUDGET'); ?>:<br>
				<input type="text" class="inputbox" name="min_bud" size="10" maxlength="10" />
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<?php echo JText::_('MOD_JBLANCE_MAX_BUDGET'); ?>:<br>
				<input type="text" class="inputbox" name="max_bud" size="10" maxlength="10" />
			</td>
		</tr>
		<?php } ?>
	</table>
	
	<div class="sp10">&nbsp;</div>
	
	<div  align="center">
		<input type="submit" class="button" value="<?php echo JText::_('MOD_JBLANCE_SEARCH'); ?>" /><br />
	</div>
	<div style="clear:both;"></div>
	
	<input type="hidden" name="option" value="com_jblance"/>
	<input type="hidden" name="view" value="project"/>
	<input type="hidden" name="layout" value="searchproject"/>
	<input type="hidden" name="Itemid" value="<?php echo $set_Itemid; ?>"/>
</form>
<script type="text/javascript">
	baseUrl = '<?php echo JURI::base(); ?>';
</script>
